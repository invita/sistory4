var F = function(args){
    //console.log("EntityDetails", args);

    args.createMainTab();
    args.createContentTab();

    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";

    var panel = new si4.widget.si4Panel({parent:args.contentTab.content.selector});
    var dataPanelGroup = panel.addGroup();

    var form = new si4.widget.si4Form({parent:dataPanelGroup.content.selector, captionWidth:"90px" });

    var fieldId = form.addInput({name:"id", value:rowValue.id, type:"text", caption:si4.translate("field_id"), readOnly: true});
    var fieldFile = form.addInput({name:"file", type:"file", caption:si4.translate("field_file")});
    var fieldName = form.addInput({name:"name", value:rowValue.name, type:"text", caption:si4.translate("field_name")});
    var fieldType = form.addInput({name:"type", value:rowValue.type, type:"text", caption:si4.translate("field_type")});
    var fieldPath = form.addInput({name:"path", value:rowValue.path, type:"text", caption:si4.translate("field_path")});

    var fieldTempFileName = form.addInput({name: "tempFileName", value: "", type: "hidden"});

    fieldFile.selector.change(function() {
        //console.log("change", fieldFile.getValue());
        var url = "/admin/upload/upload-file";
        var formData = new FormData();
        formData.append("file", fieldFile.getValue());
        //console.log("post ", url, formData);
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log("callback", response);
                if (response.status) {
                    fieldName.setValue(response.data.realFileName);
                    fieldTempFileName.setValue(response.data.tempFileName);

                    var fileExt = response.data.realFileName.split(".").pop();
                    if (fileExt.toLowerCase() in ["jpg", "jpeg", "png"]) {
                        previewImg.selector.attr("src", response.data.url);
                    }
                }
            }
        });
    });

    var saveButton = form.addInput({value:si4.translate("button_save"), type:"submit", caption:si4.translate("field_actions")});
    saveButton.selector.click(function(){
        var formValue = form.getValue();
        console.log("formValue", formValue);
        si4.api["saveFile"](form.getValue(), function(data) {
            if (data.status) {
                if (confirm(si4.translate("saved_confirm_close"))) {
                    args.mainTab.destroyTab();
                }
            } else {
                si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
            }
        });
    });



    var previewPanelGroup = panel.addGroup();
    var previewImg = new si4.widget.si4Element({parent:previewPanelGroup.content.selector, tagName: "img" });
    previewImg.selector.addClass("filePreviewImg")
    if (rowValue.url)
        previewImg.selector.attr("src", rowValue.url);

};