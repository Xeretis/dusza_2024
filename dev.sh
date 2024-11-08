#!/bin/bash
# Author: Yours truly
# Helper for dusza2024 on Linux / macOS hosts
# Not the prettiest script, it is definetely hacked together, but I digress. I mean, it does the job
# Windows users: My sincere apologies, I don't know powershell

tmux new-session php artisan octane:start \; split-window -h "docker compose up" \; split-window -h "sleep 5 && php artisan horizon" \; split-window -d "pnpm dev" \; split-window -v "while :; do php artisan schedule:run; sleep 2; done" \; attach

docker compose down --volumes -t 00
