var F = function(args){
    console.log("TranslationDetails", args);

    args.createMainTab();
    args.createContentTab();

    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.key) rowValue.key = "";
    if (!rowValue.language) rowValue.language = "";
    if (!rowValue.module) rowValue.module = "";
    if (!rowValue.value) rowValue.value = "";

    var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
    var panelGroup = panel.addGroup();

    var actionsForm = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"90px" });

    var fieldId = actionsForm.addInput({name:"id", value:rowValue.id, type:"hidden"});
    var fieldModule = actionsForm.addInput({name:"module", value:rowValue.module, type:"hidden"});
    var fieldLanguage = actionsForm.addInput({name:"language", value:rowValue.language, type:"hidden"});

    var fieldKey = actionsForm.addInput({name:"key", value:rowValue.key, type:"text", caption:si4.translate("field_key"), readOnly: rowValue.id ? true : false});
    var fieldName = actionsForm.addInput({name:"value", value:rowValue.value, type:"text", caption:si4.translate("field_value")});

    var saveButton = actionsForm.addInput({value:si4.translate("button_save"), type:"submit", caption:si4.translate("field_actions")});
    saveButton.selector.click(function(){
        //si4.api.mockedEntityList({}, function() {});
        var formValue = actionsForm.getValue();
        console.log("formValue", formValue);
        si4.api["saveTranslation"](actionsForm.getValue(), function(data) {
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