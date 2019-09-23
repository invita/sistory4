var F = function(args){
    args.createMainTab();
    args.createContentTab();

    //var notes = si4.widget.si4Element({parent:args.contentTab.content.selector});

    si4.api["dashboardFiles"]({}, function(response) {
        //console.log("dashboardFiles response", response);

        for (var i in response.dashboardFiles) {
            var respFile = response.dashboardFiles[i];
            /*
            //[respFile]
                filePath: ".../storage/logs/reindex-fullText-cron.log"
                name: "Cron Full-text reindex log"
                success: true
                text: "Starting entitie..."
            */

            var outputWrap = new si4.widget.si4Element({parent:args.contentTab.content.selector});
            var outputCaption = new si4.widget.si4Element({parent:outputWrap.selector});

            var outputText = new si4.widget.si4Element({parent:outputWrap.selector, tagName: "pre"});

            outputCaption.selector.css({
                fontSize: "15px",
                fontWeight: "bold",
                borderBottom: "silver solid 1px",
                padding: "5px",
                backgroundColor: "#CCC",
            }).html(respFile.name);

            outputText.selector.css({
                fontSize: "12px",
                padding: "5px",
                marginBottom: "10px",
            }).html(respFile.success ? respFile.text : respFile.error);
        }
    });
};