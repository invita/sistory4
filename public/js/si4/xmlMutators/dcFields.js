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

        if (!xmlDC) {
            // Check <METS:dmdSec />
            var dmdSec = xmlDoc.querySelector("dmdSec[ID=default\\.dc]");
            if (!dmdSec) {
                dmdSec = xmlDoc.createElement("METS:dmdSec");
                dmdSec.setAttribute("ID", "default.dc");
                xmlDoc.children[0].appendChild(dmdSec);
            }

            // Check <METS:mdWrap />
            var mdWrap = xmlDoc.querySelector("dmdSec[ID=default\\.dc] mdWrap[MDTYPE=DC]");
            if (!mdWrap) {
                mdWrap = xmlDoc.createElement("METS:mdWrap");
                mdWrap.setAttribute("MDTYPE", "DC");
                mdWrap.setAttribute("MIMETYPE", "text/xml");
                dmdSec.appendChild(mdWrap);
            }

            // Check <METS:xmlData />
            var xmlDC = xmlDoc.createElement("METS:xmlData");
            mdWrap.appendChild(xmlDC);
        }

        // Remove existing
        if (xmlDC.children.length) {
            for (var childIdx = xmlDC.children.length - 1; childIdx >= 0; childIdx--) {
                // Legacy cleanup (dcterms)
                if (xmlDC.children[childIdx].tagName == "dcterms:" + fieldName)
                    xmlDC.children[childIdx].remove();
                else if (xmlDC.children[childIdx].tagName == "dc:" + fieldName)
                    xmlDC.children[childIdx].remove();
            }
        }

        // Ignore empty value
        if (!fieldValues.length || fieldValues.length == 1 && !fieldValues[0]) continue;

        // Add from back
        for (var i = fieldValues.length - 1; i >= 0; i--) {
            var fieldValue = fieldValues[i];
            var newEl = xmlDoc.createElement("dc:" + fieldName);

            if (fieldBP.withCode) {
                var attr = xmlDoc.createAttribute(fieldBP.codeXmlName);
                attr.value = fieldValue.codeId;
                newEl.attributes.setNamedItem(attr);
                newEl.textContent = fieldValue.value;
            } else {
                newEl.textContent = fieldValue;
            }

            if (fieldBP.addXmlAttrs) {
                for (var j in fieldBP.addXmlAttrs) {
                    var addAttr = xmlDoc.createAttribute(fieldBP.addXmlAttrs[j].name);
                    addAttr.value = fieldBP.addXmlAttrs[j].value;
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