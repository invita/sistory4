var F = function(args){

    args.createMainTab();

    // Tab: Indexing Tools
    var indexingTab = args.createContentTab("indexingTab");

    var panel = new si4.widget.si4Panel({parent:indexingTab.content.selector, layoutClass:"si4PanelBlockLayout"});
    var panelGroupGlobal = panel.addGroup("Global");
    var formGlobal = new si4.widget.si4Form({parent:panelGroupGlobal.content.selector, inputClass:"searchInput"});

    var listIndicesButton = formGlobal.addInput({value:"List indices", type:"button"});
    listIndicesButton.selector.click(function(){
        si4.api.devTools({
            action: "elasticListIndices"
        }, responseToOutput);
    });
    formGlobal.addHr();

    var panelGroupPerIndex = panel.addGroup("Index");
    var formPerIndex = new si4.widget.si4Form({parent:panelGroupPerIndex.content.selector, inputClass:"searchInput"});

    var indexNameField = formPerIndex.addInput({name:"indexName", value:"entities", type:"text", caption:"IndexName"});
    var docTypeField = formPerIndex.addInput({name:"docType", value:"entity", type:"text", caption:"DocumentType"});

    var createIndexButton = formPerIndex.addInput({value:"Create index", type:"button"});
    createIndexButton.selector.click(function(){
        var indexName = indexNameField.getValue();
        var docType = docTypeField.getValue();
        si4.api.devTools({
            action: "elasticCreateIndex",
            indexName: indexName,
            docType: docType
        }, responseToOutput);
    });

    var deleteIndexButton = formPerIndex.addInput({value:"Delete index", type:"button"});
    deleteIndexButton.selector.click(function(){
        var indexName = indexNameField.getValue();
        si4.api.devTools({
            action: "elasticDeleteIndex",
            indexName: indexName
        }, responseToOutput);
    });

    var indexTestDocButton = formPerIndex.addInput({value:"Index test document", type:"button"});
    indexTestDocButton.selector.click(function(){
        var indexName = indexNameField.getValue();
        var docType = docTypeField.getValue();
        si4.api.devTools({
            action: "elasticIndexTestDoc",
            indexName: indexName,
            docType: docType,
            id: 1
        }, responseToOutput);
    });

    var searchAllButton = formPerIndex.addInput({value:"Search all", type:"button"});
    searchAllButton.selector.click(function(){
        var indexName = indexNameField.getValue();
        var docType = docTypeField.getValue();
        si4.api.devTools({
            action: "elasticSearchAll",
            indexName: indexName,
            docType: docType,
        }, responseToOutput);
    });

    formPerIndex.addHr();

    var panelGroupOutput = panel.addGroup("Output");
    var outputDiv = new si4.widget.si4Element({
        parent:panelGroupOutput.content.selector,
        style: {
            fontFamily: "Courier",
            marginLeft: 10
        }
    });

    function responseToOutput(response) {
        outputDiv.addHtml("<div>"+JSON.stringify(response)+"</div>");
    }

    // Tab: Search
    var searchTab = args.createContentTab("searchTab", { canClose: false });

    var panel2 = new si4.widget.si4Panel({parent:searchTab.content.selector, layoutClass:"si4PanelBlockLayout"});
    var panel2Group = panel2.addGroup();
    var form2 = new si4.widget.si4Form({parent:panel2Group.content.selector, inputClass:"searchInput"});

    var searchQueryField = form2.addInput({name:"query", value:"", type:"text", caption:"SearchQuery"});

    var searchButton = form2.addInput({value:"Search", type:"submit"});
    searchButton.selector.click(function(){
        dataTable.refresh();
    });

    var name = "entity";
    var dataTable = new si4.widget.si4DataTable({
        parent: searchTab.content.selector,
        filter: { enabled: false },
        dataSource: new si4.widget.si4DataTableDataSource({
            select: function(data, callback) {
                si4.api.devTools({
                    action: "elasticSearch",
                    query: searchQueryField.getValue()
                }, function(response) {
                    console.log(response);
                    var data = response.data && response.data.hits && response.data.hits.hits ? response.data.hits.hits : [];
                    for (var i in data) {
                        data[i].source = JSON.stringify(data[i]._source);
                    }
                    callback({data: data});
                });
            },
            //moduleName:"Entities/EntityList",
            //staticData : { bla: "blabla" },
            pageCount: 200
        }),
        editorModuleArgs: {
            moduleName:"Entities/EntityDetails",
        },
        canInsert: true,
        canDelete: false,
        tabPage: args.contentTab,
        fields: {}
    });


};