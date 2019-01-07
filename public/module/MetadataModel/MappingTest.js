var F = function(args){

    var create = function() {
        args.createMainTab();

        // Xml to Si4 test
        args.metsToSi4Tab = args.createContentTab();
        args.metsToSi4Tab.panel = new si4.widget.si4Panel({ parent: args.metsToSi4Tab.content.selector });
        args.metsToSi4Tab.panelGroup1 = args.metsToSi4Tab.panel.addGroup("Input Mets Xml");
        args.metsToSi4Tab.form = new si4.widget.si4Form({
            parent: args.metsToSi4Tab.panelGroup1.content.selector,
            captionWidth: "90px"
        });

        // -- Xml editor (codemirror)
        args.metsToSi4Tab.fieldXml = args.metsToSi4Tab.form.addInput({
            name: "xml",
            value: args.xmlData,
            type: "codemirror",
            caption: false
        });
        args.metsToSi4Tab.fieldXml.selector.css("margin-bottom", "5px");
        args.metsToSi4Tab.fieldXml.codemirror.setSize($(window).width() -20);
        args.metsToSi4Tab.onActive(function(tabArgs) {
            args.metsToSi4Tab.fieldXml.codemirror.refresh();
        });

        args.metsToSi4Tab.saveButton = args.metsToSi4Tab.form.addInput({
            //caption: si4.translate("field_actions"),
            value: si4.translate("button_check"),
            type:"submit",
            tagClass: "fixedBottomRight biggerSubmit"
        });
        args.metsToSi4Tab.saveButton.selector.click(function() {
            var xmlFormValue = args.metsToSi4Tab.form.getValue();
            si4.api["xmlToSi4Test"](xmlFormValue, function(data) {
                if (data.status) {
                    args.resultTab.output.selector.html(JSON.stringify(data.data, null, 2));
                    args.resultTab.selectTab();
                } else {
                    si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
                }
            }, function(err) {
                // errorCallback
                alert(si4.translate("operation_failed", { reason: "["+err.status+"] "+err.statusText }));
            });

        });


        // -- Result tab
        args.resultTab = args.createContentTab("resultTab", { canClose: false });
        args.resultTab.output = new si4.widget.si4Element({ parent: args.resultTab.content.selector, tagName: "pre" });
        args.resultTab.output.selector.html("Check an XML first").css({
            padding: "10px",
        });

        args.resultTab.form = new si4.widget.si4Form({ parent: args.resultTab.content.selector, });
        args.resultTab.goBackButton = args.resultTab.form.addInput({
            //caption: si4.translate("field_actions"),
            value: "Back",
            type:"submit",
            tagClass: "fixedBottomRight biggerSubmit"
        });
        args.resultTab.goBackButton.selector.click(function() {
            args.metsToSi4Tab.selectTab();
        });




        // -- XPath evaluate test tab
        args.xpathEvalTab = args.createContentTab("xpathEvalTab", { canClose: false });

        args.xpathEvalTab.form = new si4.widget.si4Form({ parent: args.xpathEvalTab.content.selector, captionWidth:"60px" });
        args.xpathEvalTab.fieldXpath = args.xpathEvalTab.form.addInput({
            name: "xpath",
            value: "",
            type: "codemirror",
            caption: si4.translate("field_test_xpath"),
        });
        args.xpathEvalTab.fieldXpath.selector.css("margin-bottom", "5px");
        args.xpathEvalTab.fieldXpath.codemirror.setSize($(window).width() -20);
        args.xpathEvalTab.onActive(function(tabArgs) {
            args.xpathEvalTab.fieldXpath.codemirror.refresh();
        });


        args.xpathEvalTab.goButton = args.xpathEvalTab.form.addInput({
            caption: si4.translate("field_actions"),
            value: "Evaluate",
            type:"submit",
        });
        args.xpathEvalTab.goButton.input.selector.click(function() {
            var xml = args.metsToSi4Tab.form.getValue().xml;
            var xpath = args.xpathEvalTab.form.getValue().xpath;
            si4.api["xmlXPathTest"]({ xml: xml, xpath: xpath }, function(data) {
                if (data.status) {
                    args.xpathEvalTab.output.selector.html(JSON.stringify(data.data, null, 2));
                } else {
                    si4.error.show(si4.translate(si4.error.ERR_API_STATUS_FALSE), si4.error.ERR_API_STATUS_FALSE, data);
                }
            }, function(err) {
                // errorCallback
                alert(si4.translate("operation_failed", { reason: "["+err.status+"] "+err.statusText }));
            });
        });

        args.xpathEvalTab.test1link = new si4.widget.si4Element({ parent: args.xpathEvalTab.goButton.selector, tagName: "a" });
        args.xpathEvalTab.test1link.selector.html("Test string METS:metsHdr").css("cursor", "pointer").css("margin-left", "10px");
        args.xpathEvalTab.test1link.selector.click(function() { args.xpathEvalTab.form.setValue({ xpath: "string(/METS:mets/METS:metsHdr/@RECORDSTATUS)" }); args.xpathEvalTab.fieldXpath.codemirror.refresh(); });

        args.xpathEvalTab.test2link = new si4.widget.si4Element({ parent: args.xpathEvalTab.goButton.selector, tagName: "a" });
        args.xpathEvalTab.test2link.selector.html("Test count dc:titles").css("cursor", "pointer").css("margin-left", "10px");
        args.xpathEvalTab.test2link.selector.click(function() { args.xpathEvalTab.form.setValue({ xpath: "count(/METS:mets/METS:dmdSec/METS:mdWrap/METS:xmlData/dc:title)" }); args.xpathEvalTab.fieldXpath.codemirror.refresh(); });

        args.xpathEvalTab.test3link = new si4.widget.si4Element({ parent: args.xpathEvalTab.goButton.selector, tagName: "a" });
        args.xpathEvalTab.test3link.selector.html("Mods test1").css("cursor", "pointer").css("margin-left", "10px");
        args.xpathEvalTab.test3link.selector.click(function() { args.xpathEvalTab.form.setValue({ xpath: "string(/METS:mets/METS:dmdSec/METS:mdWrap/METS:xmlData/mods:mods/mods:titleInfo[not(@type='alternative' or @type='translated')]/mods:title)" }); args.xpathEvalTab.fieldXpath.codemirror.refresh(); });

        args.xpathEvalTab.test4link = new si4.widget.si4Element({ parent: args.xpathEvalTab.goButton.selector, tagName: "a" });
        args.xpathEvalTab.test4link.selector.html("Mods test2").css("cursor", "pointer").css("margin-left", "10px");
        args.xpathEvalTab.test4link.selector.click(function() { args.xpathEvalTab.form.setValue({ xpath: "string(/METS:mets/METS:dmdSec/METS:mdWrap/METS:xmlData/mods:mods/mods:titleInfo[not(@type='alternative' or @type='translated')]/mods:subTitle)" }); args.xpathEvalTab.fieldXpath.codemirror.refresh(); });

        args.xpathEvalTab.output = new si4.widget.si4Element({ parent: args.xpathEvalTab.content.selector, tagName: "pre" });
        args.xpathEvalTab.output.selector.html("").css({
            padding: "10px",
        });

    };

    si4.entity.template.getEmptyMetsXml({
        template: "template.test.xml",
        id: 123,
        handleId: "test123",
        structType: "entity",
    }, function(xmlTemplate) {
        args.xmlData = xmlTemplate;
        create();
    });

};