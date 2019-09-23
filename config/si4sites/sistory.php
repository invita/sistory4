<?php
return [
    "siteName" => "SIstory",
    "siteUrl" => "http://sistory.si",
    "handlePrefix" => "11686",
    "defaultLang" => "slv",
    "oai_identifyInfo" => array(
        "repositoryName" => "Sistory.si OAI Repository",
        "baseURL" => "http://www.sistory.si/oai",
        "protocolVersion" => "2.0",
        "adminEmail" => "gregor@invita.si",
        "earliestDatestamp" => "2011-08-01T00:00:00Z",
        "deletedRecord" => "no",
        "granularity" => "YYYY-MM-DDThh:mm:ssZ"
    ),
    "dashboardFiles" => [
        [
            "name" => "Cron Full-text reindex log",
            "filePath" => "{storagePath}/logs/reindex-fullText-cron.log",
            "tailLimit" => 100,
        ],
        [
            "name" => "Cron Thumbs generation log",
            "filePath" => "{storagePath}/logs/thumbs-generation-cron.log",
            "tailLimit" => 100,
        ],
    ]
];