var F = function(args){
    var create = function() {

        args.createMainTab();

        // *** Basic Tab ***

        args.basicTab = args.createContentTab();

        args.basicTab.panel = new si4.widget.si4Panel({parent: args.basicTab.content.selector});
        args.basicTab.panelGroup = args.basicTab.panel.addGroup("OAI Group details");
        args.basicTab.form = new si4.widget.si4Form({parent: args.basicTab.panelGroup.content.selector, captionWidth: "90px"});

        var fieldId = args.basicTab.form.addInput({
            name: "id",
            value: rowValue.id,
            type: "text",
            caption: si4.translate("field_id"),
            readOnly: true
        });
        var fieldName = args.basicTab.form.addInput({
            name: "name",
            value: rowValue.name,
            type: "text",
            caption: si4.translate("field_name"),
            //readOnly: !!rowValue.id
        });
        var fieldSchema = args.basicTab.form.addInput({
            name: "schema",
            value: rowValue.schema,
            type: "text",
            caption: si4.translate("field_schema"),
        });
        var fieldNamespace = args.basicTab.form.addInput({
            name: "namespace",
            value: rowValue.namespace,
            type: "text",
            caption: si4.translate("field_namespace"),
        });
        var fieldBehaviours = args.basicTab.form.addInput({
            name: "behaviours",
            value: rowValue.behaviours,
            type: "select",
            isArray: true,
            values: si4.data.behaviourNames,
            caption: si4.translate("field_behaviours"),
        });
        var fieldAttrs = args.basicTab.form.addInput({
            name: "attrs",
            value: rowValue.attrs,
            type: "text",
            isArray: true,
            caption: si4.translate("field_attributes")+" (?)",
            secondInput: true,
            secondInputName: "key",
            placeholder2: "key",
            placeholder: "value",
            //halfWidth: true,
        });
        fieldAttrs.inputs[Object.keys(fieldAttrs.inputs)[0]].captionDiv.setHint("XML attributes for OAI base resource element.");

        args.basicTab.saveButton = args.basicTab.form.addInput({
            value: si4.translate("button_save"),
            type: "submit",
            caption: si4.translate("field_actions")
        });
        args.basicTab.saveButton.selector.click(args.saveOaiGroup);


        // *** Fields Tab ***

        console.log("rowValue", rowValue);
        args.fieldsTab = args.createContentTab("fieldsTab", { canClose: false, autoActive: !!rowValue.id });
        args.fieldsTab.panel = new si4.widget.si4Panel({parent: args.fieldsTab.content.selector});
        args.fieldsTab.dataTable = new si4.widget.si4DataTable({
            parent: args.fieldsTab.content.selector,
            primaryKey: ['id'],
            //entityTitleNew: si4.lookup["entity"].entityTitleNew,
            //entityTitleEdit: si4.lookup["entity"].entityTitleEdit,
            //filter: { enabled: false },
            dataSource: new si4.widget.si4DataTableDataSource({
                select: si4.api["oaiGroupFieldsList"],
                delete: si4.api["deleteOaiGroupField"],
                filter: { oai_group_id: rowValue.id },
                staticData: { oai_group_id: rowValue.id },
                pageCount: 20
            }),
            editorModuleArgs: {
                moduleName:"Oai/OaiFieldDetails",
                getOaiGroup: function() { return rowValue; },
            },
            canInsert: true,
            canDelete: true,
            tabPage: args.contentTab,
            fields: {
                name: {  },
                xml_name: {  },
                has_language: {  },
                /*
                behaviour_name: {  },
                field_name: {  },
                show_all_languages: {  },
                inline: {  },
                inline_separator: {  },
                display_frontend: {  },
                */
            },
            showOnlyDefinedFields: true,
            cssClass_table: "si4DataTable_table width100percent"
        });
    };

    args.saveOaiGroup = function(){

        var basicFormValue = args.basicTab.form.getValue();
        basicFormValue.behaviours = JSON.stringify(basicFormValue.behaviours);
        basicFormValue.attrs = JSON.stringify(basicFormValue.attrs);

        si4.api["saveOaiGroup"](basicFormValue, function (data) {
            if (data.status) {
                if (confirm(si4.translate("saved_confirm_close"))) {
                    args.mainTab.destroyTab();
                }
            } else {
                si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
            }
        });
    };


    // Init
    var rowValue = args.row ? args.row : {};
    if (!rowValue.id) rowValue.id = "";
    if (!rowValue.name) rowValue.name = "";
    console.log("rowValue", rowValue);

    create();

};