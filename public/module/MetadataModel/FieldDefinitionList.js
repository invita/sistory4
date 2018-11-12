var F = function(args){

    args.createMainTab();
    args.createContentTab();

    //var name = "behaviour";
    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        //entityTitleNew: si4.lookup[name].entityTitleNew,
        //entityTitleEdit: si4.lookup[name].entityTitleEdit,
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["fieldDefinitionsList"],
            delete: si4.api["deleteFieldDefinition"],
            pageCount: 20
        }),
        editorModuleArgs: {
            moduleName: "MetadataModel/FieldDefinitionDetails"
        },
        tabPage: args.contentTab,
        fields: {
            //data: { visible: false },
            translate_key: { hintF: function(args) { si4.showHint(si4.translate(args.field.getValue())); } }
        },
        cssClass_table: "si4DataTable_table width100percent"
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};