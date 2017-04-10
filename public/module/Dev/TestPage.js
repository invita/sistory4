var F = function(args){
    var mainTab = new si4.widget.si4TabPage({
        name: "Test",
        parent: si4.data.mainTab,
        contentText: "Test Page Module"
    });

    /*
    si4.api.abstractCall({some:"data"}, function(data){
        console.log("callback2", data);
    });
    */


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
        //editorModuleArgs: {
        //    moduleName:"Pub/PubEdit",
        //    tabPage:mainTab
        //},
        canInsert: false,
        canDelete: false,
        tabPage: mainTab,
        fields: {
//            pub_id: { caption:"Entity&nbsp;Id", hintF: function(args) { sic.hint.publication(args.row.lastRowData.pub_id); } },
        }
    });

    dataTable.onDataFeedComplete(function(args){
        //dataTable.dataSource.staticData = args["staticData"];
        //console.log("onDataFeedComplete", args);
    });

    //dataTable.refresh(true);

};