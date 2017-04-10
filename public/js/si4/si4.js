var si4 = { object:{}, widget:{}, data:{}, defaults:{} };

si4.defaults = {
    fadeTime: 600,
    loadingFadeTime: 200,
    hintFadeTime: 200,
    hintTriggerDelay: 200,
    autoCompleteDelay: 500,

    buttonGrad: "blue",
    submitGrad: "orange",
    tabActiveGrad: "blue",
    tabInactiveGrad: "gold",

    dataTableRowsPerPage: 10
}


si4.loadModule = function(loadArgs) {

    si4.loading.show();

    console.log("si4.loadModule", loadArgs);

    var moduleName = si4.getArg(loadArgs, "moduleName", null); // Module Name
    $.get(si4.config.modulePath+moduleName+".js", function(data) {
        eval(data);
        if (F && typeof(F) == "function") F(loadArgs);
    });

    si4.loading.hide();

    /*
    si4.loading.show();

    var moduleName = si4.getArg(loadArgs, "moduleName", null); // Module Name
    var postData = si4.getArg(loadArgs, "postData", {}); // Post data
    var tabPage = si4.getArg(loadArgs, "tabPage", null); // si4TabPage object
    var newTab = si4.getArg(loadArgs, "newTab", null); // new TabPage Name string
    var inDialog = si4.getArg(loadArgs, "inDialog", false); // Open module in new si4Dialog
    var onModuleLoad = si4.getArg(loadArgs, "onModuleLoad", function(args){}); // OnModuleLoad callback

    onModuleLoad(loadArgs);

    $.post("/loadModule", {args: {moduleName:moduleName, postData:postData}}, function(data) {
        try {
            var dataObj = JSON.parse(data);
            if (dataObj) {
                var args = si4.mergeObjects(loadArgs, dataObj.args);

                if (inDialog) {
                    var dialogTitle = "Dialog";
                    if (newTab) {
                        dialogTitle = newTab;
                        newTab = null;
                    }
                    var dialog = new si4.widget.si4Dialog({title:dialogTitle});
                    tabPage = dialog.mainTab;
                }

                // Prepare some useful functions
                args.helpers = {};

                // Create TabPage Function
                args.helpers.createTabPage = function(tabArgs){
                    var tab = (tabPage && typeof(tabPage) == "object" && tabPage.isTabPage) ? tabPage : si4.data.mainTab;
                    loadArgs.tabPage = tab;

                    if (newTab)
                        tab = new si4.widget.si4TabPage({name:newTab, parent:tab});

                    if (!tabArgs) tabArgs = {};
                    if (!tabArgs.name) tabArgs.name = 'Tab';
                    if (!tabArgs.parent) tabArgs.parent = tab == si4.data.mainTab ? tab : tab.content;
                    var childTabPage = new si4.widget.si4TabPage(tabArgs);
                    if (loadArgs.onClose && typeof(loadArgs.onClose) == "function") childTabPage.onClose(loadArgs.onClose);
                    if (loadArgs.onClosed && typeof(loadArgs.onClosed) == "function") childTabPage.onClosed(loadArgs.onClosed);
                    return childTabPage;
                };

                if (dataObj["F"] && typeof(dataObj["F"]) == "string") {
                    eval(dataObj["F"]);
                    if (F && typeof(F) == "function") F(args);
                }
            }
        }
        catch (ex) {
            alert("Error loading module "+moduleName+"\n\nMessage:\n"+ex.message);
        }

        si4.loading.hide();
    });
    */
};

si4.callMethod = function(args, f) {
    return si4.api.abstractCall(args, f);
    /*
    si4.loading.show();

    var moduleName = si4.getArg(args, "moduleName", null); // Module Name
    var methodName = si4.getArg(args, "methodName", null); // Method Name
    var aSync = si4.getArg(args, "aSync", false); // Asynchronous call

    var successF = function(result) {
        si4.loading.hide();
        if (result) {
            // Alert
            if (typeof(result['alert']) != "undefined")
                alert(result['alert']);

            if (typeof(result['sessionExpired']) != "undefined") {
                alert("Your session expired. Please login again");
                location.href = "/";
            }

            // Message
            if (typeof(f) == "function")
                f(result);
        }
    };

    var errorF = function(xhr, status, statusText) {
        si4.loading.hide();
        if (status == "parsererror") {
            // Strip tags
            statusText += xhr.responseText.replace(/(<([^>]+)>)/ig,"");
        }
        alert('moduleName: '+moduleName+'\nmethodName: '+methodName+'\n\n['+status+'] '+statusText);
    };

    var ajaxResult = $.ajax({
        type: 'POST',
        url: '/callMethod',
        data: {args:args},
        success: successF,
        error: errorF,
        dataType: "json",
        async:aSync
    });

    return ajaxResult.responseJSON;
    */
};


// Loading Animation
si4.loading = {
    isVisible: false,
    show: function(){
        //$('img#loadingGif').stop().css("display", "");
        $('img#loadingGif').stop().fadeIn(si4.defaults.loadingFadeTime);
        $('img#loadingGif2').stop().fadeIn(si4.defaults.loadingFadeTime);
        si4.loading.isVisible = true;
        si4.mouse.loadingMove();
    },
    hide: function(){
        //$('img#loadingGif').stop().css("display", "none");
        $('img#loadingGif').stop().fadeOut(si4.defaults.loadingFadeTime);
        $('img#loadingGif2').stop().fadeOut(si4.defaults.loadingFadeTime);
        si4.loading.isVisible = false;
    }
};

// Mouse Movement
si4.mouse = { x: 0, y: 0 };
$(document).mousemove(function(e) {
    si4.mouse.x = e.pageX;
    si4.mouse.y = e.pageY;
    if (si4.loading.isVisible)
        si4.mouse.loadingMove();
});

// Move loadingGif2 with cursor
si4.mouse.loadingMove = function() {
    if (!si4.mouse.loadingGif2)
        si4.mouse.loadingGif2 = $("img#loadingGif2");
    si4.mouse.loadingGif2.css("left", (si4.mouse.x+5)+"px").css("top", (si4.mouse.y+5)+"px");
};


$(document).ready(function() {
    si4.data.contentElement = $('div#pageHolder');
    si4.data.mainTab = new si4.widget.si4TabPage({
        name: "si4",
        parent: si4.data.contentElement,
        canClose: false
    });
    var primaryPage = $('#primaryPage');
    if (primaryPage)
        si4.data.mainTab.content.selector.append(primaryPage);
});

