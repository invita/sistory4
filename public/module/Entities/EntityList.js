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
            //moduleName:"Entities/EntityList",
            //staticData : { bla: "blabla" },
            pageCount: 200
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            id: { caption: "Id" },
            entity_type_name: { caption: si4.translate("field_entityType") },

            //name: { caption: "Naziv" },
            //description: { caption: "Opis" },
            title: { maxCharLength: 100 },
            creator: { caption: si4.translate("field_creators"), maxCharLength: 50 },

            entity_type_id: { visible: false },
            data: { visible: false },
        }
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};