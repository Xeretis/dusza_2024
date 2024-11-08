#!/bin/bash
# Author: Yours truly
# Helper for dusza2024 on Linux / macOS hosts
# Not the prettiest script, it is definetely hacked together, but I digress. I mean, it does the job
# Windows users: My sincere apologies, I don't know powershell

if ! command -v supervisorctl &>/dev/null; then
    echo "supervisorctl could not be found"
    echo "Please install supervisor"
    exit
fi

if ! command -v unbuffer &>/dev/null; then
    echo "unbuffer could not be found"
    echo "Please install expect"
    exit
fi

if ! command -v docker &>/dev/null; then
    echo "docker could not be found"
    echo "Please install docker"
    exit
fi

supervisord -c supervisord.conf

docker compose down --volumes -t 00
