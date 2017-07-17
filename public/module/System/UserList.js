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
            delete: si4.api.deleteUser,
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
            id: { caption: si4.translate("field_id") },
            name: { caption: si4.translate("field_username") },
            email: { caption: si4.translate("field_email") },
            firstname: { caption: si4.translate("field_firstname") },
            lastname: { caption: si4.translate("field_lastname") },
        }
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};