#!/bin/bash
mkdir -p /home/dusza/larawhale/storage
mv /home/dusza/larawhale/.htaccess /home/dusza/www/.htaccess
mv /home/dusza/larawhale/larawhale.service /home/dusza/.config/systemd/user/larawhale.service

systemctl --user daemon-reload
systemctl --user enable larawhale
systemctl --user start larawhale
