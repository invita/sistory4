var F = function(args){
    var mainTab = new si4.widget.si4TabPage({
        name: "Test",
        parent: si4.data.mainTab,
    });

    var panel = new si4.widget.si4Panel({parent:mainTab.content.selector});
    var panelGroupLoading = panel.addGroup("Tests");
    var formLoading = new si4.widget.si4Form({parent:panelGroupLoading.content.selector, captionWidth:"90px", inputClass:"searchInput"});

    var showLoadingButton = formLoading.addInput({value:"Show loading", type:"submit", caption:"Akcije"});
    showLoadingButton.selector.click(function(){
        si4.loading.show();
    });

    var hideLoadingButton = formLoading.addInput({value:"Hide loading", type:"button"});
    hideLoadingButton.selector.click(function(){
        si4.loading.hide();
    });

    formLoading.addHr();

    var jsonTestButton = formLoading.addInput({value:"Send", type:"button", caption:"Json Test"});
    jsonTestButton.selector.click(function(){
        si4.api.getEntityList({
        });
    });



    var panelGroup2 = panel.addGroup("Test group 2");
    var form2 = new si4.widget.si4Form({parent:panelGroup2.content.selector, captionWidth:"90px", inputClass:"searchInput"});

    var button3 = form2.addInput({value:"Test3", type:"button", caption:"Test3"});
    button3.selector.click(function(){
    });


};