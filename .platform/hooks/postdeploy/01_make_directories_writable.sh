#!/bin/sh

# Laravel requires some directories to be writable.

sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/cache/
sudo chmod -R 755 storage/framework/cache

sudo touch storage/logs/laravel.log
sudo chmod -R 755 storage/logs/laravel.log
sudo chown -R webapp:webapp storage/