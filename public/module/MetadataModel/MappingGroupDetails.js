var F = function(args){
    //console.log("MappingGroupDetails", args);
    var rowValue = args.row ? args.row : {};
    //console.log("rowValue", rowValue);

    // *** Logic ***

    args.saveMappingGroup = function(){
        var basicFormValue = args.basicTab.form.getValue();
        console.log("formValue", basicFormValue);

        si4.api["saveMappingGroup"](basicFormValue, function(data) {
            if (data.status) {
                rowValue = data.data;
                args.basicTab.form.setValue(rowValue);
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

    args.basicTab.panel = new si4.widget.si4Panel({ parent:args.basicTab.content.selector });
    args.basicTab.panelGroup = args.basicTab.panel.addGroup();
    args.basicTab.form = new si4.widget.si4Form({
        parent: args.basicTab.panelGroup.content.selector,
        captionWidth: "90px"
    });

    args.basicTab.fieldId = args.basicTab.form.addInput({
        name: "id",
        value: rowValue.id,
        type: "text",
        caption: si4.translate("field_systemId"),
        readOnly: true,
    });

    args.basicTab.fieldParent = args.basicTab.form.addInput({
        name: "name",
        value: rowValue.name,
        type: "text",
        caption: si4.translate("field_name"),
    });

    args.basicTab.fieldParent = args.basicTab.form.addInput({
        name: "description",
        value: rowValue.description,
        type: "textarea",
        caption: si4.translate("field_description"),
    });

    args.basicTab.saveButton = args.basicTab.form.addInput({
        caption: si4.translate("field_actions"),
        value: si4.translate("button_save"),
        type:"submit",
    });
    args.basicTab.saveButton.selector.click(args.saveMappingGroup);


    // Field mappings Tab

    args.fieldMappingsTab = args.createContentTab("fieldMappingsTab", {canClose: false, autoActive: !!rowValue.id});
    args.fieldMappingsTab.panel = new si4.widget.si4Panel({parent: args.fieldMappingsTab.content.selector});

    args.fieldMappingsTab.dataTable = new si4.widget.si4DataTable({
        parent: args.fieldMappingsTab.content.selector,
        primaryKey: ['id'],
        //entityTitleNew: si4.lookup["entity"].entityTitleNew,
        //entityTitleEdit: si4.lookup["entity"].entityTitleEdit,
        //filter: { enabled: false },
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["mappingGroupFieldsList"],
            /*
             delete: si4.api["deleteEntity"],
             filter: { struct_type: "file", parent: rowValue.handle_id },
             */
            staticData: function() { return { mapping_group_id: rowValue.id } },
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName:"MetadataModel/MappingGroupFieldDetails",
            getMappingGroup: function() { return rowValue; },
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.fieldMappingsTab,
        fieldOrder: [],
        fields: {
            id: { visible: false },
            mapping_group_id: { visible: false },
            target_field: { },
            source_xpath: { },
            value_xpath: { },
            lang_xpath: { },
            data: { visible: false },
            /*
            id: { caption: "Id" },
            handle_id: { caption: "Handle Id", hintF: si4.entity.hintHelper.displayEntityInfoDT },
            parent: { caption: "Parent", hintF: si4.entity.hintHelper.displayEntityInfoDT },
            struct_type: { canFilter: false, caption: si4.translate("field_structType"), valueTranslatePrefix:"st_" },
            fileName: { maxCharLength: 50 },
            //title: { maxCharLength: 100 },
            //creator: { caption: si4.translate("field_creator"), maxCharLength: 50 },
            */
        },
        fieldOrder: "definedFields",
        //showOnlyDefinedFields: true,
        cssClass_table: "si4DataTable_table width100percent"
    });

};