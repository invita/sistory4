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

    var fieldId = actionsForm.addInput({name:"entity_id", value:rowValue.id, type:"text", caption:"Id", readOnly: true});
    var fieldName = actionsForm.addInput({name:"username", value:rowValue.name, type:"text", caption:"Username"});
    var fieldEmail = actionsForm.addInput({name:"email", value:rowValue.email, type:"text", caption:"Email"});

    var saveButton = actionsForm.addInput({value:"Save entity", type:"submit", caption:"Akcije"});
    saveButton.selector.click(function(){
        //si4.api.mockedEntityList({}, function() {});
    });


};