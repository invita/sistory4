var F = function(args){
    //console.log("EntityDetails", args);

    args.createMainTab();
    args.createContentTab();

    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.entity_type_id) rowValue.entity_type_id = "";

    var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
    var panelGroup = panel.addGroup();
    //var panelGroup = panel.addGroup("Entity: "+JSON.stringify(args.row));

    var actionsForm = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"90px" });

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
    var fieldYear = actionsForm.addInput({name:"year", value:rowValue.year, type:"text", caption:si4.translate("field_year"), readOnly: true});
    var fieldAuthor = actionsForm.addInput({name:"author", value:rowValue.author, type:"text", caption:si4.translate("field_author"), readOnly: true});

    var fieldFile = actionsForm.addInput({name:"file", value:"", type:"file", caption:si4.translate("field_xml")});


    var fieldXml = actionsForm.addInput({name:"xml", value:rowValue.data, type:"codemirror", caption:false});
    fieldXml.selector.css("margin-left", "100px").css("margin-bottom", "2px");

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
};