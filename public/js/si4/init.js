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

    var navContainer = $("#navContainer");
    var menuItems = [
        {
            loadArgs: { moduleName:'Dev/TestPage' },
            caption: si4.translate("dev_testPage_mainTab_text"),
        },
        {
            loadArgs: { moduleName:'Dev/ElasticTools' },
            caption: si4.translate("dev_elasticTools_mainTab_text"),
        },
        {
            loadArgs: { moduleName:'System/Dashboard' },
            caption: si4.translate("system_dashboard_mainTab_text"),
        },
        {
            loadArgs: { moduleName:'System/UserList' },
            caption: si4.translate("system_userList_mainTab_text"),
        },
        {
            loadArgs: { moduleName:'Entities/EntityList' },
            caption: si4.translate("entities_entityList_mainTab_text"),
        },
    ];

    si4.loading.show();

    si4.api.initialData(null, function(response){

        if (response.status) {
            //response.entityTypes.map(function(e))
            var entityTypes = {};
            for (var etIdx in response.entityTypes) {
                var et = response.entityTypes[etIdx];
                var etTranslateKey = "et_"+et.name.replace(/\s/g, "");
                entityTypes[et.id] = si4.translate(etTranslateKey);
            }
            si4.data.entityTypes = entityTypes;
        }

        // Append menus
        for (var i in menuItems) {
            var menuItem = menuItems[i];

            var li = document.createElement("li");
            li.className = "mainMenuList";

            var a = document.createElement("a");
            a.innerHTML =  menuItem.caption;
            a.href = "javascript:si4.loadModule("+JSON.stringify(menuItem.loadArgs)+");";

            li.appendChild(a);
            navContainer.append(li);
        }

        si4.loading.hide();
    });

    //si4.loadModule({moduleName:'Dev/TestPage' });
});

