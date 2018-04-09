// https://github.com/SIstory/si4-mets

si4.entity.template = {};

si4.entity.template.emptyMetsXmlTemplateParts = {
    entity: '\r\
                <METS:mdWrap MDTYPE="OTHER" OTHERMDTYPE="ENTITY" MIMETYPE="text/xml">\r\
                    <METS:xmlData xmlns:entity="http://sistory.si/schema/si4/entity" xsi:schemaLocation="http://sistory.si/schema/si4/entity ../../schema/entity.1.0.xsd">\r\
                        <entity:description xml:lang="slv"></entity:description>\r\
                        <entity:page></entity:page>\r\
                        <entity:removedTo></entity:removedTo>\r\
                    </METS:xmlData>\r\
                </METS:mdWrap>',

    collection: '\r\
                <METS:mdWrap MDTYPE="OTHER" OTHERMDTYPE="ENTITY" MIMETYPE="text/xml">\r\
                    <METS:xmlData xmlns:collection="http://sistory.si/schema/si4/collection" xsi:schemaLocation="http://sistory.si/schema/si4/collection ../../schema/collection.1.0.xsd">\r\
                        <collection:description xml:lang="slv"></collection:description>\r\
                        <collection:externalCollection></collection:externalCollection>\r\
                        <collection:wholeContent></collection:wholeContent>\r\
                        <collection:searchResultsSort>Title ascending</collection:searchResultsSort>\r\
                        <collection:searchResultsShow>Multiple per line</collection:searchResultsShow>\r\
                    </METS:xmlData>\r\
                </METS:mdWrap>',

    file: '\r\
                <METS:mdWrap MDTYPE="OTHER" OTHERMDTYPE="FILE" MIMETYPE="text/xml">\r\
                    <!-- Si4 specific administrative metadata for File -->\r\
                </METS:mdWrap>',

};

si4.entity.template.emptyMetsXmlTemplate =
'<?xml version="1.0" encoding="UTF-8"?>\r\
<METS:mets xmlns:METS="http://www.loc.gov/METS/"\r\
    xmlns:xlink="http://www.w3.org/TR/xlink"\r\
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"\r\
    xmlns:dc="http://purl.org/dc/elements/1.1/"\r\
    xmlns:dcterms="http://purl.org/dc/terms/"\r\
    xmlns:dcmitype="http://purl.org/dc/dcmitype/"\r\
    xmlns:premis="http://www.loc.gov/standards/premis/v1"\r\
    xmlns:mods="http://www.loc.gov/mods/v3"\r\
    xmlns:entity="http://sistory.si/schema/sistory/v3/entity"\r\
    ID="[[[handleId]]]"\r\
    TYPE="[[[structType]]]"\r\
    OBJID="[[[handleUrl]]]"\r\
    xsi:schemaLocation="http://www.loc.gov/METS/ http://www.loc.gov/mets/mets.xsd http://purl.org/dc/terms/ http://dublincore.org/schemas/xmls/qdc/dcterms.xsd http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-0.xsd http://www.loc.gov/standards/premis/v1 http://www.loc.gov/standards/premis/v1/PREMIS-v1-1.xsd">\r\
    <METS:metsHdr CREATEDATE="[[[timestamp]]]" LASTMODDATE="[[[timestamp]]]" RECORDSTATUS="Active">\r\
        <METS:agent ROLE="DISSEMINATOR" TYPE="ORGANIZATION">\r\
            <METS:name>SIstory</METS:name>\r\
            <METS:note>http://sistory.si/</METS:note>\r\
        </METS:agent>\r\
        <METS:agent ROLE="CREATOR" ID="user.[[[userId]]]" TYPE="INDIVIDUAL">\r\
            <METS:name></METS:name>\r\
        </METS:agent>\r\
    </METS:metsHdr>\r\
    <METS:dmdSec ID="default.dc">\r\
        <METS:mdWrap MDTYPE="DC" MIMETYPE="text/xml">\r\
            <METS:xmlData>\r\
                <dcterms:title xml:lang="slv"></dcterms:title>\r\
                <dcterms:creator></dcterms:creator>\r\
                <dcterms:date></dcterms:date>\r\
            </METS:xmlData>\r\
        </METS:mdWrap>\r\
    </METS:dmdSec>\r\
    <METS:amdSec ID="default.amd">\r\
        <METS:techMD ID="default.premis">\r\
            <METS:mdWrap MDTYPE="PREMIS:OBJECT" MIMETYPE="text/xml">\r\
                <METS:xmlData>\r\
                    <premis:objectIdentifier>\r\
                        <premis:objectIdentifierType>si4</premis:objectIdentifierType>\r\
                        <premis:objectIdentifierValue>[[[handleId]]]</premis:objectIdentifierValue>\r\
                    </premis:objectIdentifier>\r\
                    <premis:objectIdentifier>\r\
                        <premis:objectIdentifierType>Local name</premis:objectIdentifierType>\r\
                        <premis:objectIdentifierValue>[[[id]]]</premis:objectIdentifierValue>\r\
                    </premis:objectIdentifier>\r\
                    <premis:objectIdentifier>\r\
                        <premis:objectIdentifierType>hdl</premis:objectIdentifierType>\r\
                        <premis:objectIdentifierValue>[[[handleUrl]]]</premis:objectIdentifierValue>\r\
                    </premis:objectIdentifier>\r\
                    <premis:objectCategory>[[[structType]]]</premis:objectCategory>\r\
                </METS:xmlData>\r\
            </METS:mdWrap>\r\
        </METS:techMD>\r\
        <METS:techMD ID="default.si4">[[[si4techMD]]]\r\
        </METS:techMD>\r\
    </METS:amdSec>\r\
    <METS:fileSec ID="default.file" xmlns:xlink="http://www.w3.org/1999/xlink">\r\
        <METS:fileGrp>\r\
            <METS:file MIMETYPE="" SIZE="" CREATED="" CHECKSUM="" CHECKSUMTYPE="">\r\
            </METS:file>\r\
        </METS:fileGrp>\r\
    </METS:fileSec>\r\
    <METS:structMap ID="default.structure" TYPE="primary" xmlns:xlink="http://www.w3.org/1999/xlink">\r\
    </METS:structMap>\r\
</METS:mets>\r\
';


si4.entity.template.getReplaceMap = function(data) {
    var replaceMap = {
        id: data.id,
        handleId: data.handleId,
        handleUrl: "http://hdl.handle.net/11686/"+data.handleId,
        structType: data.structType,
        userId: si4.data.currentUser.id,
        timestamp: si4.dateToISOString(),
    };

    var si4techMDTemplate = si4.entity.template.emptyMetsXmlTemplateParts[data.structType];
    if (si4techMDTemplate) {
        replaceMap.si4techMD = si4.entity.template.replaceMetsXml(si4techMDTemplate, replaceMap);
    }

    return replaceMap;
};

si4.entity.template.replaceMetsXml = function(xml, replaceMap) {
    var result = xml;
    for (var key in replaceMap)
        result = result.replace(new RegExp('\\[\\[\\['+key+'\\]\\]\\]', 'ig'), replaceMap[key]);
    return result;
};

si4.entity.template.getEmptyMetsXml = function(data) {
    var replaceMap = si4.entity.template.getReplaceMap(data);
    var xml = si4.entity.template.emptyMetsXmlTemplate;
    return si4.entity.template.replaceMetsXml(xml, replaceMap)
};
