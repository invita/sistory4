var F = function(args){
    //console.log("EntityDetails", args);

    var create = function() {
        var rowValue = args.row ? args.row : {};
        //console.log("entity args", args);
        console.log("rowValue", rowValue);

        var struct_type = rowValue.struct_type;

        args.createMainTab();

        // *** Basic Tab ***

        // -- System fields
        args.basicTab = args.createContentTab();
        args.basicTab.panel = new si4.widget.si4Panel({ parent:args.basicTab.content.selector });
        args.basicTab.panelGroup = args.basicTab.panel.addGroup(si4.translate("entityGroup_adminFields"));
        args.basicTab.form = new si4.widget.si4Form({
            parent: args.basicTab.panelGroup.content.selector,
            captionWidth: "90px"
        });

        args.basicTab.fieldId = args.basicTab.form.addInput({
            name: "id",
            value: rowValue.id,
            type: "text",
            caption: si4.translate("field_id"),
            readOnly: true,
        });

        args.basicTab.fieldId = args.basicTab.form.addInput({
            name: "handle_id",
            value: rowValue.handle_id,
            type: "text",
            caption: si4.translate("field_handleId"),
            readOnly: true,
        });

        args.basicTab.fieldStructTypeId = args.basicTab.form.addInput({
            name: "struct_type",
            value: rowValue.struct_type,
            type: "select",
            caption: si4.translate("field_structType"),
            values: si4.data.structTypes,
            disabled: true,
        });

        args.basicTab.fieldEntityTypeId = args.basicTab.form.addInput({
            name: "entity_type",
            value: rowValue.entity_type,
            type: struct_type == "file" ? "hidden" : "select",
            caption: si4.translate("field_entityType"),
            values: si4.data.entityTypes,
            addEmptyOption: true,
        });

        args.basicTab.fieldParent = args.basicTab.form.addInput({
            name: "parent",
            value: rowValue.parent,
            type: "text",
            caption: si4.translate("field_parent"),
            readOnly: struct_type == "file"
        });

        args.basicTab.fieldParent = args.basicTab.form.addInput({
            name: "primary",
            value: rowValue.primary,
            type: struct_type == "file" ? "hidden" : "text",
            caption: si4.translate("field_primary"),
        });

        args.basicTab.entityActive = args.basicTab.form.addInput({
            name: "active",
            value: rowValue.active,
            type: "checkbox",
            caption: si4.translate("field_active"),
            style: { marginTop: "4px" },
        });
        /*
        args.basicTab.entityEnabled = args.basicTab.form.addInput({
            name: "enabled",
            value: rowValue.enabled,
            type: "checkbox",
            caption: si4.translate("field_enabled")
        });
        */

        if (rowValue.struct_type == "file") {
            args.basicTab.physicalFile = args.basicTab.form.addInput({
                name: "physicalFile",
                value: "",
                type: "file",
                caption: si4.translate("field_physicalFile")
            });
            args.basicTab.realFileName = args.basicTab.form.addInput({ name: "realFileName", value: "", type: "hidden" });
            args.basicTab.tempFileName = args.basicTab.form.addInput({ name: "tempFileName", value: "", type: "hidden" });

            args.basicTab.physicalFile.selector.change(function() {
                //console.log("change", fieldFile.getValue());
                var url = "/admin/upload/upload-file";
                var formData = new FormData();
                formData.append("file", args.basicTab.physicalFile.getValue());
                //console.log("post ", url, formData);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        console.log("callback", response);
                        if (response.status) {
                            args.uploadedFile = response.data;
                            args.basicTab.realFileName.setValue(response.data.realFileName);
                            args.basicTab.tempFileName.setValue(response.data.tempFileName);

                            // File Preview
                            var fileExt = response.data.realFileName.split(".").pop();
                            if (["jpg", "jpeg", "png"].indexOf(fileExt.toLowerCase()) != -1) {
                                args.basicTab.filePreviewImg.selector.attr("src", response.data.url);
                            }


                            // Put to XML...
                            var xmlStr = args.xmlTab.fieldXml.getValue();
                            var parser = new DOMParser();
                            var xmlDoc = parser.parseFromString(xmlStr, "text/xml");

                            // Find <METS:fileSec>
                            var xmlFileSec = xmlDoc.querySelector("fileSec");

                            // Remove all children from fileSec
                            for (var i = xmlFileSec.children.length -1; i >= 0; i--)
                                xmlFileSec.removeChild(xmlFileSec.children[i]);

                            // METS:fileGrp
                            var fileGrpEl = xmlDoc.createElement("METS:fileGrp");
                            fileGrpEl.setAttribute("USE", "DEFAULT");
                            xmlFileSec.appendChild(fileGrpEl);

                            // METS:file
                            var fileEl = xmlDoc.createElement("METS:file");
                            fileEl.setAttribute("ID", response.data.realFileName);
                            fileEl.setAttribute("USE", "DEFAULT");
                            fileEl.setAttribute("CREATED", new Date().toISOString());
                            fileEl.setAttribute("SIZE", response.data.size);
                            fileEl.setAttribute("CHECKSUM", response.data.checksum);
                            fileEl.setAttribute("CHECKSUMTYPE", "md5");
                            fileEl.setAttribute("MIMETYPE", response.data.mimeType);
                            fileEl.setAttribute("OWNERID", si4.data.currentUser.id);
                            fileGrpEl.appendChild(fileEl);

                            // METS:FLocat
                            var fLocatEl = xmlDoc.createElement("METS:FLocat");
                            fLocatEl.setAttribute("ID", args.row.handle_id);
                            fLocatEl.setAttribute("USE", "HTTP");
                            fLocatEl.setAttribute("LOCTYPE", "URL");
                            fLocatEl.setAttribute("title", response.data.realFileName);
                            fLocatEl.setAttribute("href", "http://hdl.handle.net/11686/"+args.row.handle_id);
                            fileEl.appendChild(fLocatEl);


                            // Put xml into editor
                            var xmlText = new XMLSerializer().serializeToString(xmlDoc);
                            var xmlTextPretty = vkbeautify.xml(xmlText);
                            args.xmlTab.fieldXml.setValue(xmlTextPretty);

                            //args.xmlTab.selectTab();
                            args.xmlTab.fieldXml.codemirror.refresh();

                            //args.saveEntity(true);

                        }
                    }
                });
            });
        }

        args.basicTab.saveButton = args.basicTab.form.addInput({
            caption: si4.translate("field_actions"),
            value: si4.translate("button_save"),
            type:"submit",
        });
        args.basicTab.saveButton.selector.click(args.saveEntity);


        // -- Preview metadata
        args.basicTab.panelGroupPreview = args.basicTab.panel.addGroup(si4.translate("entityGroup_mdPreview"));
        args.basicTab.panelGroupPreview.selector.css("margin-left", "20px");
        args.basicTab.formPreview = new si4.widget.si4Form({
            parent: args.basicTab.panelGroupPreview.content.selector,
            captionWidth: "90px"
        });

        if (struct_type == "file") {
            args.basicTab.fieldFileName = args.basicTab.formPreview.addInput({
                name:"fileName",
                value:rowValue.fileName,
                type:"text",
                caption:si4.translate("field_name"),
                readOnly: true
            });
            args.basicTab.fieldFileFormat = args.basicTab.formPreview.addInput({
                name:"format",
                value:"",//rowValue.format,
                type:"text",
                caption:si4.translate("field_mimeType"),
                readOnly: true
            });
            args.basicTab.fieldFileSize = args.basicTab.formPreview.addInput({
                name:"fileSize",
                value:"",//rowValue.date,
                type:"text",
                caption:si4.translate("field_size"),
                readOnly: true
            });
            args.basicTab.fieldFileTimestamp = args.basicTab.formPreview.addInput({
                name:"fileTimestamp",
                value:"",
                type:"text",
                caption:si4.translate("field_timestamp"),
                readOnly: true
            });
            args.basicTab.fieldFileChecksum = args.basicTab.formPreview.addInput({
                name:"fileChecksum",
                value:"",
                type:"text",
                caption:si4.translate("field_checksum"),
                readOnly: true
            });
            args.basicTab.fieldFileChecksumAlgo = args.basicTab.formPreview.addInput({
                name:"fileChecksumAlgo",
                value:"MD5",
                type:"text",
                caption:si4.translate("field_checksumAlgo"),
                readOnly: true
            });
            args.basicTab.filePreviewDiv = new si4.widget.si4Element({
                parent: args.basicTab.panelGroupPreview.content.selector });
            args.basicTab.filePreviewImg = new si4.widget.si4Element({
                parent: args.basicTab.filePreviewDiv.selector, tagName: "img" });
            args.basicTab.filePreviewImg.selector.css({
                marginTop: "10px", maxWidth: "256px"
            });

            // File Preview
            var fileName = rowValue.fileName;
            if (fileName) {
                var fileUrl = rowValue.fileUrl;
                var fileExt = fileName.split(".").pop();
                if (["jpg", "jpeg", "png"].indexOf(fileExt.toLowerCase()) != -1) {
                    args.basicTab.filePreviewImg.selector.attr("src", fileUrl);
                }
            }
        } else {
            args.basicTab.fieldTitle = args.basicTab.formPreview.addInput({
                name:"title",
                value:rowValue.title,
                type:"text",
                caption:si4.translate("field_title"),
                readOnly: true
            });
            args.basicTab.fieldAuthor = args.basicTab.formPreview.addInput({
                name:"author",
                value:rowValue.creator,
                type:"text",
                caption:si4.translate("field_creator"),
                readOnly: true
            });
            args.basicTab.fieldYear = args.basicTab.formPreview.addInput({
                name:"year",
                value:rowValue.date,
                type:"text",
                caption:si4.translate("field_year"),
                readOnly: true
            });
        }


        // *** Xml Tab ***
        args.xmlTab = args.createContentTab("xmlTab", { canClose: false });
        args.xmlTab.panel = new si4.widget.si4Panel({ parent: args.xmlTab.content.selector });
        args.xmlTab.panelGroup = args.xmlTab.panel.addGroup();
        args.xmlTab.form = new si4.widget.si4Form({
            parent: args.xmlTab.panelGroup.content.selector,
            captionWidth: "90px"
        });

        // -- File upload
        var fieldFile = args.xmlTab.form.addInput({
            name: "file",
            value: "",
            type: "file",
            caption:si4.translate("field_xmlFromFile"),
        });
        fieldFile.selector.change(function() {
            //console.log("change", fieldFile.getValue());
            var url = "/admin/upload/show-content";
            var formData = new FormData();
            formData.append("file", fieldFile.getValue());
            //console.log("post ", url, formData);
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    console.log("callback", response);
                    if (response.status)
                        args.xmlTab.fieldXml.setValue(response.data);
                }
            });
        });

        // -- Xml editor (codemirror)
        args.xmlTab.fieldXml = args.xmlTab.form.addInput({
            name: "xml",
            value: rowValue.xmlData,
            type: "codemirror",
            caption: false
        });
        args.xmlTab.fieldXml.selector.css("margin-bottom", "5px");
            //.css("margin-left", "100px");
        args.xmlTab.fieldXml.codemirror.setSize($(window).width() -20);
        args.xmlTab.onActive(function(tabArgs) {
            args.xmlTab.fieldXml.codemirror.refresh();
        });

        args.xmlTab.saveButton = args.xmlTab.form.addInput({
            //caption: si4.translate("field_actions"),
            value: si4.translate("button_save"),
            type:"submit",
            tagClass: "fixedBottomRight biggerSubmit"
        });
        args.xmlTab.saveButton.selector.click(args.saveEntity);


        // *** Metadata Editor Tab ***
        if (struct_type != "file") {
            args.editorTab = args.createContentTab("mdEditorTab", {canClose: false});
            args.editorTab.panel = new si4.widget.si4Panel({parent: args.editorTab.content.selector});
            args.editorTab.panelGroup = args.editorTab.panel.addGroup();
            args.editorTab.form = new si4.widget.si4Form({
                parent: args.editorTab.panelGroup.content.selector,
                captionWidth: "90px"
            });

            args.editorTab.fields = {};


            for (var fieldIdx in si4.entity.mdHelper.dcFieldOrder) {
                var fieldName = si4.entity.mdHelper.dcFieldOrder[fieldIdx];
                var fieldBP = si4.entity.mdHelper.dcBlueprint[fieldName];

                args.editorTab.fields[fieldName] = args.editorTab.form.addInput({
                    name: fieldName,
                    type: fieldBP.inputType,
                    caption: fieldBP.translation,
                    withCode: fieldBP.withCode,
                    values: fieldBP.values,
                    isArray: true,
                });
            }

            args.editorTab.saveButton = args.editorTab.form.addInput({
                caption: si4.translate("field_actions"),
                value: si4.translate("button_save"),
                type: "submit",
            });

            // Convert DC fields to XML
            args.editorTab.saveButton.selector.click(function (val) {
                var formValue = args.editorTab.form.getValue();
                console.log("formValue", formValue);

                var xmlStr = args.xmlTab.fieldXml.getValue();
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xmlStr, "text/xml");

                // Find <METS:mdWrap MDTYPE="DC> + <xmlData>"
                var xmlDC = xmlDoc.querySelector("mdWrap[MDTYPE=DC] xmlData");

                var fieldNames = si4.entity.mdHelper.dcFieldOrder.slice(0); // Clone
                fieldNames.reverse();
                for (var fieldIdx in fieldNames) {
                    var fieldName = fieldNames[fieldIdx];
                    var fieldBP = si4.entity.mdHelper.dcBlueprint[fieldName];
                    var fieldValues = formValue[fieldName];

                    for (var childIdx = xmlDC.children.length - 1; childIdx >= 0; childIdx--)
                        if (xmlDC.children[childIdx].tagName == "dcterms:" + fieldName)
                            xmlDC.children[childIdx].remove();

                    if (!fieldValues.length || fieldValues.length == 1 && !fieldValues[0]) continue;

                    for (var i = fieldValues.length - 1; i >= 0; i--) {
                        var fieldValue = fieldValues[i];
                        var newEl = xmlDoc.createElement("dcterms:" + fieldName);

                        if (fieldBP.withCode) {
                            var attr = xmlDoc.createAttribute(fieldBP.codeXmlName);
                            attr.value = fieldValue.codeId;
                            newEl.attributes.setNamedItem(attr);
                            newEl.textContent = fieldValue.value;
                        } else {
                            newEl.textContent = fieldValue;
                        }

                        if (fieldBP.addXmlAttrs) {
                            for (var i in fieldBP.addXmlAttrs) {
                                var addAttr = xmlDoc.createAttribute(fieldBP.addXmlAttrs[i].name);
                                addAttr.value = fieldBP.addXmlAttrs[i].value;
                                newEl.attributes.setNamedItem(addAttr);
                            }
                        }

                        if (xmlDC.children.length) {
                            xmlDC.insertBefore(newEl, xmlDC.children[0]);
                        } else {
                            xmlDC.appendChild(newEl);
                        }
                    }
                }


                // Put xml into editor
                var xmlText = new XMLSerializer().serializeToString(xmlDoc);
                var xmlTextPretty = vkbeautify.xml(xmlText);
                args.xmlTab.fieldXml.setValue(xmlTextPretty);

                //args.xmlTab.selectTab();
                args.xmlTab.fieldXml.codemirror.refresh();

                args.saveEntity();
            });

            // Parse XML to DC fields
            args.editorTab.onActive(function () {
                args.editorTab.form.allInputs.clear();
                var xmlStr = args.xmlTab.fieldXml.getValue();
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xmlStr, "text/xml");

                // Find <METS:mdWrap MDTYPE="DC> + <xmlData>"
                var xmlDC = xmlDoc.querySelector("mdWrap[MDTYPE=DC] xmlData");
                var formValFromXml = {};
                for (var i = 0; i < xmlDC.children.length; i++) {
                    var dcElement = xmlDC.children[i];
                    // dcElement.tagName;
                    // dcElement.textContent;
                    // dcElement.attributes;

                    if (dcElement.tagName.indexOf(":") == -1) continue;
                    var fieldName = dcElement.tagName.split(":")[1];
                    var fieldValue = dcElement.textContent;

                    if (!si4.entity.mdHelper.dcBlueprint[fieldName]) continue;
                    var fieldBP = si4.entity.mdHelper.dcBlueprint[fieldName];

                    var inputValue;
                    if (fieldBP.withCode) {
                        inputValue = {
                            codeId: dcElement.attributes.getNamedItem(fieldBP.codeXmlName).value,
                            value: fieldValue
                        };
                    } else {
                        inputValue = fieldValue;
                    }

                    if (!formValFromXml[fieldName]) formValFromXml[fieldName] = [];
                    formValFromXml[fieldName].push(inputValue);
                }

                window.xmlDoc = xmlDoc;
                console.log(formValFromXml);
                args.editorTab.form.setValue(formValFromXml);
            });
        }


        // *** Relations Tab ***
        if (struct_type != "file") {
            args.relationsTab = args.createContentTab("relationsTab", { canClose: false });
            args.relationsTab.addHierarchyElement = function(hArgs) {

                var rowEl = new si4.widget.si4Element({ parent: hArgs.container, tagClass: "entityHierarchyRow" });
                if (hArgs.addClass) rowEl.selector.addClass(hArgs.addClass)
                if (hArgs.entityData.struct_type == "entity") rowEl.selector.addClass("stEntity");
                if (hArgs.entityData.struct_type == "collection") rowEl.selector.addClass("stCollection");
                rowEl.selector.css("margin-left", hArgs.indent+"px");
                var line = '<span class="ehTitle">' +hArgs.entityData.title + '</span>';
                if (hArgs.entityData.creator) line = '<span class="ehCreator">' +hArgs.entityData.creator+ '</span> : '+ line;
                if (hArgs.entityData.date) line = line + ' '+'<span class="ehDate">' +hArgs.entityData.date+ '</span>';
                line = '<span class="ehId">[' +hArgs.entityData.id + ']</span> ' +line;
                rowEl.selector.html(line);

                rowEl.selector.click(function() {
                    si4.loadModule({
                        moduleName: "Entities/EntityDetails",
                        caller: "entityList",
                        id: hArgs.entityData.id,
                        row: hArgs.entityData,
                        tabPage: args.tabPage
                    });
                });
            };

            args.relationsTab.renderHierarchy = function(response) {

                var indentStep = 25;

                var indent = 0;

                args.relationsTab.content.selector.empty();
                var containerEl = new si4.widget.si4Element({ parent: args.relationsTab.content.selector, tagClass: "entityHierarchyContainer" });

                // Render parents
                for (var i in response.data.parents) {
                    args.relationsTab.addHierarchyElement({
                        container: containerEl.selector,
                        entityData: response.data.parents[i],
                        addClass: "parent",
                        indent: indent
                    });
                    indent += indentStep;
                }

                // Render current
                args.relationsTab.addHierarchyElement({
                    container: containerEl.selector,
                    entityData: response.data.currentEntity,
                    addClass: "current",
                    indent: indent
                });
                indent += indentStep;

                // Render children
                for (var i in response.data.children) {
                    args.relationsTab.addHierarchyElement({
                        container: containerEl.selector,
                        entityData: response.data.children[i],
                        addClass: "child",
                        indent: indent
                    });
                    indent += indentStep;
                }

            };

            args.relationsTab.onActive(function(tabArgs) {
                si4.api.entityHierarchy({ handle_id: rowValue.handle_id }, function (response) {

                    console.log("entityHierarchy response", response);

                    if (!response.status) {
                        args.relationsTab.statusDiv = new si4.widget.si4Element({ parent: args.relationsTab.content.selector });
                        args.relationsTab.statusDiv.addHtml("Error loading hierarchy.");
                    } else {
                        args.relationsTab.renderHierarchy(response);

                        // response.data.children
                        // response.data.parents
                        // response.data.currentEntity
                    }
                });
            });
        }


        // *** Files Tab ***
        if (struct_type != "file") {
            args.filesTab = args.createContentTab("filesTab", {canClose: false});
            args.filesTab.panel = new si4.widget.si4Panel({parent: args.filesTab.content.selector});
            args.filesTab.panelGroup = args.filesTab.panel.addGroup("TODO: Tabela datotek in možnost dodajanja, brisanja.");
            args.filesTab.form = new si4.widget.si4Form({
                parent: args.filesTab.panelGroup.content.selector,
                captionWidth: "90px"
            });
        }


        /*
        // *** Files Tab ***
        args.filesTab = args.createContentTab("filesTab", { canClose: false });
        args.filesTab.panel = new si4.widget.si4Panel({ parent: args.filesTab.content.selector });
        args.filesTab.panelGroup = args.filesTab.panel.addGroup("TODO: Tabela datotek in možnost dodajanja, brisanja.");
        args.filesTab.form = new si4.widget.si4Form({
            parent: args.filesTab.panelGroup.content.selector,
            captionWidth: "90px"
        });
        */

    };


    args.saveEntity = function(){
        var basicFormValue = args.basicTab.form.getValue();
        var xmlFormValue = args.xmlTab.form.getValue();

        var formValue = Object.assign({}, basicFormValue, xmlFormValue);
        //console.log("formValue", basicFormValue);
        si4.api["saveEntity"](formValue, function(data) {
            if (data.status) {
                if (args.row.struct_type == "file") {
                    args.basicTab.realFileName.setValue("");
                    args.basicTab.tempFileName.setValue("");
                }
                if (confirm(si4.translate("saved_confirm_close"))) {
                    args.mainTab.destroyTab();
                }
            } else {
                si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
            }
            //console.log("saveEntity callback", data);
        });
    };


    if (!args.row || !args.row.id) {
        args.row = {};
        switch (args.caller) {
            case "entityList":default:
                args.row.struct_type = "entity";
                args.row.entity_type = "dependant";
                break;
            case "collectionList":
                args.row.struct_type = "collection";
                args.row.entity_type = "primary";
                break;
            case "fileList":
                args.row.struct_type = "file";
                args.row.entity_type = "primary";
                break;
        }
        args.row.indexed = true;
        args.row.enabled = true;

        si4.api["reserveEntityId"]({ struct_type: args.row.struct_type }, function(response) {
            args.row.id = response.data.id;
            args.row.handle_id = response.data.handle_id;
            args.row.xmlData = si4.entity.template.getEmptyMetsXml({
                id: args.row.id,
                handleId: args.row.handle_id,
                structType: args.row.struct_type,
            });
            create();
        });
    } else {
        create();
    }

};