si4.config = {};

si4.config.repositoryName = "SIstory";

si4.config.modulePath = "/module/";
si4.config.uploadApiUrl = "/admin/upload";
si4.config.apiUrl = "/admin/api";

si4.config.apiNames = [

    "dashboardFiles",

    "fieldDefinitions", "fieldDefinitionsList", "saveFieldDefinition", "deleteFieldDefinition",
    "behaviourList", "saveBehaviour", "deleteBehaviour",
    "behaviourFieldList", "saveBehaviourField", "deleteBehaviourField",

    "mappingGroupList", "saveMappingGroup", "deleteMappingGroup",
    "mappingGroupFieldsList", "saveMappingGroupField", "deleteMappingGroupField",
    "xmlToSi4Test", "xmlXPathTest",

    "oaiGroupList", "saveOaiGroup", "deleteOaiGroup",
    "oaiGroupFieldsList", "saveOaiGroupField", "deleteOaiGroupField",


    "entityList", "entityListDb", "saveEntity", "reserveEntityId", "deleteEntity",
    "entityHierarchy", "forceReindexEntity", "forceRegenThumb",

    "entityRelationsList", "saveEntityRelation", "deleteEntityRelation",
    "userList", "saveUser", "deleteUser",
    "fileList", "saveFile", "deleteFile",
    "initialData",
    "translationsList", "saveTranslation", "deleteTranslation", "generateTranslationFiles",
    "devTools"
];

/*
si4.config.apis = {};
for (var i in si4.config.apiNames) {
    var apiName = si4.config.apiNames[i];
    //si4.config.apis[apiName] = si4.config.apiUrl+"/"+si4.apiUrlFromName(apiName);
}

si4.config.apis = {
    initialData: si4.config.apiUrl+"/initial-data",

    reserveEntityId: si4.config.apiUrl+"/reserve-entity-id",
    saveEntity: si4.config.apiUrl+"/save-entity",
    entityList: si4.config.apiUrl+"/entity-list",
    deleteEntity: si4.config.apiUrl+"/delete-entity",

    userList: si4.config.apiUrl+"/user-list",
    saveUser: si4.config.apiUrl+"/save-user",
    deleteUser: si4.config.apiUrl+"/delete-user",

    devTools: si4.config.apiUrl+"/dev-tools",
};
*/

si4.config.uploadApis = {
    entity: si4.config.uploadApiUrl+"/entity",
};

si4.config.maxErrorsToAlert = 10;

//si4.config.