var foo = {};
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

    si4.loading.show();

    si4.api["initialData"](null, function(response){

        if (response.status) {

            // structTypes
            var structTypes = {};
            for (var stIdx in response.structTypes) {
                var st = response.structTypes[stIdx];
                structTypes[st] = si4.translate("st_"+st);
            }
            si4.data.structTypes = structTypes;

            // entityTypes
            var entityTypes = {};
            for (var etIdx in response.entityTypes) {
                var et = response.entityTypes[etIdx];
                entityTypes[et] = si4.translate("et_"+et);
            }
            si4.data.entityTypes = entityTypes;

            // dcLanguages
            /*
            var dcLanguages = {};
            for (var dcLangIdx in response.dcLanguages) {
                var dcLang = response.dcLanguages[dcLangIdx];
                dcLanguages[dcLang] = dcLang;
            }
            si4.entity.mdHelper.dcLangCodes = dcLanguages;
            */
            //si4.data.dcLanguages = dcLanguages;

            var relationTypes = {};
            var relationTypesSelOpts = {};
            var relationTypesMap = {};
            for (var relIdx in response.relationTypes) {
                var rel = response.relationTypes[relIdx];
                var relTranslateKey = "rel_"+rel.name.replace(/\s/g, "");
                var relRevTranslateKey = "rel_"+rel.name_rev.replace(/\s/g, "");
                relationTypes[rel.id] = {
                    name: si4.translate(relTranslateKey),
                    name_rev: si4.translate(relRevTranslateKey)
                };
                relationTypesSelOpts[rel.id+"n"] = si4.translate(relTranslateKey);
                relationTypesSelOpts[rel.id+"r"] = si4.translate(relRevTranslateKey);

                relationTypesMap[rel.id+"n"] = rel.name;
                relationTypesMap[rel.id+"r"] = rel.name_rev;
            }
            si4.data.relationTypes = relationTypes;
            si4.data.relationTypesSelOpts = relationTypesSelOpts;
            si4.data.relationTypesMap = relationTypesMap;

            si4.data.currentUser = response.currentUser;


            // Append menus

            var menuItems = [];
            var isAdmin = si4.data.currentUser.name == "Duhec";

            if (isAdmin) {
                menuItems.push({
                    loadArgs: { moduleName:'Dev/TestPage' },
                    caption: si4.translate("dev_testPage_mainTab_text"),
                });
                menuItems.push({
                    loadArgs: { moduleName:'Dev/ElasticTools' },
                    caption: si4.translate("dev_elasticTools_mainTab_text"),
                });
                menuItems.push({
                    loadArgs: { moduleName:'Entities/DbEntityList' },
                    caption: si4.translate("entities_dbEntityList_mainTab_text"),
                });
            }

            menuItems.push({
                loadArgs: { moduleName:'System/Dashboard' },
                caption: si4.translate("system_dashboard_mainTab_text"),
            });
            menuItems.push({
                loadArgs: { moduleName:'System/Notes' },
                caption: si4.translate("system_notes_mainTab_text"),
            });
            menuItems.push({
                loadArgs: { moduleName:'System/UserList' },
                caption: si4.translate("system_userList_mainTab_text"),
            });
            menuItems.push({
                loadArgs: { moduleName:'Entities/EntityList' },
                caption: si4.translate("entities_entityList_mainTab_text"),
            });
            menuItems.push({
                loadArgs: { moduleName:'Entities/CollectionList' },
                caption: si4.translate("entities_collectionList_mainTab_text"),
            });
            menuItems.push({
                loadArgs: { moduleName:'Entities/FileList' },
                caption: si4.translate("entities_fileList_mainTab_text"),
            });

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

        }

        si4.loading.hide();
    });

    //si4.loadModule({moduleName:'Dev/TestPage' });
});

