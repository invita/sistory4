var F = function(args){
    //console.log("FieldDefinitionDetails", args);

    args.createMainTab();
    args.createContentTab();

    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.field_name) rowValue.field_name = "";
    if (!rowValue.translate_key) rowValue.translate_key = "";
    if (!rowValue.has_language) rowValue.has_language = false;
    if (!rowValue.show_all_languages) rowValue.show_all_languages = false;
    if (!rowValue.inline) rowValue.inline = false;
    if (!rowValue.inline_separator) rowValue.inline_separator = "";
    if (!rowValue.display_frontend) rowValue.display_frontend = false;

    var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
    var panelGroup = panel.addGroup("Si4 Field details");
    var actionsForm = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"140px" });

    var fieldId = actionsForm.addInput({name:"id", value:rowValue.id, type:"text", caption:si4.translate("field_id"), readOnly: true});
    var fieldFieldName = actionsForm.addInput({name:"field_name", value:rowValue.field_name, type:"text", caption:si4.translate("field_name")});
    var fieldTranslateKey = actionsForm.addInput({name:"translate_key", value:rowValue.translate_key, type:"text", caption:si4.translate("field_translate_key")});
    var fieldHasLanguage = actionsForm.addInput({name:"has_language", value:rowValue.has_language, type:"checkbox", caption:si4.translate("field_has_language")});
    var fieldShowAllLanguages = actionsForm.addInput({name:"show_all_languages", value:rowValue.show_all_languages, type:"checkbox", caption:si4.translate("field_show_all_languages")});
    var fieldInline = actionsForm.addInput({name:"inline", value:rowValue.inline, type:"checkbox", caption:si4.translate("field_inline")});
    var fieldInlineSeparator = actionsForm.addInput({name:"inline_separator", value:rowValue.inline_separator, type:"text", caption:si4.translate("field_inline_separator")});
    var fieldDisplayFrontend = actionsForm.addInput({name:"display_frontend", value:rowValue.display_frontend, type:"checkbox", caption:si4.translate("field_display_frontend")});
    var fieldEnableAdvSearch = actionsForm.addInput({name:"enable_adv_search", value:rowValue.enable_adv_search, type:"checkbox", caption:si4.translate("field_enable_adv_search")});

    var saveButton = actionsForm.addInput({value:si4.translate("button_save"), type:"submit", caption:si4.translate("field_actions")});
    saveButton.selector.click(function(){
        var formValue = actionsForm.getValue();
        console.log("formValue", formValue);

        si4.api["saveFieldDefinition"](formValue, function(data) {
            if (data.status) {
                if (confirm(si4.translate("saved_confirm_close"))) {
                    args.mainTab.destroyTab();
                }
            } else {
                si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
            }
        });
    });

};