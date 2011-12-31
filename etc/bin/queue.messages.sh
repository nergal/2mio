#!/bin/sh
sudo -unginx php /path/to/src/public/index.php --uri='cron/queue/messages'
