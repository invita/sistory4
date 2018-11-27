var F = function(args){
    args.createMainTab();
    args.createContentTab();
    args.contentTab.content.selector.css("position", "relative");

    args.languages = {
        slv: "SLV",
        eng: "ENG"
    };

    args.form = new si4.widget.si4Form({
        parent: args.contentTab.content.selector,
        captionWidth: "90px"
    });

    args.form.fieldLanguage = args.form.addInput({
        name: "language",
        value: "slv",
        type: "select",
        caption: si4.translate("field_language"),
        values: args.languages,
    });

    args.form.fieldLanguage.selector.change(function(e) {
        var val = $(e.target).val();
        dataTable.dataSource.staticData.language = val;
        dataTable.refresh(true);
    });


    var dataTable = new si4.widget.si4DataTable({
        parent: args.contentTab.content.selector,
        primaryKey: ['id'],
        dataSource: new si4.widget.si4DataTableDataSource({
            select: si4.api["translationsList"],
            delete: si4.api["deleteTranslation"],
            pageCount: 50,
            staticData: {
                language: "slv",
                module: "fe"
            }
        }),
        editorModuleArgs: {
            moduleName:"System/TranslationDetails"
        },
        canInsert: true,
        canDelete: true,
        tabPage: args.contentTab,
        fields: {
            key: { caption: si4.translate("field_key") },
            value: { caption: si4.translate("field_value") },
        },
        showOnlyDefinedFields: true,
        cssClass_table: "si4DataTable_table width100percent"
    });


    args.form2 = new si4.widget.si4Form({
        parent: args.contentTab.content.selector,
        captionWidth: "90px"
    });
    args.generateButton = args.form2.addInput({
        value:si4.translate("button_generate_fe_translation_files"),
        type:"button",
        caption: null,
        tagClass: "absoluteTopRight"
    });
    args.generateButton.selector.click(function() {
        si4.api["generateTranslationFiles"]({}, function(data) {
            console.log("generation resp", data);
        });
    });

    //console.log(dataTable);
    //dataTable.dataSource.staticData.foo = "bar";

    //dataTable.refresh(true);
};