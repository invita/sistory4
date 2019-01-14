var F = function(args){
    //console.log("MappingGroupFieldDetails", args);
    var rowValue = args.row ? args.row : {};

    console.log("rowValue", rowValue);

    // *** Logic ***

    args.saveMappingGroupField = function(){
        var basicFormValue = args.basicTab.form.getValue();
        console.log("formValue", basicFormValue);
        basicFormValue.variables = JSON.stringify(basicFormValue.variables);

        si4.api["saveMappingGroupField"](basicFormValue, function(data) {
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
        captionWidth: "90px"
    });

    args.basicTab.fieldId = args.basicTab.form.addInput({
        name: "id",
        value: rowValue.id,
        type: "text",
        caption: si4.translate("field_systemId"),
        readOnly: true,
    });

    args.basicTab.fieldMappingGroupId = args.basicTab.form.addInput({
        name: "mapping_group_id",
        value: args.getMappingGroup().id,
        type: "hidden",
        //readOnly: true,
    });

    args.basicTab.fieldTargetField = args.basicTab.form.addInput({
        name: "target_field",
        value: rowValue.target_field,
        type: "select",
        caption: si4.translate("field_target_field"),
        values: si4.data.fieldDefinitionNames,
    });

    args.basicTab.fieldSourceXpath = args.basicTab.form.addInput({
        name: "source_xpath",
        value: rowValue.source_xpath,
        type: "textarea",
        caption: si4.translate("field_source_xpath"),
        placeholder: si4.translate("field_source_xpath_placeholder"),
    });

    args.basicTab.fieldValueXpath = args.basicTab.form.addInput({
        name: "value_xpath",
        value: rowValue.value_xpath,
        type: "textarea",
        caption: si4.translate("field_value_xpath"),
        placeholder: si4.translate("field_value_xpath_placeholder"),
    });

    args.basicTab.fieldLangXpath = args.basicTab.form.addInput({
        name: "lang_xpath",
        value: rowValue.lang_xpath,
        type: "textarea",
        caption: si4.translate("field_lang_xpath"),
        placeholder: si4.translate("field_lang_xpath_placeholder"),
    });

    args.basicTab.form.addHr();
    args.basicTab.fieldLangXpath = args.basicTab.form.addInput({
        isArray: true,
        name: "variables",
        value: rowValue.variables,
        type: "textarea",
        secondInput: true,
        secondInputName: "name",
        caption: si4.translate("field_variables"),
        placeholder: si4.translate("field_varValue_xpath_placeholder"),
        placeholder2: si4.translate("field_varName_xpath_placeholder"),
    });
    args.basicTab.form.addHr();

    args.basicTab.saveButton = args.basicTab.form.addInput({
        caption: si4.translate("field_actions"),
        value: si4.translate("button_save"),
        type:"submit",
    });
    args.basicTab.saveButton.selector.click(args.saveMappingGroupField);
};