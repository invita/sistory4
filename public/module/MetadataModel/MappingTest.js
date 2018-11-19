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