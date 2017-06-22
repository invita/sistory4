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
            caption: "TestPage",
        },
        {
            loadArgs: { moduleName:'Dev/ElasticTools' },
            caption: "ElasticTools",
        },
        {
            loadArgs: { moduleName:'System/Dashboard' },
            caption: "Sistem",
        },
        {
            loadArgs: { moduleName:'System/UserList' },
            caption: "Uporabniki",
        },
        {
            loadArgs: { moduleName:'Entities/EntityList' },
            caption: "Seznam entitet",
        },
    ];

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

    //si4.loadModule({moduleName:'Dev/TestPage' });
});

