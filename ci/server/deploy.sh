#!/bin/bash
cd /home/dusza/larawhale
mkdir -p /home/dusza/larawhale/storage
mkdir -p /home/dusza/larawhale/storage/framework/{sessions,views,cache}
mv /home/dusza/larawhale/htaccess /home/dusza/www/.htaccess

mv services/* /home/dusza/.config/systemd/user/
chmod +x franken_wrapper
mv larawhale_linux-x86_64 frankenphp

./frankenphp php-cli artisan migrate --force

systemctl --user daemon-reload

systemctl --user enable larawhale_octane
systemctl --user enable larawhale_queue
systemctl --user enable larawhale_redis
systemctl --user enable larawhale_reverb
systemctl --user enable larawhale_cron

systemctl --user enable larawhale.target
systemctl --user stop larawhale.target
systemctl --user start larawhale.target

sleep 1
./frankenphp php-cli artisan db:seed ProductionSeeder --force
