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
        value: args.getOaiGroup().id,
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

        var valuesMappingField = form.addInput({
            name: "xml_values",
            //value: rowValue.mapping,
            type: "text",
            isArray: true,
            placeholder: "Element value",
            caption: "Xml Values (?)",
            secondInput: true,
            secondInputName: "path",
            placeholder2: "Element name",
            //halfWidth: true,
        });
        valuesMappingField.inputs[Object.keys(valuesMappingField.inputs)[0]].captionDiv.setHint(
            "Each Si4field can have variables defined through mapping in <b>Mapping Definitions -> Group -> Field</b>.<br/>\n" +
            "Variable named <b>'value'</b> is always available, and <b>'lang'</b> when selected field supports language.<br/>\n" +
            "In the Mapping Definitions you can also define custom variables to use them here.<br/>\n" +
            "<b>In the left input</b> type OAI Xml subpath (i.e. something/@type to access value for attribute 'type' of element 'something')<br/>\n" +
            "<b>In the right input</b> type variable name for selected Si4field to use for this OAI <b>Xml subpath value</b>. Use quotes for literal value (i.e. \"info\")<br/>");

        /*
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
            "<b>In the left</b> input type <b>xml element's attribute name</b>.<br/>\n" +
            "<b>In the right</b> input type variable name to serve as source for the <b>attribute value</b>. Use quotes for literal value (i.e. \"info\")<br/>" +
            "Multiple xml attributes can be defined by adding rows to this input.");
        */

        var exampleHint = new si4.widget.si4Element({ parent: form.selector, hint: "..." });
        exampleHint.selector.addClass("exampleHint").html("View example (?)");
        exampleHint.showHint = function() {
            si4.showHint(args.mappingHint(form.getValue()));
        };

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



    // *** Mapping hint
    args.mappingHint = function(mappingFormValue) {
        var fieldFormValue = args.basicTab.fieldsForm.getValue();
        var NL = "<br/>\n";
        var tab = "&nbsp;&nbsp;&nbsp;&nbsp;";
        var hint = "";
        var el = function(name, attrs) { if (!attrs) attrs = ""; else attrs = " "+attrs; return "&lt;"+name+attrs+"&gt;"; };
        var tabs = function(c) { var result = ""; for (var i = 0; i < c; i++) result += tab; return result; };
        var val = function(val) { if (!val) val = ""; if (val.startsWith("\"")) return val.replace(/"/g, ""); else return "<i>"+mappingFormValue.si4field+"."+val+"</i>"; }

        var xml_path_components =  fieldFormValue.xml_path.trim() ? fieldFormValue.xml_path.trim().split("/") : [];
        var xpcl = xml_path_components.length;

        var xml_name =  fieldFormValue.xml_name.trim();

        var render = function(testEl, depth) {
            var result = "";
            if (testEl.children.length) {
                result += tabs(depth)+el(testEl.name, testEl.attrs.join(" "))+NL;
                for (var cIdx in testEl.children)
                    result += render(testEl.children[cIdx], depth+1);
                result += tabs(depth)+el("/"+testEl.name)+NL;
            } else {
                result += tabs(depth)+el(testEl.name, testEl.attrs.join(" "))+ val(testEl.value)+ el("/"+testEl.name)+NL;
            }
            return result;
        };

        hint += el("oai_resource")+NL;
        hint += tab+"..."+NL;
        hint += "<b>";
        for (var pcIdx = 0; pcIdx < xml_path_components.length; pcIdx++) {
            hint += tabs(pcIdx+1)+el(xml_path_components[pcIdx])+NL;
        }


        var testEls = [];
        for (var i = 0; i < 1; i++) {
            var testEl = {
                name: fieldFormValue.xml_name,
                attrs: [],
                children: [],
                value: ""
            };

            if (fieldFormValue.has_language) testEl.attrs.push("xml:lang=&quot;"+val("lang")+"&quot;");

            for (var j in mappingFormValue.xml_values) {
                var xmlPath = mappingFormValue.xml_values[j].path;
                var xmlValue = mappingFormValue.xml_values[j].value;
                var curEl = testEl;

                if (xmlPath) {
                    var pathComps = xmlPath.split("/");
                    for (var k in pathComps) {
                        if (pathComps[k].startsWith("@")) {
                            curEl.attrs.push(pathComps[k].replace("@", "")+"=\""+val(xmlValue)+"\"");
                        } else {
                            var existing = null;
                            for (var xi in curEl.children) {
                                if (curEl.children[xi].name == pathComps[k]) {
                                    existing = curEl.children[xi];
                                    break;
                                }
                            }

                            //console.log("existing", pathComps[k], existing, curEl);
                            if (existing) {
                                curEl = existing;
                            } else {
                                var child = {
                                    name: pathComps[k],
                                    attrs: [],
                                    children: [],
                                    value: xmlValue
                                };
                                curEl.children.push(child);
                                curEl = child;
                            }
                        }
                    }
                } else {
                    curEl.value = xmlValue;
                }
            }

            console.log(testEl);
            hint += render(testEl, xpcl +1);

            /*
            var attrs = "";
            if (fieldFormValue.has_language) attrs += " xml:lang=&quot;"+val("lang")+"&quot;";
            for (var j in mappingFormValue.xml_attributes) {
                if (mappingFormValue.xml_attributes[j].name && mappingFormValue.xml_attributes[j].value)
                    attrs += " "+mappingFormValue.xml_attributes[j].name+"=&quot;"+val(mappingFormValue.xml_attributes[j].value)+"&quot;";
            }

            hint += tabs(xpcl+1)+el(xml_name, attrs)+NL;


            if (!mappingFormValue.xml_values[0].name) {
                // Simple
                hint += tabs(xpcl+2) +val(mappingFormValue.xml_values[0].value) +NL;
            } else {
                // Multiple value elements
                for (var k in mappingFormValue.xml_values) {
                    hint += tabs(xpcl+2) +
                    el(mappingFormValue.xml_values[k].name) +
                    val(mappingFormValue.xml_values[k].value)+
                    el("/"+mappingFormValue.xml_values[k].name) +NL;
                }
            }

            hint += tabs(xpcl+1)+el("/"+xml_name)+NL;
             */
        }

        for (var pcIdx = xml_path_components.length - 1; pcIdx >= 0; pcIdx--) {
            hint += tabs(pcIdx + 1) + el("/" + xml_path_components[pcIdx]) + NL;
        }


        //hint += tab+el("test")+"bla"+el("/test")+NL;
        hint += "</b>";
        hint += tab+"..."+NL;
        hint += el("/oai_resource")+NL;
        return hint;
    };
    // *** End of Mapping hint






    args.setMappingValue(rowValue.mapping);
    if (!args.mappingForms.length) args.newMapping();

};