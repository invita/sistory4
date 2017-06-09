var F = function(args){

    args.createMainTab();
    args.createContentTab();

    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        entityTitleNew: si4.lookup.entity.entityTitleNew, // "Entity %id% - %name%",
        entityTitleEdit: si4.lookup.entity.entityTitleEdit, // "Entity %id% - %name%",
        //filter: { enabled: false },
        dataSource: new si4.widget.si4DataTableDataSource({
            moduleName:"Pub/PubSearch",
            //staticData : { bla: "blabla" },
            pageCount: 10
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
        },
        canInsert: true,
        canDelete: false,
        tabPage: args.contentTab,
        fields: {
            id: { caption: "Id" },
            name: { caption: "Naziv" },
            description: { caption: "Opis" },
//            pub_id: { caption:"Entity&nbsp;Id", hintF: function(args) { sic.hint.publication(args.row.lastRowData.pub_id); } },
        }
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};