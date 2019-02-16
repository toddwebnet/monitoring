#!/usr/bin/env bash

[ -e ../../docker/nginx/sites/monitor.dev.idevcode.com.conf ] && rm ../../docker/nginx/sites/monitor.dev.idevcode.com.conf
cp ./monitor.dev.idevcode.com.conf ../../docker/nginx/sites/monitor.dev.idevcode.com.conf
