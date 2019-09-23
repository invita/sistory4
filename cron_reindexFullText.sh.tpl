#!/bin/bash
cd /var/www/sistory4
php artisan reindex:entitiesText --noPrompt >> storage/logs/reindex-fullText-cron.log
