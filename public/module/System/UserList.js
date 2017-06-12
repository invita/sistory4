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
            select: si4.api.userList,
            //moduleName:"System/UserList",
            pageCount: 200
        }),
        editorModuleArgs: {
            moduleName:"System/UserDetails",
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            id: { caption: "Id" },
            username: { caption: "Username" },
            email: { caption: "Email" },
            firstname: { caption: "Ime" },
            lastname: { caption: "Priimek" },
        }
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};