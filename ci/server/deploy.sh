#!/bin/bash
mkdir -p /home/dusza/larawhale/storage
mv /home/dusza/larawhale/server/.htaccess /home/dusza/www/.htaccess
mv /home/dusza/larawhale/server/larawhale.service /home/dusza/.config/systemd/user/larawhale.service

systemctl --user daemon-reload
systemctl --user enable larawhale
systemctl --user start larawhale
