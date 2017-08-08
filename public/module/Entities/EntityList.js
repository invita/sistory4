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
            staticData : { entity_type_id: 1 },
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
            id: { caption: "Id" },
            entity_type_name: { caption: si4.translate("field_entityType"), valueTranslatePrefix:"et_" },

            //name: { caption: "Naziv" },
            //description: { caption: "Opis" },
            title: { maxCharLength: 100 },
            creator: { caption: si4.translate("field_creators"), maxCharLength: 50 },

            entity_type_id: { visible: false },
            data: { visible: false },
        },
        cssClass_table: "si4DataTable_table width100percent"
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

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
                }, 1000);
            }
        });

        /*
        console.log("change", fieldFile.getValue());
        //$.post()

        var url = "/admin/upload/show-content";

        var formData = new FormData();
        formData.append("file", fieldFile.getValue());

        console.log("post ", url, formData);

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log("callback", response);
                if (response.status)
                    fieldXml.setValue(response.data);
            }
        });
        */
    });
    args.entityImportButton = args.createContentTab("importTab", { type: "button" });
    args.entityImportButton.onActive(function(e) {
        console.log("import");
        importFile.input.selector.click();
    });


    args.entityExportButton = args.createContentTab("exportTab", { type: "button" });
    args.entityExportButton.onActive(function() {
        console.log("export");
    });
};