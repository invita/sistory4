var F = function(args){
    args.createMainTab();
    args.createContentTab();

    var notes = si4.widget.si4Element({parent:args.contentTab.content.selector});
    notes.selector.css("padding", "10px").html(
        "<div class='docs'>\
        <p>Si4 is a complex system.\
            Entities with their mets xml as text are stored in a relational (Mysql) database only for permanent storage.\
            Every entity's xml must then be parsed, mapped and stored into an Elastic search index.\
            Retrieving entity data is then on done by querying Elastic search.\
            Entity xml is mostly entered by the user, but parts are calculated by the system too.\
        </p>\
        <b>When an entity is saved</b><br/>\
        <ul>\
            <li>If this is a new entity:\
                <ul>\
                    <li>New System Id is created and saved into Mysql along with basic entity data</li>\
                    <li>If Handle suffix field is not specified, system will acquire a new unique suffix for specified Content type. [1]</li>\
                </ul>\
            </li>\
            <li>Entity type (and if dependant, primary entity's Handle suffix) is calculated:\
                <ul>\
                    <li>Query Elastic search to find parent hierarchy chain, up to top-most entity (si4)</li>\
                    <li>Collections are primary if they have no parent and dependant otherwise</li>\
                    <li>Entities are primary if their parent is a collection</li>\
                    <li>Files are always primary</li>\
                </ul>\
            </li>\
            <li>Mets xml is then parsed and updated:\
                <ul>\
                    <li>METS:mets attributes ID and OBJID are both filled with value of Handle suffix field</li>\
                    <li>METS:mets attribute TYPE is filled with value of Content type field</li>\
                    <li>Premis data is filled (<pre>METS:mets/METS:amdSec/METS:techMD/METS:mdWrap[@MDTYPE='PREMIS:OBJECT']/METS:xmlData/premis:*</pre>)</li>\
                    <li>Parent/child hierarchy is written into xml METS:structMap (<pre>METS:mets/METS:structMap</pre>)</li>\
                    <li>Associated file metadata is written into xml (<pre>METS:mets/METS:fileSec</pre>)</li>\
                    <li>Xml is then formatted into a pretty (human readable) xml</li>\
                </ul>\
            </li>\
            <li>Entity xml is now saved into Mysql database</li>\
            <li>Entity is (re)indexed:\
                <ul>\
                    <li>Xml is parsed using mets xsd schema (which also validates the xml)</li>\
                    <li>Parsed xml data is then mapped and simplified</li>\
                    <li>JSON is created and pushed into Elastic index</li>\
                </ul>\
            </li>\
            <li>If entity contains a suitable file, thumbnail is generated</li>\
        </ul>\
        <b>[1] Content types are:</b><br/>\
        <ul>\
            <li>entity (Handle suffix: #)</li>\
            <li>collection (Handle suffix: menu#)</li>\
            <li>file (Handle suffix: file#)</li>\
        </ul>\
        </div>"
    );
};