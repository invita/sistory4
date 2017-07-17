var F = function(args){
    //console.log("EntityDetails", args);

    var create = function() {
        var rowValue = args.row ? args.row : {};

        args.createMainTab();
        args.createContentTab();

        //console.log("entity rowValue", rowValue);

        //if (!rowValue.id) rowValue.id = "";
        //if (!rowValue.entity_type_id) rowValue.entity_type_id = "";


        var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
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
            values: si4.mergeObjects(si4.data.entityTypes, {"0":""}) });


        var fieldTitle = actionsForm.addInput({name:"title", value:rowValue.title, type:"text", caption:si4.translate("field_title"), readOnly: true});
        var fieldAuthor = actionsForm.addInput({name:"author", value:rowValue.creator, type:"text", caption:si4.translate("field_creators"), readOnly: true});
        var fieldYear = actionsForm.addInput({name:"year", value:rowValue.date, type:"text", caption:si4.translate("field_year"), readOnly: true});

        var fieldFile = actionsForm.addInput({name:"file", value:"", type:"file", caption:si4.translate("field_xml")});


        var fieldXml = actionsForm.addInput({name:"xml", value:rowValue.data, type:"codemirror", caption:false });
        fieldXml.selector.css("margin-bottom", "5px").css("margin-left", "100px");
        fieldXml.codemirror.setSize($(window).width() -350);

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
                    //moduleName:"Entities/EntityList",
                    staticData : { entityId: rowValue.id },
                    pageCount: 200
                }),
                //editorModuleArgs: {
                //    moduleName:"Entities/EntityDetails",
                //},
                canInsert: true,
                canDelete: true,
                //editable: true,
                customInsert: function() {
                    si4.api["saveEntityRelation"]({
                        id: null,
                        first_entity_id: rowValue.id,
                        relation_type_id: 1,
                        second_entity_id: rowValue.id,
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
                tabPage: args.relationsTab,
                fields: {
                    id: { editable: false },
                    first_entity_id: { caption: si4.translate("field_firstEntity"), displayType: "button" },
                    relation_type_id: {
                        caption: si4.translate("field_relationType"),
                        width: 100,
                        format: function(val) {
                            return "("+val+") " +(si4.data.relationTypes[val] || "");
                        }
                    },
                    second_entity_id: { caption: si4.translate("field_secondEntity"), displayType: "button" },

                    _delete: { width: 50 },
                    created_at: { visible: false },
                    updated_at: { visible: false }
                }
            });
        });









        /*
         var panelGroupRelations = panel.addGroup(si4.translate("panel_relations"));
         var relationsForm = new si4.widget.si4Form({parent:panelGroupRelations.content.selector, captionWidth:"90px", inputClass: "short" });

         var rels = {
         "1": "parentOf",
         "2": "childOf",
         "3": "memberOf",
         "4": "derivedFrom",
         "5": "reviewOf",
         };

         //var relParent = relationsForm.addInput({name:"parent", value:15, type:"text", caption:si4.translate("field_parent"),
         //  lookup: function(self){} });

         var relChildren = relationsForm.addInput({name:"relations", type:"text", caption: "", //caption:si4.translate("field_children"),
         isArray:true, lookup: function(self){ console.log("lookup", self); }, withCode:rels});

         relChildren.setValue([
         {codeId: 2, value: 1},
         {codeId: 1, value: 2},
         {codeId: 1, value: 3},
         {codeId: 1, value: 4},
         {codeId: 5, value: 5},
         ]);
         */
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