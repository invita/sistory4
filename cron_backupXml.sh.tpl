#!/bin/bash
cd /var/www/sistory4/backup

DIR=./backup/
LOCK_FILE=".backup_lock"
FORMAT='+%Y-%m-%d %H:%M:%S'

if [ -f $LOCK_FILE ]; then
    exit
fi

touch $LOCK_FILE

git add *
git commit -m "Automatic backup  $(date "$FORMAT")"

rm $LOCK_FILE