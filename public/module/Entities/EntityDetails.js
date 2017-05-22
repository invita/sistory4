var F = function(args){
    console.log("EntityDetails", args);
    var mainTab = new si4.widget.si4TabPage({
        name: args.entityTitle,
        parent: si4.data.mainTab,
    });

    var panel = new si4.widget.si4Panel({parent:mainTab.content.selector});
    var panelGroupLoading = panel.addGroup(args.row.name);
    var formLoading = new si4.widget.si4Form({parent:panelGroupLoading.content.selector, captionWidth:"90px", inputClass:"searchInput"});

    var showLoadingButton = formLoading.addInput({value:"Neka akcija", type:"submit", caption:"Akcije"});
    showLoadingButton.selector.click(function(){
        //si4.loading.show();
    });


};