var F = function(args){
    //console.log("EntityDetails", args);

    args.createMainTab();
    args.createContentTab();

    var rowValue = args.row ? args.row : {};
    //console.log("entity rowValue", rowValue);

    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.entity_type_id) rowValue.entity_type_id = "";

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
        values: si4.data.entityTypes });


    var fieldTitle = actionsForm.addInput({name:"title", value:rowValue.title, type:"text", caption:si4.translate("field_title"), readOnly: true});
    var fieldAuthor = actionsForm.addInput({name:"author", value:rowValue.creator, type:"text", caption:si4.translate("field_creators"), readOnly: true});
    var fieldYear = actionsForm.addInput({name:"year", value:rowValue.year, type:"text", caption:si4.translate("field_year"), readOnly: true});

    var fieldFile = actionsForm.addInput({name:"file", value:"", type:"file", caption:si4.translate("field_xml")});


    var fieldXml = actionsForm.addInput({name:"xml", value:rowValue.data, type:"codemirror", caption:false });
    fieldXml.selector.css("margin-left", "100px").css("margin-bottom", "2px");
    fieldXml.codemirror.setSize($(window).width() -350);

    var entityIndexed = actionsForm.addInput({name:"indexed", value:rowValue.indexed, type:"checkbox", caption:si4.translate("field_indexed")});
    var entityEnabled = actionsForm.addInput({name:"enabled", value:rowValue.enabled, type:"checkbox", caption:si4.translate("field_enabled")});

    var saveButton = actionsForm.addInput({value:si4.translate("button_save"), type:"submit", caption:si4.translate("field_actions")});
    saveButton.selector.click(function(){

        var formValue = actionsForm.getValue();
        console.log("formValue", formValue);
        si4.api.saveEntity(actionsForm.getValue(), function(data) {
            if (data.status) {
                if (confirm(si4.translate("saved_confirm_close"))) {
                    args.mainTab.destroyTab();
                }
            } else {
                si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE)
            }
            //console.log("saveEntity callback", data);
        });
    });

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
};