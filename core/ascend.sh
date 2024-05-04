#!/bin/bash

script_path="C:\xampp\htdocs\vinance\core\app\Lib\Ascend.php"

while true; do
    php "$script_path" & 
    wait  

    sleep 2
done
