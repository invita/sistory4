var F = function(args){

    args.createMainTab();
    args.createContentTab();

    var name = "entity";
    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        entityTitleNew: si4.lookup[name].entityTitleNew,
        entityTitleEdit: si4.lookup[name].entityTitleEdit,
        //filter: { enabled: false },
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["entityList"],
            delete: si4.api["deleteEntity"],
            //staticData : { struct_type: "entity" },
            filter: { struct_type: "entity" },
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
            caller: "entityList"
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            id: { caption: si4.translate("field_id") },
            handle_id: { caption: si4.translate("field_handleId"), hintF: si4.entity.hintHelper.displayEntityInfoDT },
            parent: { caption: si4.translate("field_parent"), hintF: si4.entity.hintHelper.displayEntityInfoDT },
            primary: { caption: si4.translate("field_primary"), hintF: si4.entity.hintHelper.displayEntityInfoDT },
            collection: { caption: si4.translate("field_collection"), hintF: si4.entity.hintHelper.displayEntityInfoDT },
            struct_type: { caption: si4.translate("field_structType"), valueTranslatePrefix:"st_", canFilter: false },
            struct_subtype: { caption: si4.translate("field_structSubtype") },
            entity_type: { caption: si4.translate("field_entityType"), valueTranslatePrefix:"et_" },
            title: { caption: si4.translate("field_title"), maxCharLength: 80 },
            creator: { caption: si4.translate("field_creator"), maxCharLength: 50 },
            date: { caption: si4.translate("field_date") },
        },
        showOnlyDefinedFields: true,
        cssClass_table: "si4DataTable_table width100percent"
    });


    // Import
    var importForm = new si4.widget.si4Form({parent:si4.data.contentElement });
    var importFile = importForm.addInput({name:"file", value:"", type:"file", accept: ".zip" });
    importFile.displayNone();
    importFile.selector.change(function() {
        console.log("importFile input change", importFile.getValue());
        var formData = new FormData();
        formData.append("file", importFile.getValue());
        si4.entity.entityImport(formData, dataTable);
    });

    args.entityImportButton = args.createContentTab("importTab", { type: "button" });
    args.entityImportButton.onActive(function(e) {
        console.log("import", e);
        importFile.input.selector.click();
    });


    // Export Mets
    args.entityExportMetsButton = args.createContentTab("exportMetsTab", { type: "button" });
    args.entityExportMetsButton.onActive(function() {
        var url = "/admin/download/exportMets";
        var postData = dataTable.dataSource.getMethodCallData(dataTable.dataSource.methodNames.select);

        var exportForm = document.createElement("form");
        exportForm.action = url;
        exportForm.method = "POST";

        var dataInput = document.createElement("input");
        dataInput.name = "data";
        dataInput.type = "hidden";
        dataInput.value = JSON.stringify(postData);
        exportForm.appendChild(dataInput);

        si4.data.contentElement.append(exportForm);

        exportForm.submit();
    });

    // Export Csv
    args.entityExportCsvButton = args.createContentTab("exportCsvTab", { type: "button" });
    args.entityExportCsvButton.onActive(function() {
        var url = "/admin/download/exportCsv";
        var postData = dataTable.dataSource.getMethodCallData(dataTable.dataSource.methodNames.select);

        var exportForm = document.createElement("form");
        exportForm.action = url;
        exportForm.method = "POST";

        var dataInput = document.createElement("input");
        dataInput.name = "data";
        dataInput.type = "hidden";
        dataInput.value = JSON.stringify(postData);
        exportForm.appendChild(dataInput);

        si4.data.contentElement.append(exportForm);

        exportForm.submit();
    });
};