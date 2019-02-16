#!/usr/bin/env bash
if [ $(whoami) != "docker" ]; then
    echo "Must run as docker user"
    exit;
fi

if [ -z "$*" ]; then
    echo "Require username and password arguments";
    exit;
fi

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

cd $(echo $DIR) && mysql -u$1 -p$2 -hmysql <drop_db.sql
cd $(echo $DIR) && mysql -u$1 -p$2 -hmysql <create_db.sql

cd $(echo $DIR)/../ && composer install && php artisan migrate; php artisan db:seed
