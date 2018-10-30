var F = function(args){
    //console.log("BehaviourDetails", args);

    args.createMainTab();
    args.createContentTab();

    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.name) rowValue.name = "";
    if (!rowValue.data) rowValue.data = "{}";

    var dataParsed = JSON.parse(rowValue.data);

    var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
    var panelGroup = panel.addGroup("Behaviour details");
    var actionsForm = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"90px" });

    var fieldId = actionsForm.addInput({name:"id", value:rowValue.id, type:"text", caption:si4.translate("field_id"), readOnly: true});
    var fieldName = actionsForm.addInput({name:"name", value:rowValue.name, type:"text", caption:si4.translate("field_name")});
    var fieldData = actionsForm.addInput({name:"data", value:rowValue.data, type:"hidden", caption:si4.translate("field_data"), readOnly: true});

    var panelGroupFrontend = panel.addGroup("Fields to display in Frontend");
    var frontendForm = new si4.widget.si4Form({parent:panelGroupFrontend.content.selector, captionWidth:"90px" });
    var fieldFrontendFields = frontendForm.addInput({name:"frontendFields", value: "",
        type:"select", caption:si4.translate("field_frontendFields"), isArray: true,
        values: si4.data.fieldDefinitionNames,
    });

    frontendForm.setValue(dataParsed);

    var saveButton = actionsForm.addInput({value:si4.translate("button_save"), type:"submit", caption:si4.translate("field_actions")});
    saveButton.selector.click(function(){
        //fieldData.setValue("foo");
        //var formValue = si4.mergeObjects(actionsForm.getValue(), frontendForm.getValue());
        var formValue = actionsForm.getValue();
        console.log("formValue", formValue);

        formValue.data = JSON.stringify(frontendForm.getValue());

        si4.api["saveBehaviour"](formValue, function(data) {
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