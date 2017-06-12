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
            select: si4.api.entityList,
            //moduleName:"Entities/EntityList",
            //staticData : { bla: "blabla" },
            pageCount: 200
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