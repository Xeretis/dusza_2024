#!/bin/bash
while :; do
    /home/dusza/larawhale/server/larawhale_linux-x86_64 php-cli artisan schedule:run
    sleep 60
done
