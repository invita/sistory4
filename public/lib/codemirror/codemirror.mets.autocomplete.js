// Scope variables in here:
var codemirrorMets = {};

// common dcTerms element:
codemirrorMets.dcTermsEl = {
    attrs: {
        "xml:lang": ["slv", "eng"],
    },
    children: []
};

codemirrorMets.autoCompleteConfig = {
    "!top": ["METS:metsHdr", "METS:dmdSec", "METS:amdSec", "METS:fileSec", "METS:structMap"],
    "!attrs": {
        ID: null,
        TYPE: null,
    },

    "METS:dmdSec": {
        attrs: {
            GROUPID: null,
        },
        children: ["METS:mdWrap"]
    },
    "METS:mdWrap": {
        attrs: {
            MDTYPE: ["PREMIS:OBJECT", "DC", "MODS"],
            MIMETYPE: ["text/xml"],
        },
        children: ["METS:xmlData"]
    },
    "METS:xmlData": {
        attrs: {},
        children: ["dcterms:title", "dcterms:creator", "dcterms:description", "dcterms:subject",
            "dcterms:publisher", "dcterms:contributor", "dcterms:date", "dcterms:type", "dcterms:identifier",
            "dcterms:language", "dcterms:coverage", "dcterms:license" ],
    },
    "dcterms:title": codemirrorMets.dcTermsEl,
    "dcterms:creator": codemirrorMets.dcTermsEl,
    "dcterms:description": codemirrorMets.dcTermsEl,
    "dcterms:subject": codemirrorMets.dcTermsEl,
    "dcterms:publisher": codemirrorMets.dcTermsEl,
    "dcterms:contributor": codemirrorMets.dcTermsEl,
    "dcterms:date": codemirrorMets.dcTermsEl,
    "dcterms:type": codemirrorMets.dcTermsEl,
    "dcterms:identifier": codemirrorMets.dcTermsEl,
    "dcterms:language": codemirrorMets.dcTermsEl,
    "dcterms:coverage": codemirrorMets.dcTermsEl,
    "dcterms:license": codemirrorMets.dcTermsEl,

};
