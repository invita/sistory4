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
            select: si4.api["entityListDb"],
            delete: si4.api["deleteEntity"],
            staticData : { },
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
            caller: "collectionList"
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        showOnlyDefinedFields: true,
        fields: {
            id: { caption: si4.translate("field_id") },
            handle_id: { caption: si4.translate("field_handleId") },
            parent: { caption: si4.translate("field_parent") },
            primary: { caption: si4.translate("field_primary") },
            collection: { caption: si4.translate("field_collection") },
            struct_type: { caption: si4.translate("field_structType"), valueTranslatePrefix:"st_", canFilter: false },
            struct_subtype: { caption: si4.translate("field_structSubtype") },
            entity_type: { caption: si4.translate("field_entityType"), valueTranslatePrefix:"et_" },
        },
        cssClass_table: "si4DataTable_table width100percent"
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};