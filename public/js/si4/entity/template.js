si4.entity.template = {};

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
    TYPE="entity"\r\
    ID="SISTORY.ID.[[[id]]]"\r\
    OBJID="[[[handleUrl]]]"\r\
    xsi:schemaLocation="http://www.loc.gov/METS/ http://www.loc.gov/mets/mets.xsd http://purl.org/dc/terms/ http://dublincore.org/schemas/xmls/qdc/dcterms.xsd http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-0.xsd http://www.loc.gov/standards/premis/v1 http://www.loc.gov/standards/premis/v1/PREMIS-v1-1.xsd">\r\
    <METS:metsHdr CREATEDATE="[[[timestamp]]]" LASTMODDATE="[[[timestamp]]]" RECORDSTATUS="Active">\r\
        <METS:agent ROLE="DISSEMINATOR" TYPE="ORGANIZATION">\r\
            <METS:name>Zgodovina Slovenije - SIstory</METS:name>\r\
        </METS:agent>\r\
        <METS:agent ROLE="CREATOR" ID="user.[[[userId]]]" TYPE="INDIVIDUAL">\r\
            <METS:name/>\r\
        </METS:agent>\r\
    </METS:metsHdr>\r\
    <METS:dmdSec ID="PREMIS.[[[id]]]" GROUPID="[[[id]]]">\r\
        <METS:mdWrap MDTYPE="PREMIS:OBJECT" MIMETYPE="text/xml">\r\
            <METS:xmlData>\r\
                <premis:object>\r\
                    <premis:objectIdentifier>\r\
                        <premis:objectIdentifierType>SIstory Entity ID</premis:objectIdentifierType>\r\
                        <premis:objectIdentifierValue>[[[id]]]</premis:objectIdentifierValue>\r\
                    </premis:objectIdentifier>\r\
                    <premis:objectIdentifier>\r\
                        <premis:objectIdentifierType>hdl</premis:objectIdentifierType>\r\
                        <premis:objectIdentifierValue>[[[handleUrl]]]</premis:objectIdentifierValue>\r\
                    </premis:objectIdentifier>\r\
                    <premis:objectCategory>Entity</premis:objectCategory>\r\
                </premis:object>\r\
            </METS:xmlData>\r\
        </METS:mdWrap>\r\
    </METS:dmdSec>\r\
    <METS:dmdSec ID="DC.[[[id]]]" GROUPID="[[[id]]]">\r\
        <METS:mdWrap MDTYPE="DC" MIMETYPE="text/xml">\r\
            <METS:xmlData>\r\
                <dcterms:title xml:lang="slv"></dcterms:title>\r\
                <dcterms:creator></dcterms:creator>\r\
                <dcterms:date xsi:type="dcterms:W3CDTF"></dcterms:date>\r\
            </METS:xmlData>\r\
        </METS:mdWrap>\r\
    </METS:dmdSec>\r\
    <METS:fileSec xmlns:xlink="http://www.w3.org/1999/xlink">\r\
    </METS:fileSec>\r\
    <METS:structMap xmlns:xlink="http://www.w3.org/1999/xlink" TYPE="logical" LABEL="sistory">\r\
    </METS:structMap>\r\
</METS:mets>\r\
';


si4.entity.template.getReplaceMap = function(data) {
    return {
        id: data.id,
        handleUrl: "http://hdl.handle.net/11686/"+data.id,
        userId: si4.data.currentUser.id,
        timestamp: si4.dateToISOString(),
    };
};

si4.entity.template.getEmptyMetsXml = function(data) {
    var replaceMap = si4.entity.template.getReplaceMap(data);
    var result = si4.entity.template.emptyMetsXmlTemplate;

    for (var key in replaceMap)
        result = result.replace(new RegExp('\\[\\[\\['+key+'\\]\\]\\]', 'ig'), replaceMap[key]);

    return result;
};
