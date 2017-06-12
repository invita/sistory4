var F = function(args){
    //console.log("EntityDetails", args);

    args.createMainTab(args.entityTitle);
    args.createContentTab("Urejanje");

    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.entity_type_id) rowValue.entity_type_id = "";

    var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
    var panelGroup = panel.addGroup();
    //var panelGroup = panel.addGroup("Entity: "+JSON.stringify(args.row));

    var actionsForm = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"90px" });

    var fieldId = actionsForm.addInput({name:"id", value:rowValue.id, type:"text", caption:"Id" /*, readOnly: true */});

    /*
    var fieldEntityTypeId = actionsForm.addInput({
        name:"entity_type_id", value:rowValue.entity_type_id, type:"text", caption:"Entity Type",
        inputConstruct: si4.widget.si4MultiSelect,  values:['publication', 'menu'], multiSelect: false
    });
    */
    var fieldEntityTypeId = actionsForm.addInput({
        name:"entity_type_id", value:rowValue.entity_type_id, type:"select", caption:"Entity Type",
        values: si4.codes.entityTypeIds });


    var fieldTitle = actionsForm.addInput({name:"title", value:rowValue.title, type:"text", caption:"Title"});
    var fieldYear = actionsForm.addInput({name:"year", value:rowValue.year, type:"text", caption:"Year"});
    var fieldAuthor = actionsForm.addInput({name:"author", value:rowValue.author, type:"text", caption:"Author"});

    var fieldFile = actionsForm.addInput({name:"file", value:"", type:"file", caption:"Xml"});


    //var initialXml = "<test>\n" +
    //    "  <neki value=\"1\">Test XML</neki>\n" +
    //    "</test>\n\n\n\n\n\n\n";
    var fieldXml = actionsForm.addInput({name:"xml", value:rowValue.data, type:"codemirror", caption:false});
    fieldXml.selector.css("margin-left", "100px").css("margin-bottom", "2px");
    //fieldXml.selector.css("width", ($(window).width()*0.9)+"px");

    var entityIndexed = actionsForm.addInput({name:"indexed", value:rowValue.indexed, type:"checkbox", caption:"Indexed"});
    var entityEnabled = actionsForm.addInput({name:"enabled", value:rowValue.enabled, type:"checkbox", caption:"Enabled"});

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
        //console.log("actionsForm", actionsForm.getValueAsFormData());

        //si4.api.uploadEntity(actionsForm.getValueAsFormData(), function() {});
        //var fu = new si4.object.si4FileUploader();
        //fu.chooseFile();

        /*
        $.ajax({
            type: "POST",
            url: si4.config.uploadApis.entity,
            data: actionsForm.getValueAsFormData(),
            processData: false,
            success: function() {
                console.log("sent");
            }
        });
        */

        //si4.api.mockedEntityList({}, function() {});

        var formValue = actionsForm.getValue();
        console.log("formValue", formValue);
        si4.api.saveEntity(actionsForm.getValue(), function(data) {
            console.log("saveEntity callback", data);
        });
    });


    /*
    si4.api.getTestEntity(args.row, function(data) {
        //console.log("actionsForm", actionsForm);
        actionsForm.setValue(data);
    });
    */


};