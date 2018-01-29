var F = function(args){

    args.createMainTab();
    args.createContentTab();

    var name = "file";
    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        entityTitleNew: si4.lookup[name].entityTitleNew,
        entityTitleEdit: si4.lookup[name].entityTitleEdit,
        //filter: { enabled: false },
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["fileList"],
            delete: si4.api["deleteFile"],
            staticData : { },
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName:"Files/FileDetails",
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            //id: { caption: "Id" },
            size: { format: function(fieldVal) { return si4.friendlyFileSize(fieldVal); } },
            url: { visible: false },
            mimeType: { caption: si4.translate("field_mimeType") },
        },
        cssClass_table: "si4DataTable_table width100percent"
    });

};