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
            id: { caption: "Id" },
            handle_id: { caption: "Handle Id", hintF: si4.entity.hintHelper.displayEntityInfoDT },
            parent: { caption: "Parent", hintF: si4.entity.hintHelper.displayEntityInfoDT },
            struct_type: { canFilter: false, caption: si4.translate("field_structType"), valueTranslatePrefix:"st_" },
            entity_type: { visible: false },
            primary: { visible: false },

            //name: { caption: "Naziv" },
            //description: { caption: "Opis" },
            title: { maxCharLength: 100 },
            creator: { caption: si4.translate("field_creator"), maxCharLength: 50 },

            active: { visible: false },
            xmlData: { visible: false },
            elasticData: { visible: false },
        },
        cssClass_table: "si4DataTable_table width100percent"
    });

    /*

    var importForm = new si4.widget.si4Form({parent:si4.data.contentElement });
    var importFile = importForm.addInput({name:"file", value:"", type:"file", accept: ".zip" });
    importFile.displayNone();
    importFile.selector.change(function() {
        console.log("change", importFile.getValue());

        var url = "/admin/upload/import";
        var formData = new FormData();
        formData.append("file", importFile.getValue());
        console.log("post ", url, formData);

        si4.loading.show();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log("callback", response);
                setTimeout(function() {
                    dataTable.refresh();
                    si4.loading.hide();
                }, 2000);
            }
        });
    });

    args.entityImportButton = args.createContentTab("importTab", { type: "button" });
    args.entityImportButton.onActive(function(e) {
        console.log("import");
        importFile.input.selector.click();
    });


    args.entityExportButton = args.createContentTab("exportTab", { type: "button" });
    args.entityExportButton.onActive(function() {
        var url = "/admin/download/export";
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

    */



    /*

    var name = "file";
    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        entityTitleNew: si4.lookup[name].entityTitleNew,
        entityTitleEdit: si4.lookup[name].entityTitleEdit,
        //filter: { enabled: false },
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["fileList"],
            delete: si4.api["deleteFile"],
            staticData : { },
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName:"Files/FileDetails",
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            //id: { caption: "Id" },
            size: { format: function(fieldVal) { return si4.friendlyFileSize(fieldVal); } },
            url: { visible: false },
            mimeType: { caption: si4.translate("field_mimeType") },
        },
        cssClass_table: "si4DataTable_table width100percent"
    });

    */

};