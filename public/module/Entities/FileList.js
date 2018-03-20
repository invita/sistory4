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
            //staticData : { struct_type: "file" },
            filter: { struct_type: "file" },
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
            caller: "fileList"
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            id: { caption: si4.translate("field_id") },
            handle_id: { caption: si4.translate("field_handleId"), hintF: si4.entity.hintHelper.displayEntityInfoDT },
            parent: { caption: si4.translate("field_entity"), hintF: si4.entity.hintHelper.displayEntityInfoDT },
            struct_type: { canFilter: false, caption: si4.translate("field_structType"), valueTranslatePrefix:"st_" },
        },
        showOnlyDefinedFields: true,
        cssClass_table: "si4DataTable_table width100percent"
    });


    // Import
    var importForm = new si4.widget.si4Form({parent:si4.data.contentElement });
    var importFile = importForm.addInput({name:"file", value:"", type:"file", accept: ".zip" });
    importFile.displayNone();
    importFile.selector.change(function() {
        console.log("change", importFile.getValue());

        var importCheckUrl = "/admin/upload/import-check";
        var importUrl = "/admin/upload/import";

        var formData = new FormData();
        formData.append("file", importFile.getValue());
        console.log("post ", importCheckUrl, formData);

        si4.loading.show();
        $.ajax({
            type: 'POST',
            url: importCheckUrl,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log("import-check callback", response);
                si4.loading.hide();

                if (response.status) {
                    if (confirm(si4.translate("text_confirm_import_entities", response.data))) {
                        si4.loading.show();
                        var importData = { uploadedFile: response.pathName };

                        console.log("post ", importUrl, importData);
                        $.ajax({
                            type: 'POST',
                            url: importUrl,
                            data: JSON.stringify(importData),
                            success: function(response){
                                console.log("import callback", response);

                                if (response.status) {
                                    setTimeout(function() {
                                        dataTable.refresh();
                                        si4.loading.hide();
                                    }, 2000);

                                } else {
                                    alert(response.error);
                                }
                            }
                        });
                    }

                } else {
                    alert(response.error);
                }
            }
        });

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