#!/bin/bash

# script_path="C:\xampp\htdocs\vinance\core\app\Lib\Ascend.php"

while true; do
    # php "$script_path" & 
    php artisan binary:calculate
    wait  

    sleep 10
done
