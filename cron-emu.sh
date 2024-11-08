#!/bin/bash
while :; do
    php artisan schedule:run
    sleep 60
done
