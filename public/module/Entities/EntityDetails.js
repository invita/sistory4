var F = function(args){
    console.log("EntityDetails", args);
    var mainTab = new si4.widget.si4TabPage({
        name: args.entityTitle,
        parent: si4.data.mainTab,
    });

    var panel = new si4.widget.si4Panel({parent:mainTab.content.selector});
    var panelGroup = panel.addGroup("Entity: "+JSON.stringify(args.row));

    var actionsForm = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"90px", inputClass:"searchInput"});

    var fieldId = actionsForm.addInput({name:"entity_id", value:""+args.row.id, type:"text", caption:"Id"});
    var fieldEntityTypeId = actionsForm.addInput({name:"entity_type_id", value:""+args.row.entity_type_id, type:"text", caption:"Entity Type Id"});
    var fieldName = actionsForm.addInput({name:"name", value:args.row.name, type:"text", caption:"Name"});
    var fieldDesc = actionsForm.addInput({name:"description", value:args.row.description, type:"text", caption:"Description"});

    var fieldFile = actionsForm.addInput({name:"file", value:"", type:"file", caption:"File"});


    var saveButton = actionsForm.addInput({value:"Save entity", type:"submit", caption:"Akcije"});
    saveButton.selector.click(function(){
        //si4.loading.show();

        /*
        $.post(si4.config.apis.entityList, JSON.stringify(args), function(data) {
            console.log("post callback", data);
            if (typeof(callback) == "function") callback(data);
        });
        */
        /*
         $.post(si4.config.uploadApis.entity, actionsForm.getValueAsFormData(), function() {
         // callback
         console.log("sent");
         });
        */
        console.log("actionsForm", actionsForm.getValueAsFormData());

        $.ajax({
            type: "POST",
            url: si4.config.uploadApis.entity,
            data: actionsForm.getValueAsFormData(),
            contentType: false,
            processData: false,
            success: function() {
                console.log("sent");
            }
        });

    });


};