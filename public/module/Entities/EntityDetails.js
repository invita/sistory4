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
            caption: si4.translate("field_systemId"),
            readOnly: true,
        });

        args.basicTab.fieldHandleId = args.basicTab.form.addInput({
            name: "handle_id",
            value: rowValue.handle_id,
            type: "text",
            caption: si4.translate("field_handleId"),
            //readOnly: rowValue.handle_id ? true : false,
            readOnly: false,
        });

        args.basicTab.fieldStructTypeId = args.basicTab.form.addInput({
            name: "struct_type",
            value: rowValue.struct_type,
            type: "select",
            caption: si4.translate("field_structType"),
            values: si4.data.structTypes,
            disabled: true,
        });

        args.basicTab.fieldParent = args.basicTab.form.addInput({
            name: "parent",
            value: rowValue.parent,
            type: "text",
            caption: si4.translate("field_parent"),
            readOnly: struct_type == "file",
            style: { marginTop: "10px" }
        });

        args.basicTab.fieldEntityTypeId = args.basicTab.form.addInput({
            name: "entity_type",
            value: rowValue.entity_type,
            type: struct_type == "file" ? "hidden" : "select",
            caption: si4.translate("field_entityType"),
            values: si4.data.entityTypes,
            addEmptyOption: true,
            disabled: true,
        });

        args.basicTab.fieldStructSubtype = args.basicTab.form.addInput({
            name: "struct_subtype",
            value: rowValue.struct_subtype,
            type: "text",
            caption: si4.translate("field_structSubtype"),
            //readOnly: true,
        });

        args.basicTab.fieldChildOrder = args.basicTab.form.addInput({
            name: "child_order",
            value: rowValue.child_order,
            type: "number",
            caption: si4.translate("field_childOrder"),
            //readOnly: true,
        });

        args.basicTab.fieldPrimary = args.basicTab.form.addInput({
            name: "primary",
            value: rowValue.primary,
            type: struct_type == "file" ? "hidden" : "text",
            caption: si4.translate("field_primary"),
            readOnly: true,
        });

        args.basicTab.fieldCollection = args.basicTab.form.addInput({
            name: "collection",
            value: rowValue.collection,
            type: struct_type == "entity" ? "text" : "hidden",
            caption: si4.translate("field_collection"),
            readOnly: true,
        });

        args.basicTab.entityActive = args.basicTab.form.addInput({
            name: "active",
            value: rowValue.active,
            type: "checkbox",
            caption: si4.translate("field_active"),
            style: { marginTop: "6px", marginBottom: "4px" },
        });
        args.basicTab.entityActive.selector.change(function(e) {
            var value = args.basicTab.entityActive.getValue();
            var entityActive = value ? "Active" : "Inactive";
            si4.xmlMutators.mutateXml(args.xmlTab.fieldXml, "entityActive", { value: entityActive });
        });

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
                                args.basicTab.filePreviewDiv.display();
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
                            fileEl.setAttribute("ID", args.row.handle_id);
                            fileEl.setAttribute("OWNERID", response.data.realFileName);
                            fileEl.setAttribute("USE", "DEFAULT");
                            fileEl.setAttribute("CREATED", new Date().toISOString());
                            fileEl.setAttribute("SIZE", response.data.size);
                            fileEl.setAttribute("CHECKSUM", response.data.checksum);
                            fileEl.setAttribute("CHECKSUMTYPE", "md5");
                            fileEl.setAttribute("MIMETYPE", response.data.mimeType);
                            fileGrpEl.appendChild(fileEl);

                            // METS:FLocat
                            var fLocatEl = xmlDoc.createElement("METS:FLocat");
                            fLocatEl.setAttribute("ID", args.row.handle_id);
                            fLocatEl.setAttribute("USE", "HTTP");
                            fLocatEl.setAttribute("LOCTYPE", "URL");
                            fLocatEl.setAttribute("title", response.data.realFileName);
                            fLocatEl.setAttribute("href", "http://hdl.handle.net/"+si4.data.repositoryInfo.handlePrefix+"/"+args.row.handle_id);
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
                value:rowValue.fileMimeType,
                type:"text",
                caption:si4.translate("field_mimeType"),
                readOnly: true
            });
            args.basicTab.fieldFileSize = args.basicTab.formPreview.addInput({
                name:"fileSize",
                value:rowValue.fileSize,
                type:"text",
                caption:si4.translate("field_size"),
                readOnly: true
            });
            args.basicTab.fieldFileTimestamp = args.basicTab.formPreview.addInput({
                name:"fileTimestamp",
                value:rowValue.fileTimestamp,
                type:"text",
                caption:si4.translate("field_timestamp"),
                readOnly: true
            });
            args.basicTab.fieldFileChecksum = args.basicTab.formPreview.addInput({
                name:"fileChecksum",
                value:rowValue.fileChecksum,
                type:"text",
                caption:si4.translate("field_checksum"),
                readOnly: true
            });
            args.basicTab.fieldFileChecksumAlgo = args.basicTab.formPreview.addInput({
                name:"fileChecksumAlgo",
                value:rowValue.fileChecksumType,
                type:"text",
                caption:si4.translate("field_checksumAlgo"),
                readOnly: true
            });

            // Preview and Download container
            args.basicTab.fileInfoDiv = new si4.widget.si4Element({
                parent: args.basicTab.panelGroupPreview.content.selector, tagClass: "fileInfoDiv" });

            // Download div
            args.basicTab.fileDownloadDiv = new si4.widget.si4Element({
                parent: args.basicTab.fileInfoDiv.selector, tagClass: "fileDownloadDiv" });
            args.basicTab.fileDownloadImg = new si4.widget.si4Element({
                parent: args.basicTab.fileDownloadDiv.selector, tagName: "img" });
            args.basicTab.fileDownloadImg.selector.attr("src", "/img/download.png");
            args.basicTab.fileDownloadSpan = new si4.widget.si4Element({
                parent: args.basicTab.fileDownloadDiv.selector, tagName: "span" });
            args.basicTab.fileDownloadSpan.selector.html("Open");
            args.basicTab.fileDownloadDiv.selector.click(function() {
                window.open(rowValue.fileUrl, "_blank");
            });
            args.basicTab.fileDownloadDiv.displayNone();

            // Preview div
            args.basicTab.filePreviewDiv = new si4.widget.si4Element({
                parent: args.basicTab.fileInfoDiv.selector, tagClass: "filePreviewDiv" });
            args.basicTab.filePreviewImg = new si4.widget.si4Element({
                parent: args.basicTab.filePreviewDiv.selector, tagName: "img" });
            args.basicTab.filePreviewDiv.displayNone();


            // File Preview
            var fileName = rowValue.fileName;
            if (fileName) {
                /*
                var fileUrl = rowValue.fileUrl;
                var fileExt = fileName.split(".").pop();
                if (["jpg", "jpeg", "png"].indexOf(fileExt.toLowerCase()) != -1) {
                    args.basicTab.filePreviewImg.selector.attr("src", fileUrl);
                    args.basicTab.filePreviewDiv.display();
                }
                args.basicTab.fileDownloadDiv.display();
                */

                var fileThumb = rowValue.fileThumb;
                args.basicTab.filePreviewImg.selector.attr("src", fileThumb);
                args.basicTab.filePreviewDiv.display();
                args.basicTab.fileDownloadDiv.display();
            }
        } else {
            args.basicTab.fieldTitle = args.basicTab.formPreview.addInput({
                name:"title",
                value:rowValue.title,
                type:"text",
                caption:si4.translate("field_title"),
                readOnly: true
            });
            if (struct_type == "entity") {
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
        /*
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

            si4.xmlMutators.mutateXml(args.xmlTab.fieldXml, "dcFields", { value: formValue });

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
            if (xmlDC) {
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
            }

            window.xmlDoc = xmlDoc;
            console.log(formValFromXml);
            args.editorTab.form.setValue(formValFromXml);
        });
        */


        // *** Relations Tab ***
        if (struct_type != "file") {
            args.relationsTab = args.createContentTab("relationsTab", { canClose: false });
            args.relationsTab.addHierarchyElement = function(hArgs) {

                var rowEl = new si4.widget.si4Element({ parent: hArgs.container, tagClass: "entityHierarchyRow" });
                if (hArgs.addClass) rowEl.selector.addClass(hArgs.addClass)
                if (hArgs.entityData.struct_type == "entity") rowEl.selector.addClass("stEntity");
                if (hArgs.entityData.struct_type == "collection") rowEl.selector.addClass("stCollection");
                if (hArgs.entityData.struct_type == "file") rowEl.selector.addClass("stFile");
                rowEl.selector.css("margin-left", hArgs.indent+"px");

                var line = "";
                switch (hArgs.entityData.struct_type) {
                    case "entity":case "collection":default:
                        line = '<span class="ehTitle">' +hArgs.entityData.title + '</span>';
                        if (hArgs.entityData.creator)
                            line = '<span class="ehCreator">' +hArgs.entityData.creator+ '</span> : '+ line;
                        if (hArgs.entityData.date)
                            line = line + ' '+'<span class="ehDate">' +hArgs.entityData.date+ '</span>';
                        line = '<span class="ehId">[' +hArgs.entityData.handle_id + ']</span> ' +line;
                        break;
                    case "file":
                        line = '<span class="ehTitle">' +hArgs.entityData.fileName + '</span>';
                        line = '<span class="ehId">[' +hArgs.entityData.handle_id + ']</span> ' +line;
                        break;
                }

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
                    //indent += indentStep;
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
        if (struct_type == "entity") {
            args.filesTab = args.createContentTab("filesTab", {canClose: false});
            args.filesTab.panel = new si4.widget.si4Panel({parent: args.filesTab.content.selector});

            args.filesTab.dataTable = new si4.widget.si4DataTable({
                parent: args.filesTab.content.selector,
                primaryKey: ['id'],
                entityTitleNew: si4.lookup["entity"].entityTitleNew,
                entityTitleEdit: si4.lookup["entity"].entityTitleEdit,
                //filter: { enabled: false },
                dataSource: new si4.widget.si4DataTableDataSource({
                    select: si4.api["entityList"],
                    delete: si4.api["deleteEntity"],
                    filter: { struct_type: "file", parent: rowValue.handle_id },
                    staticData: { parent: rowValue.handle_id },
                    pageCount: 20
                }),
                editorModuleArgs: {
                    moduleName:"Entities/EntityDetails",
                    caller: "fileList"
                },
                canInsert: true,
                canDelete: true,
                tabPage: args.filesTab,
                fields: {
                    id: { caption: "Id" },
                    handle_id: { caption: "Handle Id", hintF: si4.entity.hintHelper.displayEntityInfoDT },
                    parent: { caption: "Parent", hintF: si4.entity.hintHelper.displayEntityInfoDT },
                    struct_type: { canFilter: false, caption: si4.translate("field_structType"), valueTranslatePrefix:"st_" },
                    fileName: { maxCharLength: 50 },
                    //title: { maxCharLength: 100 },
                    //creator: { caption: si4.translate("field_creator"), maxCharLength: 50 },
                },
                showOnlyDefinedFields: true,
                cssClass_table: "si4DataTable_table width100percent"
            });


            /*
            args.filesTab.panelGroup = args.filesTab.panel.addGroup("TODO: Tabela datotek in možnost dodajanja, brisanja.");
            args.filesTab.form = new si4.widget.si4Form({
                parent: args.filesTab.panelGroup.content.selector,
                captionWidth: "90px"
            });
            */
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
        }, function(err) {
            // errorCallback
            alert(si4.translate("save_failed_entity", { reason: "["+err.status+"] "+err.statusText }));
        });
    };


    if (!args.row || !args.row.id) {
        console.log("new", args);
        args.row = {};
        switch (args.caller) {
            case "entityList":default:
                args.row.struct_type = "entity";
                args.row.entity_type = "";
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
            args.row.child_order = response.data.id;
            args.row.handle_id = response.data.handle_id;
            args.row.struct_subtype = response.data.struct_subtype;
            if (args.staticData) {
                for (var i in args.staticData) args.row[i] = args.staticData[i];
            }
            si4.entity.template.getEmptyMetsXml({
                id: args.row.id,
                handleId: args.row.handle_id,
                structType: args.row.struct_type,
            }, function(xmlTemplate) {
                args.row.xmlData = xmlTemplate;
                create();
            });
        });
    } else {
        create();
    }

};