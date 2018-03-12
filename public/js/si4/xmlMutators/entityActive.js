si4.xmlMutators.entityActive = function(xmlDoc, args) {
    var newValue = args.value;

    var xmlMetsHdr = xmlDoc.querySelector("metsHdr");

    var recStatusAttr = xmlDoc.createAttribute("RECORDSTATUS");
    recStatusAttr.value = newValue;
    xmlMetsHdr.attributes.setNamedItem(recStatusAttr);
};