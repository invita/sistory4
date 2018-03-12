si4.xmlMutators.dcFields = function(xmlDoc, args) {
    var newValue = args.value;

    // Find <METS:mdWrap MDTYPE="DC> + <xmlData>"
    var xmlDC = xmlDoc.querySelector("mdWrap[MDTYPE=DC] xmlData");

    var fieldNames = si4.entity.mdHelper.dcFieldOrder.slice(0); // Clone
    fieldNames.reverse();
    for (var fieldIdx in fieldNames) {
        var fieldName = fieldNames[fieldIdx];
        var fieldBP = si4.entity.mdHelper.dcBlueprint[fieldName];
        var fieldValues = newValue[fieldName];

        for (var childIdx = xmlDC.children.length - 1; childIdx >= 0; childIdx--)
            if (xmlDC.children[childIdx].tagName == "dcterms:" + fieldName)
                xmlDC.children[childIdx].remove();

        if (!fieldValues.length || fieldValues.length == 1 && !fieldValues[0]) continue;

        for (var i = fieldValues.length - 1; i >= 0; i--) {
            var fieldValue = fieldValues[i];
            var newEl = xmlDoc.createElement("dcterms:" + fieldName);

            if (fieldBP.withCode) {
                var attr = xmlDoc.createAttribute(fieldBP.codeXmlName);
                attr.value = fieldValue.codeId;
                newEl.attributes.setNamedItem(attr);
                newEl.textContent = fieldValue.value;
            } else {
                newEl.textContent = fieldValue;
            }

            if (fieldBP.addXmlAttrs) {
                for (var i in fieldBP.addXmlAttrs) {
                    var addAttr = xmlDoc.createAttribute(fieldBP.addXmlAttrs[i].name);
                    addAttr.value = fieldBP.addXmlAttrs[i].value;
                    newEl.attributes.setNamedItem(addAttr);
                }
            }

            if (xmlDC.children.length) {
                xmlDC.insertBefore(newEl, xmlDC.children[0]);
            } else {
                xmlDC.appendChild(newEl);
            }
        }
    }

};