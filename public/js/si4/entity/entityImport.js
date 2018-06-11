si4.entity.entityImport = function(formData, dataTable) {

    var importCheckUrl = "/admin/upload/import-check";
    var importUrl = "/admin/upload/import";

    console.log("post ", importCheckUrl, formData);

    si4.loading.show();
    $.ajax({
        type: 'POST',
        url: importCheckUrl,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            console.log("import-check callback", response);
            si4.loading.hide();

            if (response.status) {

                var importInfo = [];
                if (response.data["collections"]) importInfo.push("- "+response.data["collections"]+" collections");
                if (response.data["entities"]) importInfo.push("- "+response.data["entities"]+ " entities");
                if (response.data["files"]) importInfo.push("- "+response.data["files"]+ " files");
                if (response.data["unknown"]) importInfo.push("- "+response.data["unknown"]+ " unknown");

                var confirmData = { importInfo: importInfo.join(",\n")+"\n" };

                if (confirm(si4.translate("text_confirm_import_entities", confirmData))) {
                    si4.loading.show();
                    var importData = { uploadedFile: response.pathName };

                    console.log("post ", importUrl, importData);
                    $.ajax({
                        type: 'POST',
                        url: importUrl,
                        data: JSON.stringify(importData),
                        success: function(response){
                            console.log("import callback", response);

                            if (response.errors && response.errors.length) {
                                alert(si4.translate("ERR_ENTITY_IMPORT") +"\n\n"+
                                    response.errors.slice(0,si4.config.maxErrorsToAlert).join("\n") +
                                    (response.errors.length > si4.config.maxErrorsToAlert ? "\n\n..."+(response.errors.length -10)+" "+si4.translate("ERR_MORE_ERRORS") : "")
                                );
                            } else {
                                alert(si4.translate("text_import_entities_success", response.data));
                            }

                            setTimeout(function() {
                                dataTable.refresh();
                                si4.loading.hide();
                            }, 2000);
                        }
                    });
                }

            } else {
                alert(response.error);
            }
        }
    });

};