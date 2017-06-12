var F = function(args){

    args.createMainTab();
    args.createContentTab();

    var name = "user";
    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        entityTitleNew: si4.lookup[name].entityTitleNew,
        entityTitleEdit: si4.lookup[name].entityTitleEdit,
        dataSource: new si4.widget.si4DataTableDataSource({
            moduleName:"System/UserList",
            //staticData : { bla: "blabla" },
            pageCount: 10
        }),
        editorModuleArgs: {
            moduleName:"System/UserDetails",
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            id: { caption: "Id" },
            name: { caption: "Naziv" },
            email: { caption: "Email" },
        }
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};