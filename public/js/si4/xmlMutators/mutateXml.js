si4.xmlMutators.mutateXml = function(si4field, mutatorName, args) {
    if (!si4.xmlMutators[mutatorName]) {
        console.warn("No xml mutator named", mutatorName);
        return;
    }

    var xmlStr = si4field.getValue();
    var parser = new DOMParser();
    var xmlDoc = parser.parseFromString(xmlStr, "text/xml");

    si4.xmlMutators[mutatorName](xmlDoc, args);

    var xmlText = new XMLSerializer().serializeToString(xmlDoc);
    var xmlTextPretty = vkbeautify.xml(xmlText);
    si4field.setValue(xmlTextPretty);

    if (si4field.codemirror) si4field.codemirror.refresh();

};