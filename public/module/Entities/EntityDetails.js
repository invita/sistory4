var F = function(args){
    //console.log("EntityDetails", args);

    var create = function() {
        var rowValue = args.row ? args.row : {};

        args.createMainTab();
        args.dataTab = args.createContentTab();

        console.log("entity args", args);

        //if (!rowValue.id) rowValue.id = "";
        //if (!rowValue.entity_type_id) rowValue.entity_type_id = "";


        var panel = new si4.widget.si4Panel({parent:args.dataTab.content.selector});
        var panelGroupData = panel.addGroup(si4.translate("panel_entityData"));
        //var panelGroup = panel.addGroup("Entity: "+JSON.stringify(args.row));

        var actionsForm = new si4.widget.si4Form({parent:panelGroupData.content.selector, captionWidth:"90px" });

        var fieldId = actionsForm.addInput({name:"id", value:rowValue.id, type:"text", caption:si4.translate("field_id"), readOnly: true});

        /*
         var fieldEntityTypeId = actionsForm.addInput({
         name:"entity_type_id", value:rowValue.entity_type_id, type:"text", caption:"Entity Type",
         inputConstruct: si4.widget.si4MultiSelect,  values:['publication', 'menu'], multiSelect: false
         });
         */

        var fieldEntityTypeId = actionsForm.addInput({
            name:"entity_type_id", value:rowValue.entity_type_id, type:"select", caption:si4.translate("field_entityType"),
            values: si4.data.entityTypes, value: args.caller == "collectionList" ? 2 : 1 });

        var fieldTitle = actionsForm.addInput({name:"title", value:rowValue.title, type:"text", caption:si4.translate("field_title"), readOnly: true});
        var fieldAuthor = actionsForm.addInput({name:"author", value:rowValue.creator, type:"text", caption:si4.translate("field_creators"), readOnly: true});
        var fieldYear = actionsForm.addInput({name:"year", value:rowValue.date, type:"text", caption:si4.translate("field_year"), readOnly: true});

        var fieldFile = actionsForm.addInput({name:"file", value:"", type:"file", caption:si4.translate("field_xml")});
        fieldFile.selector.change(function() {
            console.log("change", fieldFile.getValue());
            //$.post()

            var url = "/admin/upload/show-content";
            var formData = new FormData();
            formData.append("file", fieldFile.getValue());

            console.log("post ", url, formData);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    console.log("callback", response);
                    if (response.status)
                        fieldXml.setValue(response.data);
                }
            });
        });


        var fieldXml = actionsForm.addInput({name:"xml", value:rowValue.xmlData, type:"codemirror", caption:false });
        fieldXml.selector.css("margin-bottom", "5px").css("margin-left", "100px");
        fieldXml.codemirror.setSize($(window).width() -110);

        var entityIndexed = actionsForm.addInput({name:"indexed", value:rowValue.indexed, type:"checkbox", caption:si4.translate("field_indexed")});
        var entityEnabled = actionsForm.addInput({name:"enabled", value:rowValue.enabled, type:"checkbox", caption:si4.translate("field_enabled")});

        var saveButton = actionsForm.addInput({value:si4.translate("button_save"), type:"submit", caption:si4.translate("field_actions")});
        saveButton.selector.click(function(){

            var formValue = actionsForm.getValue();
            console.log("formValue", formValue);
            si4.api["saveEntity"](actionsForm.getValue(), function(data) {
                if (data.status) {
                    if (confirm(si4.translate("saved_confirm_close"))) {
                        args.mainTab.destroyTab();
                    }
                } else {
                    si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
                }
                //console.log("saveEntity callback", data);
            });
        });


        // Relations Tab
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
                        var xml = fieldXml.getValue();
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

                        var entityType = (si4.data.entityTypes[fieldEntityTypeId.getValue()] || "").toLowerCase();

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
                            metsDiv2.setAttribute("TYPE", entityType);
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
                                metsChildDiv.setAttribute("TYPE", childEntity.entity_type_name);
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
                            fieldXml.setValue(xmlTextPretty);

                            //console.log(xmlText);

                            args.dataTab.selectTab();
                            fieldXml.codemirror.refresh();


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
    };


    if (!args.row || !args.row.id) {
        args.row = {};
        args.row.entity_type_id = "0";
        args.row.indexed = true;
        args.row.enabled = true;

        si4.api["reserveEntityId"]({}, function(response) {
            args.row.id = response.data;
            args.row.data = si4.entity.template.getEmptyMetsXml({ id: args.row.id });
            create();
        });
    } else {
        create();
    }

};