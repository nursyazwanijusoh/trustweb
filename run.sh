#!/bin/bash
php artisan migrate --force
php artisan queue:work&
