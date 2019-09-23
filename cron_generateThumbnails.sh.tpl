#!/bin/bash
cd /var/www/sistory4
php artisan thumbs:createAll --noPrompt >> storage/logs/thumbs-generation-cron.log
