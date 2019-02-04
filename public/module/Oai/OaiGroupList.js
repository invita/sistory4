var F = function(args){
    args.createMainTab();
    args.createContentTab("contentTab");

    var name = "oaiGroup";
    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        //entityTitleNew: si4.lookup[name].entityTitleNew,
        //entityTitleEdit: si4.lookup[name].entityTitleEdit,
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["oaiGroupList"],
            delete: si4.api["deleteOaiGroup"],
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName: "Oai/OaiGroupDetails"
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            id: { },
            name: { caption: si4.translate("field_name_metadataPrefix") },
            //description: { },
        },
        cssClass_table: "si4DataTable_table width100percent",
        showOnlyDefinedFields: true
    });
};