var F = function(args){
    //console.log("BehaviourDetails", args);

    var templateNames = ["entity", "collection", "file"];

    var create = function() {

        args.createMainTab();

        // *** Basic Tab ***

        args.basicTab = args.createContentTab();

        args.basicTab.panel = new si4.widget.si4Panel({parent: args.basicTab.content.selector});
        args.basicTab.panelGroup = args.basicTab.panel.addGroup("Behaviour details");
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
            readOnly: !!rowValue.id
        });
        var fieldDescription = args.basicTab.form.addInput({
            name: "description",
            value: rowValue.description,
            type: "textarea",
            caption: si4.translate("field_description"),
        });

        args.basicTab.saveButton = args.basicTab.form.addInput({
            value: si4.translate("button_save"),
            type: "submit",
            caption: si4.translate("field_actions")
        });
        args.basicTab.saveButton.selector.click(args.saveBehaviour);


        // *** Fields Tab ***

        args.fieldsTab = args.createContentTab("fieldsTab", { canClose: false });

        args.fieldsTab.panel = new si4.widget.si4Panel({parent: args.fieldsTab.content.selector});

        args.fieldsTab.dataTable = new si4.widget.si4DataTable({
            parent: args.fieldsTab.content.selector,
            primaryKey: ['id'],
            //entityTitleNew: si4.lookup["entity"].entityTitleNew,
            //entityTitleEdit: si4.lookup["entity"].entityTitleEdit,
            //filter: { enabled: false },
            dataSource: new si4.widget.si4DataTableDataSource({
                select: si4.api["behaviourFieldList"],
                delete: si4.api["deleteBehaviourField"],
                filter: { behaviour_name: rowValue.name },
                staticData: { behaviour_name: rowValue.name },
                pageCount: 20
            }),
            editorModuleArgs: {
                moduleName:"MetadataModel/BehaviourFieldDetails",
                getBehaviour: function() { return rowValue; },
            },
            canInsert: true,
            canDelete: true,
            tabPage: args.filesTab,
            fields: {
                behaviour_name: {  },
                field_name: {  },
                show_all_languages: {  },
                inline: {  },
                inline_separator: {  },
                display_frontend: {  },
            },
            showOnlyDefinedFields: true,
            cssClass_table: "si4DataTable_table width100percent"
        });


        // *** Adv. Search Tab ***

        args.advSearchTab = args.createContentTab("advSearchTab", { canClose: false });

        // TODO: This Code is not to clean...
        var recreateAdvSearchForm = function(values) {
            args.advSearchTab.content.selector.empty();
            args.advSearchTab.form = new si4.widget.si4Form({parent: args.advSearchTab.content.selector, captionWidth: "90px"});
            var fieldAdvSearchFields = args.advSearchTab.form.addInput({
                name: "advanced_search",
                value: "",
                type: "select",
                caption: si4.translate("field_frontendFields"),
                isArray: true,
                values: values,
            });

            if (rowValue.advanced_search)
                args.advSearchTab.form.setValue({ advanced_search: JSON.parse(rowValue.advanced_search) });

            // AddAll button
            args.advSearchTab.setAllButton = args.advSearchTab.form.addInput({
                value: si4.translate("button_addAll"),
                type: "button",
                caption: si4.translate("field_actions")
            });
            args.advSearchTab.setAllButton.selector.click(function() {
                args.advSearchTab.form.setValue({ advanced_search: Object.keys(values) });
            });

            // Save button
            args.advSearchTab.saveButton = args.advSearchTab.form.addInput({
                value: si4.translate("button_save"),
                type: "submit",
                caption: null
            });
            args.advSearchTab.saveButton.selector.click(args.saveBehaviour);

        };
        recreateAdvSearchForm(si4.data.fieldDefinitionNames);

        args.advSearchTab.onActive(function(tabArgs) {
            // Load BehaviourFields...
            si4.loading.show();
            var behaviourFieldListReq = {
                staticData: { behaviour_name: rowValue.name },
                pageCount: 1000
            };
            si4.api["behaviourFieldList"](behaviourFieldListReq, function (data) {
                var values = {};
                if (data.status && data.rowCount) {
                    for (var i in data.data) {
                        values[data.data[i].field_name] = data.data[i].field_name;
                    }
                    recreateAdvSearchForm(values);
                }
                si4.loading.hide();
            }, function() {
                alert("Error loading behaviour fields");
                si4.loading.hide();
            });
        });



        // *** Template Tabs ***

        args.templateTab = {};
        templateNames.forEach(function(templateName, idx) {
            args.templateTab[templateName] = args.createContentTab("templateTab_"+templateName, { canClose: false });
            args.templateTab[templateName].panel = new si4.widget.si4Panel({ parent: args.templateTab[templateName].content.selector });
            args.templateTab[templateName].panelGroup = args.templateTab[templateName].panel.addGroup();
            args.templateTab[templateName].form = new si4.widget.si4Form({
                parent: args.templateTab[templateName].panelGroup.content.selector,
                captionWidth: "90px"
            });

            // -- Xml editor (codemirror)
            args.templateTab[templateName].fieldTemplate = args.templateTab[templateName].form.addInput({
                name: "template",
                value: rowValue["template_"+templateName],
                type: "codemirror",
                caption: false
            });
            args.templateTab[templateName].fieldTemplate.selector.css("margin-bottom", "5px");
            args.templateTab[templateName].fieldTemplate.codemirror.setSize($(window).width() -20);
            args.templateTab[templateName].onActive(function(tabArgs) {
                args.templateTab[templateName].fieldTemplate.codemirror.refresh();
            });

            args.templateTab[templateName].saveButton = args.templateTab[templateName].form.addInput({
                value: si4.translate("button_save"),
                type:"submit",
                tagClass: "fixedBottomRight biggerSubmit"
            });
            args.templateTab[templateName].saveButton.selector.click(args.saveBehaviour);

        });

    };

    args.saveBehaviour = function(){

        var basicFormValue = args.basicTab.form.getValue();
        var advSearchFormValue = args.advSearchTab.form.getValue();
        advSearchFormValue.advanced_search = JSON.stringify(advSearchFormValue.advanced_search);

        var templatesFormValue = {}
        templateNames.forEach(function(templateName, idx) {
            templatesFormValue["template_"+templateName] = args.templateTab[templateName].form.getValue().template;
        });

        var behaviourData = Object.assign({}, basicFormValue, advSearchFormValue, templatesFormValue);
        console.log("behaviourData", behaviourData);

        si4.api["saveBehaviour"](behaviourData, function (data) {
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
    if (!rowValue.description) rowValue.description = "";

    create();

};