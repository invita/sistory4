var F = function(args){
    var mainTab = new si4.widget.si4TabPage({
        name: "Sistem",
        parent: si4.data.mainTab
    });

    //si4.loading.show();

    var panel = new si4.widget.si4Panel({parent:mainTab.content.selector});
    var panelGroup = panel.addGroup("Test");

    var form = new si4.widget.si4Form({parent:panelGroup.content.selector, captionWidth:"90px", inputClass:"searchInput"});
    var showLoadingButton = form.addInput({value:"Show loading", type:"submit", caption:" "});
    showLoadingButton.selector.click(function(){
        si4.loading.show();
    });

    var hideLoadingButton = form.addInput({value:"Hide loading", type:"button"});
    hideLoadingButton.selector.click(function(){
        si4.loading.hide();
    });

    form.addHr();







};