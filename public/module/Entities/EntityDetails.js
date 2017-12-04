var F = function(args){
    //console.log("EntityDetails", args);

    var create = function() {
        var rowValue = args.row ? args.row : {};

        console.log("entity args", args);
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

        args.basicTab.fieldStructTypeId = args.basicTab.form.addInput({
            name: "struct_type",
            value: rowValue.struct_type,
            type: "select",
            caption: si4.translate("field_structType"),
            values: si4.data.structTypes,
            addEmptyOption: true,
        });

        args.basicTab.fieldEntityTypeId = args.basicTab.form.addInput({
            name: "entity_type",
            value: rowValue.entity_type,
            type: "select",
            caption: si4.translate("field_entityType"),
            values: si4.data.entityTypes,
            addEmptyOption: true,
        });

        args.basicTab.fieldParent = args.basicTab.form.addInput({
            name: "parent",
            value: rowValue.parent,
            type: "text",
            caption: si4.translate("field_parent"),
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
            caption:si4.translate("field_creators"),
            readOnly: true
        });
        args.basicTab.fieldYear = args.basicTab.formPreview.addInput({
            name:"year",
            value:rowValue.date,
            type:"text",
            caption:si4.translate("field_year"),
            readOnly: true
        });



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
            caption: si4.translate("field_actions"),
            value: si4.translate("button_save"),
            type:"submit",
        });
        args.xmlTab.saveButton.selector.click(args.saveEntity);


        // *** Metadata Editor Tab ***
        args.editorTab = args.createContentTab("mdEditorTab", { canClose: false });
        args.editorTab.panel = new si4.widget.si4Panel({ parent: args.editorTab.content.selector });
        args.editorTab.panelGroup = args.editorTab.panel.addGroup("TODO: Kot pri SICI, le da polja dobijo podatke iz XML in tudi shranijo v XML...");
        args.editorTab.form = new si4.widget.si4Form({
            parent: args.editorTab.panelGroup.content.selector,
            captionWidth: "90px"
        });

        args.editorTab.fieldTitle = args.editorTab.form.addInput({
            name: "title",
            //value: [rowValue.title],
            type: "text",
            caption: si4.translate("field_title"),
            isArray: true,
        });
        args.editorTab.fieldTitle = args.editorTab.form.addInput({
            name: "creator",
            //value: [rowValue.creator],
            type: "text",
            caption: si4.translate("field_creators"),
            isArray: true,
        });


        // *** Relations Tab ***
        args.relationsTab = args.createContentTab("relationsTab", { canClose: false });
        args.relationsTab.onActive(function(tabArgs) {
            if (args.relationsDataTable) return;
            var tableName = "relations";
            args.relationsDataTable = new si4.widget.si4DataTable({
                parent: args.relationsTab.content.selector,
                primaryKey: ['id'],
                //entityTitleNew: si4.lookup[tableName].entityTitleNew,
                //entityTitleEdit: si4.lookup[tableName].entityTitleEdit,
                //filter: { enabled: false },
                dataSource: new si4.widget.si4DataTableDataSource({
                    select: si4.api["entityRelationsList"],
                    delete: si4.api["deleteEntityRelation"],
                    updateRow: si4.api["saveEntityRelation"],
                    //moduleName:"Entities/EntityList",
                    staticData : { entityId: rowValue.id },
                    pageCount: 50
                }),
                //editorModuleArgs: {
                //    moduleName:"Entities/EntityDetails",
                //},
                filter: {
                    enabled: false
                },
                canInsert: true,
                canDelete: true,
                //editable: true,
                customInsert: function() {
                    si4.api["saveEntityRelation"]({
                        id: null,
                        first_entity_id: rowValue.id,
                        relation_type_id: 0,
                        second_entity_id: 0,
                        staticData: { entityId: rowValue.id }
                    }, function(response) {
                        //console.log("response", response);
                        if (response.status) {
                            args.relationsDataTable.feedData(response);
                            //args.relationsDataTable.refresh();
                        }
                    });
                    //console.log("insert");
                },
                customControlls: function(dt, cpName) {
                    dt[cpName].relsToXmlDiv = new si4.widget.si4Element({parent:dt[cpName].selector, tagClass:"inline filterButton vmid"});
                    dt[cpName].relsToXmlImg = new si4.widget.si4Element({parent:dt[cpName].relsToXmlDiv.selector, tagName:"img", tagClass:"icon12 vmid"});
                    dt[cpName].relsToXmlImg.selector.attr("src", "/img/icon/apply.png");
                    dt[cpName].relsToXmlSpan = new si4.widget.si4Element({parent:dt[cpName].relsToXmlDiv.selector, tagName:"span", tagClass:"vmid"});
                    dt[cpName].relsToXmlSpan.selector.html("Save relations to XML");
                    dt[cpName].relsToXmlDiv.selector.click(function(){

                        // TODO

                        var parser = new DOMParser();
                        var xml = args.xmlTab.fieldXml.getValue();
                        var xmlDoc = parser.parseFromString(xml,"text/xml");

                        //console.log("xml", xml);
                        //console.log("xmlDoc", xmlDoc);

                        var metsStructMap;
                        if (xmlDoc.getElementsByTagName("METS:structMap").length) {
                            // <METS:structMap> exists
                            metsStructMap = xmlDoc.getElementsByTagName("METS:structMap")[0];
                            // Clear structMap children
                            for (var i = 0; i < metsStructMap.children.length; i++) {
                                metsStructMap.removeChild(metsStructMap.children[i]);
                            }
                        } else {
                            // <METS:structMap> does not exist
                            metsStructMap = xmlDoc.createElement("METS:structMap");
                            xmlDoc.appendChild(metsStructMap);
                        }

                        var parentId = null;
                        var childIds = [];
                        var entityIdsToRequest = [];

                        var structType = (si4.data.structTypes[args.basicTab.fieldStructTypeId.getValue()] || "").toLowerCase();

                        var dtRels = dt.getValue();
                        for (var relIdx = 0; relIdx < dtRels.length; relIdx++) {
                            var relId = parseInt(dtRels[relIdx].related_entity_id);
                            var relType = dtRels[relIdx].relation_type_id;
                            if (relType == "1n") {
                                // Child of
                                parentId = relId;
                                entityIdsToRequest.push(relId);
                            } else if (relType == "1r") {
                                // Parent of
                                childIds.push(relId);
                                entityIdsToRequest.push(relId);
                            }
                        }

                        metsStructMap.setAttribute("TYPE", parentId ? "dependent" : "primary");
                        metsStructMap.setAttribute("LABEL", si4.config.repositoryName);
                        metsStructMap.setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");


                        // Get related entities from API...

                        //console.log("entityIdsToRequest", entityIdsToRequest);
                        si4.api.entityList({ entityIds: entityIdsToRequest}, function(response) {
                            //console.log("response", response);

                            var parentEntity = null;
                            var childEntities = {};
                            for (var respIdx = 0; respIdx < response.data.length; respIdx++) {
                                var relEntity = response.data[respIdx];
                                if (relEntity.id == parentId)
                                    parentEntity = relEntity;
                                else
                                    childEntities[relEntity.id] = relEntity;
                            }

                            //console.log("parentEntity", parentEntity);
                            console.log("childEntities", childEntities);

                            // TODO: parent collection
                            var metsDiv1 = xmlDoc.createElement("METS:div");
                            metsDiv1.setAttribute("TYPE", "collection");
                            metsStructMap.appendChild(metsDiv1);

                            var metsMptr = xmlDoc.createElement("METS:mptr");
                            metsMptr.setAttribute("LOCTYPE", "HANDLE");
                            metsMptr.setAttribute("xlink:href", "http://hdl.handle.net/11686/menu76");
                            metsDiv1.appendChild(metsMptr);

                            var metsDiv2 = xmlDoc.createElement("METS:div");
                            metsDiv2.setAttribute("TYPE", structType);
                            metsDiv2.setAttribute("DMDID", "dc."+args.row.id+" mods."+args.row.id);
                            metsDiv2.setAttribute("ADMID", "premis."+args.row.id+" entity."+args.row.id);
                            metsDiv1.appendChild(metsDiv2);

                            // Files
                            var metsFptr = xmlDoc.createElement("METS:fptr");
                            metsFptr.setAttribute("FILEID", "thumbnail");
                            metsDiv2.appendChild(metsFptr);

                            for (var childIdx = 0; childIdx < childIds.length; childIdx++) {
                                var childId = childIds[childIdx];
                                var childEntity = childEntities[childId];
                                var metsChildDiv = xmlDoc.createElement("METS:div");
                                metsChildDiv.setAttribute("TYPE", childEntity.struct_type_name);
                                metsChildDiv.setAttribute("ORDER", childIdx);

                                var metsChildMptr = xmlDoc.createElement("METS:mptr");
                                metsChildMptr.setAttribute("LOCTYPE", "HANDLE");
                                metsChildMptr.setAttribute("xlink:href", childEntity.elasticData.objId);
                                metsChildDiv.appendChild(metsChildMptr);

                                metsDiv2.appendChild(metsChildDiv);
                            }


                            // Put xml into editor
                            var xmlText = new XMLSerializer().serializeToString(xmlDoc);
                            var xmlTextPretty = vkbeautify.xml(xmlText);
                            args.xmlTab.fieldXml.setValue(xmlTextPretty);

                            //console.log(xmlText);

                            args.xmlTab.selectTab();
                            args.xmlTab.fieldXml.codemirror.refresh();


                            window.xmlDoc = xmlDoc;
                            window.metsStructMap = metsStructMap;
                            window.dtRels = dtRels;

                        });

                        // .getElementsByTagName("METS:mdWrap")[0]
                        // .getElementsByTagName("METS:xmlData")[0];

                        // xmlDoc.getElementsByTagName("METS:metsHdr")[0]
                        // xmlDoc.getElementsByTagName("METS:dmdSec")[1]

                        /*
                        var xmlData = xmlDoc
                            .getElementsByTagName("METS:dmdSec")[1]
                            .getElementsByTagName("METS:mdWrap")[0]
                            .getElementsByTagName("METS:xmlData")[0];

                        var dcRels = xmlData.getElementsByTagName("dcterms:relation");
                        for (var i = 0; i < dcRels.length; i++) {
                            xmlData.removeChild(dcRels[i]);
                            //console.log(dcRels[i])
                        }

                        console.log(dt);
                        console.log("dt.getValue()", dt.getValue());

//                        <dcterms:relation type="isParentOf">123</dcterms:relation>
                        // TODO: Force xml path

                        var dtRels = dt.getValue();
                        for (var i in dtRels) {
                            var rel = xmlDoc.createElement("dcterms:relation");
                            var relType = si4.data.relationTypesMap[dtRels[i].relation_type_id];
                            rel.setAttribute("type", relType);
                            rel.innerHTML = "http://hdl.handle.net/11686/"+dtRels[i].related_entity_id;

                            console.log("rel", rel);

                            xmlData.appendChild(rel);
                            //xmlData.append("\n");

                            // dtRels[i].related_entity_id
                            // dtRels[i].relation_type_id
                        }
                        */

                    });
                },
                tabPage: args.relationsTab,
                fields: {
                    id: { editable: false },
                    related_entity_id: { caption: si4.translate("field_relatedEntity"), editable: true, editorType: "input" },
                    relation_type_id: {
                        caption: si4.translate("field_relationType"),
                        width: 100,
                        editable: true,
                        editorType: "select",
                        selectOptions: si4.data.relationTypesSelOpts,
                    },

                    //_delete: { width: 50 },
                    id: { visible: false },
                    created_at: { visible: false },
                    updated_at: { visible: false }
                }
            });
        });


        // *** Files Tab ***
        args.filesTab = args.createContentTab("filesTab", { canClose: false });
        args.filesTab.panel = new si4.widget.si4Panel({ parent: args.filesTab.content.selector });
        args.filesTab.panelGroup = args.filesTab.panel.addGroup("TODO: Tabela datotek in možnost dodajanja, brisanja.");
        args.filesTab.form = new si4.widget.si4Form({
            parent: args.filesTab.panelGroup.content.selector,
            captionWidth: "90px"
        });
    };


    args.saveEntity = function(){
        var basicFormValue = args.basicTab.form.getValue();
        var xmlFormValue = args.xmlTab.form.getValue();

        var formValue = Object.assign({}, basicFormValue, xmlFormValue);
        //console.log("formValue", basicFormValue);
        si4.api["saveEntity"](formValue, function(data) {
            if (data.status) {
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
        args.row.struct_type = args.caller == "collectionList" ? "collection" : "entity";
        args.row.entity_type = args.caller == "collectionList" ? "primary" : "dependant";
        args.row.indexed = true;
        args.row.enabled = true;

        si4.api["reserveEntityId"]({}, function(response) {
            args.row.id = response.data;
            args.row.xmlData = si4.entity.template.getEmptyMetsXml({ id: args.row.id });
            create();
        });
    } else {
        create();
    }

};