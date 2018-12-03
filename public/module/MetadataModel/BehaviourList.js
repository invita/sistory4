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
            moduleName: "MetadataModel/BehaviourDetails"
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            id: { },
            name: { },
            description: { },
        },
        cssClass_table: "si4DataTable_table width100percent",
        showOnlyDefinedFields: true
    });
};