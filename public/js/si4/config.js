si4.config = {};

si4.config.modulePath = "/module/";
si4.config.uploadApiUrl = "/admin/upload";
si4.config.apiUrl = "/admin/api";

si4.config.apis = {
    saveEntity: si4.config.apiUrl+"/save-entity",
    entityList: si4.config.apiUrl+"/entity-list",

    userList: si4.config.apiUrl+"/user-list",
    saveUser: si4.config.apiUrl+"/save-user",

    devTools: si4.config.apiUrl+"/dev-tools",
};

si4.config.uploadApis = {
    entity: si4.config.uploadApiUrl+"/entity",
}

//si4.config.