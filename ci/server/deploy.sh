#!/bin/bash
cd /home/dusza/larawhale
mkdir -p /home/dusza/larawhale/storage
mkdir -p /home/dusza/larawhale/storage/framework/{sessions,views,cache}
mv /home/dusza/larawhale/htaccess /home/dusza/www/.htaccess

mv services/* /home/dusza/.config/systemd/user/

mv larawhale_linux-x86_64 frankenphp

./frankenphp php-cli artisan migrate --force
./frankenphp php-cli artisan db:seed ProductionSeeder --force

systemctl --user daemon-reload
systemctl --user enable larawhale.target
systemctl --user start larawhale.target
