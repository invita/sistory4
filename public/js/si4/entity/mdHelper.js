si4.entity.mdHelper = {};

si4.entity.mdHelper.dcFieldOrder = [
    "title", "creator", "contributor", "date", "description",
    "format", "coverage", "language", "identifier", "publisher",
    "relation", "rights", "source", "subject", "type"
];

si4.entity.mdHelper.dcLangCodes = {
    slv: "slv",
    eng: "eng",
};

si4.entity.mdHelper.dcTypes = {
    "Collection": "Collection",
    "Dataset": "Dataset",
    "Event": "Event",
    "Image": "Image",
    "Interactive Resource": "Interactive Resource",
    "Moving Image": "Moving Image",
    "Physical Object": "Physical Object",
    "Service": "Service",
    "Software": "Software",
    "Sound": "Sound",
    "Still Image": "Still Image",
    "Text": "Text",
};

si4.entity.mdHelper.dcBlueprint = {
    title: {
        translation: si4.translate("field_title"),
        inputType: "text",
        //withCode: si4.entity.mdHelper.dcLangCodes,
        codeXmlName: "xml:lang",
    },
    creator: {
        translation: si4.translate("field_creator"),
        inputType: "text",
    },
    contributor: {
        translation: si4.translate("field_contributor"),
        inputType: "text",
    },
    date: {
        translation: si4.translate("field_date"),
        inputType: "text",
        addXmlAttrs: [{ name: "xsi:type", value: "dcterms:W3CDTF" }],
    },
    description: {
        translation: si4.translate("field_description"),
        inputType: "textarea",
    },
    format: {
        translation: si4.translate("field_format"),
        inputType: "text",
    },
    coverage: {
        translation: si4.translate("field_coverage"),
        inputType: "text",
        //withCode: si4.entity.mdHelper.dcLangCodes,
        codeXmlName: "xml:lang",
    },
    language: {
        translation: si4.translate("field_language"),
        inputType: "select",
        //values: si4.entity.mdHelper.dcLangCodes,
        addXmlAttrs: [{ name: "xsi:type", value: "dcterms:ISO639-3" }],
    },
    identifier: {
        translation: si4.translate("field_identifier"),
        inputType: "text",
    },
    publisher: {
        translation: si4.translate("field_publisher"),
        inputType: "text",
    },
    relation: {
        translation: si4.translate("field_relation"),
        inputType: "text",
    },
    rights: {
        translation: si4.translate("field_rights"),
        inputType: "text",
    },
    source: {
        translation: si4.translate("field_source"),
        inputType: "text",
    },
    subject: {
        translation: si4.translate("field_subject"),
        inputType: "text",
        //withCode: si4.entity.mdHelper.dcLangCodes,
        codeXmlName: "xml:lang",
    },
    type: {
        translation: si4.translate("field_type"),
        inputType: "select",
        values: si4.entity.mdHelper.dcTypes,
        addXmlAttrs: [{ name: "xsi:type", value: "dcterms:DCMIType" }],
    },
};
