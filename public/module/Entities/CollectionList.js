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
            staticData : { entity_type_id: 2 },
            pageCount: 200
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
            caller: "collectionList"
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

};