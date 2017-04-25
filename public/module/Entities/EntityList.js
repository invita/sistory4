var F = function(args){
    var mainTab = new si4.widget.si4TabPage({
        name: "Seznam entitet",
        parent: si4.data.mainTab,
    });

    var dataTable = new si4.widget.si4DataTable({
        parent: mainTab.content.selector,
        primaryKey: ['id'],
        entityTitle: "Entity %id% - %name%",
        //filter: { enabled: false },
        dataSource: new si4.widget.si4DataTableDataSource({
            moduleName:"Pub/PubSearch",
            staticData : { bla: "blabla" },
            pageCount: 10
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
        },
        canInsert: false,
        canDelete: false,
        tabPage: mainTab,
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