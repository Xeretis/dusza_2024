#!/bin/bash
cd /home/dusza/larawhale
mkdir -p /home/dusza/larawhale/storage
mkdir -p framework/{sessions,views,cache}
mv /home/dusza/larawhale/htaccess /home/dusza/www/.htaccess
mv /home/dusza/larawhale/larawhale.service /home/dusza/.config/systemd/user/larawhale.service

mv larawhale_linux-x86_64 frankenphp

systemctl --user daemon-reload
systemctl --user enable larawhale
systemctl --user start larawhale
