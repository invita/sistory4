var F = function(args){

    var rowValue = args.row ? args.row : {};
    console.log("rowValue", rowValue);

    // *** Logic ***

    args.saveOaiGroupField = function(){
        var postData = args.basicTab.fieldsForm.getValue();
        var mapping = args.getMappingValue();
        var mappingCleaned = [];
        for (var i in mapping) {
            if (mapping[i].si4field) mappingCleaned.push(mapping[i]);
        }
        postData.mapping = JSON.stringify(mappingCleaned);
        console.log("postData", postData);

        si4.api["saveOaiGroupField"](postData, function(data) {
            if (data.status) {
                rowValue = data.data;
                args.basicTab.fieldsForm.setValue(rowValue);
                args.setMappingValue(rowValue.mapping);
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

    // Fields

    args.basicTab.mappingsContainer = new si4.widget.si4Element({ parent: args.basicTab.content.selector });
    args.basicTab.mappingsContainer.selector.css("display", "inline-table");
    args.basicTab.addContainer = new si4.widget.si4Element({ parent: args.basicTab.content.selector });
    args.basicTab.addContainer.selector.css("display", "inline-table");


    args.basicTab.panel = new si4.widget.si4Panel({ parent: args.basicTab.mappingsContainer.selector });
    args.basicTab.panelGroupFields = args.basicTab.panel.addGroup("Field details");
    args.basicTab.fieldsForm = new si4.widget.si4Form({
        parent: args.basicTab.panelGroupFields.content.selector,
        captionWidth: "120px"
    });

    args.basicTab.fieldId = args.basicTab.fieldsForm.addInput({
        name: "id",
        value: rowValue.id,
        caption: si4.translate("field_systemId"),
        type: "hidden",
        //readOnly: true,
    });
    args.basicTab.fieldGroupId = args.basicTab.fieldsForm.addInput({
        name: "oai_group_id",
        value: rowValue.oai_group_id,
        caption: si4.translate("field_systemId"),
        type: "hidden",
        //readOnly: true,
    });

    args.basicTab.fieldFieldName = args.basicTab.fieldsForm.addInput({
        name: "name",
        value: rowValue.name,
        type: "text",
        caption: si4.translate("field_field_name"),
    });

    args.basicTab.fieldHasLanguage = args.basicTab.fieldsForm.addInput({
        name: "has_language",
        value: rowValue.has_language,
        type: "checkbox",
        caption: si4.translate("field_has_language"),
    });

    args.basicTab.fieldXmlPath = args.basicTab.fieldsForm.addInput({
        name: "xml_path",
        value: rowValue.xml_path,
        type: "text",
        caption: si4.translate("field_xml_path"),
    });

    args.basicTab.fieldXmlName = args.basicTab.fieldsForm.addInput({
        name: "xml_name",
        value: rowValue.xml_name,
        type: "text",
        caption: si4.translate("field_xml_name"),
    });


    args.basicTab.saveButton = args.basicTab.fieldsForm.addInput({
        caption: si4.translate("field_actions"),
        value: si4.translate("button_save"),
        type:"submit",
    });
    args.basicTab.saveButton.selector.click(args.saveOaiGroupField);


    // Mapping

    args.mappingForms = [];
    args.newMapping = function() {
        var panelGroup = args.basicTab.panel.addGroup("Mapping");
        var form = new si4.widget.si4Form({
            parent: panelGroup.content.selector,
            captionWidth: "100px"
        });

        var si4fieldField = form.addInput({
            name: "si4field",
            //value: rowValue.mapping,
            type: "select",
            values: si4.data.fieldDefinitionNames,
            addEmptyOption: true,
        });

        var valueMappingField = form.addInput({
            name: "xml_value",
            //value: rowValue.mapping,
            type: "text",
            placeholder: "Element value",
            caption: "Xml Value (?)",
        });
        valueMappingField.captionDiv.setHint(
            "Each Si4field can have variables defined through mapping in <b>Mapping Definitions -> Group -> Field</b>.<br/>\n" +
            "Variable named <b>'value'</b> is always available, and <b>'lang'</b> when selected field supports language.<br/>\n" +
            "You can also define and use custom variables.<br/>\n" +
            "Type variable name for selected Si4field to use for this OAI <b>xml element's value</b>.<br/>");

        var attrMappingField = form.addInput({
            name: "xml_attributes",
            //value: rowValue.mapping,
            type: "text",
            secondInput: true,
            secondInputName: "name",
            halfWidth: true,
            isArray: true,
            placeholder: "Attr value",
            placeholder2: "Attr name",
            caption: "Xml Attributes (?)",
        });
        attrMappingField.inputs[Object.keys(attrMappingField.inputs)[0]].captionDiv.setHint(
            "Variable named <b>'value'</b> is always available, and <b>'lang'</b> when selected field supports language.<br/>\n" +
            "You can also define and use custom variables.<br/>\n" +
            "In the left input type <b>xml element's attribute name</b>.<br/>\n" +
            "In the right input type variable name to serve as source for the <b>attribute value</b>.<br/>" +
            "Multiple xml attributes can be defined by adding rows to this input.");

        var result = {
            panelGroup: panelGroup,
            form: form,
        };
        args.mappingForms.push(result);
        return result;
    };

    args.getMappingValue = function() {
        var result = [];
        for (var i in args.mappingForms) {
            result.push(args.mappingForms[i].form.getValue());
        }
        return result;
    };

    args.setMappingValue = function(value) {
        if (!$.isArray(value)) return;
        for (var i in args.mappingForms) {
            args.mappingForms[i].panelGroup.selector.remove();
        }
        args.mappingForms = [];
        for (var i in value) {
            args.newMapping().form.setValue(value[i]);
        }
    };

    args.basicTab.addMappingButton = new si4.widget.si4Element({ parent: args.basicTab.addContainer.selector,
        tagName: "input", tagClass: "si4Input gradGray", attr: { type: "button", value: " + " } });
    args.basicTab.addMappingButton.selector.css("margin-top", "38px").css("margin-left", "10px").css("height", "46px");
    args.basicTab.addMappingButton.selector.click(function() {
        args.newMapping();
    });

    args.setMappingValue(rowValue.mapping);
    if (!args.mappingForms.length) args.newMapping();

};