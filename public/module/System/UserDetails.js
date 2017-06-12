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

    var fieldId = actionsForm.addInput({name:"id", value:rowValue.id, type:"text", caption:"Id", readOnly: true});
    var fieldName = actionsForm.addInput({name:"name", value:rowValue.name, type:"text", caption:"Username"});
    var fieldEmail = actionsForm.addInput({name:"email", value:rowValue.email, type:"text", caption:"Email"});
    var fieldPassword = actionsForm.addInput({name:"password", value:rowValue.password, type:"password", caption:"Password"});
    var fieldFirstname = actionsForm.addInput({name:"firstname", value:rowValue.firstname, type:"text", caption:"Ime"});
    var fieldLastname = actionsForm.addInput({name:"lastname", value:rowValue.lastname, type:"text", caption:"Priimek"});

    var saveButton = actionsForm.addInput({value:"Save entity", type:"submit", caption:"Akcije"});
    saveButton.selector.click(function(){
        //si4.api.mockedEntityList({}, function() {});
        var formValue = actionsForm.getValue();
        console.log("formValue", formValue);
        si4.api.saveUser(actionsForm.getValue(), function(data) {
            console.log("saveEntity callback", data);
        });
    });


};