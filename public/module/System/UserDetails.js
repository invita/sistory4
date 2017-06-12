var F = function(args){
    //console.log("EntityDetails", args);

    args.createMainTab(args.entityTitle);
    args.createContentTab("Urejanje");

    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.entity_type_id) rowValue.entity_type_id = "";

    var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
    var panelGroup = panel.addGroup();

    var actionsForm = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"90px" });

    var fieldId = actionsForm.addInput({name:"id", value:rowValue.id, type:"text", caption:si4.translate("field_id"), readOnly: true});
    var fieldName = actionsForm.addInput({name:"name", value:rowValue.name, type:"text", caption:si4.translate("field_username")});
    var fieldEmail = actionsForm.addInput({name:"email", value:rowValue.email, type:"text", caption:si4.translate("field_email")});
    var fieldPassword = actionsForm.addInput({name:"password", value:rowValue.password, type:"password", caption:si4.translate("field_password")});
    var fieldFirstname = actionsForm.addInput({name:"firstname", value:rowValue.firstname, type:"text", caption:si4.translate("field_firstname")});
    var fieldLastname = actionsForm.addInput({name:"lastname", value:rowValue.lastname, type:"text", caption:si4.translate("field_lastname")});

    var saveButton = actionsForm.addInput({value:si4.translate("button_save"), type:"submit", caption:si4.translate("field_actions")});
    saveButton.selector.click(function(){
        //si4.api.mockedEntityList({}, function() {});
        var formValue = actionsForm.getValue();
        console.log("formValue", formValue);
        si4.api.saveUser(actionsForm.getValue(), function(data) {
            console.log("saveEntity callback", data);
        });
    });


};