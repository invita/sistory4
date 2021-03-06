si4.entity.hintHelper = {};

si4.entity.hintHelper.displayEntityInfo = function(handle_id) {
    if (!handle_id) return;

    si4.api.entityList({ filter: { handle_id: handle_id } }, function(response) {
        console.log("Hint resolution", response);
        if (!response.data || !response.data.length) return;
        var entityData = response.data[0];

        var NL = "<br/>\n";
        var hintStr = "<b>Handle Id: "+handle_id+"</b>"+ (entityData.date ? " ("+entityData.date+")" : "") +NL;
        if (entityData.title) hintStr += "Title: "+entityData.title+NL;
        if (entityData.creator) hintStr += "Creator: "+entityData.creator+NL;
        si4.showHint(hintStr);
    });
};

si4.entity.hintHelper.displayEntityInfoDT = function(dtArgs) {
    return si4.entity.hintHelper.displayEntityInfo(dtArgs.field.getValue());
};