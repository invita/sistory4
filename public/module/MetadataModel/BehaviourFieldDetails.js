var F = function(args){

    var rowValue = args.row ? args.row : {};

    // *** Logic ***

    args.saveBehaviourField = function(){
        var basicFormValue = args.basicTab.form.getValue();
        console.log("formValue", basicFormValue);

        si4.api["saveBehaviourField"](basicFormValue, function(data) {
            if (data.status) {
                rowValue = data.data;
                args.basicTab.form.setValue(rowValue);
                if (confirm(si4.translate("saved_confirm_close"))) {
                    args.mainTab.destroyTab();
                }
            } else {
                si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
            }
        }, function(err) {
            // errorCallback
            alert(si4.translate("save_failed", { reason: "["+err.status+"] "+err.statusText }));
        });
    };

    // *** Layout ***

    // Basic Tab
    args.createMainTab();

    args.basicTab = args.createContentTab();

    args.basicTab.panel = new si4.widget.si4Panel({ parent:args.basicTab.content.selector });
    args.basicTab.panelGroup = args.basicTab.panel.addGroup();
    args.basicTab.form = new si4.widget.si4Form({
        parent: args.basicTab.panelGroup.content.selector,
        captionWidth: "120px"
    });

    args.basicTab.fieldId = args.basicTab.form.addInput({
        name: "id",
        value: rowValue.id,
        caption: si4.translate("field_systemId"),
        type: "hidden",
        //readOnly: true,
    });

    args.basicTab.fieldBehaviourName = args.basicTab.form.addInput({
        name: "behaviour_name",
        value: args.getBehaviour().name,
        type: "hidden",
        //readOnly: true,
    });

    args.basicTab.fieldFieldName = args.basicTab.form.addInput({
        name: "field_name",
        value: rowValue.field_name,
        type: "select",
        caption: si4.translate("field_target_field"),
        values: si4.data.fieldDefinitionNames,
    });

    args.basicTab.fieldShowAllLangs = args.basicTab.form.addInput({
        name: "show_all_languages",
        value: rowValue.show_all_languages,
        type: "checkbox",
        caption: si4.translate("field_show_all_languages"),
    });

    args.basicTab.fieldInline = args.basicTab.form.addInput({
        name: "inline",
        value: rowValue.inline,
        type: "checkbox",
        caption: si4.translate("field_inline"),
    });

    args.basicTab.fieldInlineSep = args.basicTab.form.addInput({
        name: "inline_separator",
        value: rowValue.inline_separator,
        type: "text",
        caption: si4.translate("field_inline_separator"),
    });

    args.basicTab.fieldDisplayFE = args.basicTab.form.addInput({
        name: "display_frontend",
        value: rowValue.display_frontend,
        type: "checkbox",
        caption: si4.translate("field_display_frontend"),
    });

    args.basicTab.saveButton = args.basicTab.form.addInput({
        caption: si4.translate("field_actions"),
        value: si4.translate("button_save"),
        type:"submit",
    });
    args.basicTab.saveButton.selector.click(args.saveBehaviourField);
};