var F = function(args){

    args.createMainTab();
    args.createContentTab();

    var name = "behaviour";
    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        entityTitleNew: si4.lookup[name].entityTitleNew,
        entityTitleEdit: si4.lookup[name].entityTitleEdit,
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["behaviourList"],
            delete: si4.api["deleteBehaviour"],
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName: "Entities/BehaviourDetails"
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
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