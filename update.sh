#!/bin/bash
DIR=`pwd`

echo "Checking dependencies"
declare -a dependencies=(node-sass node npm)
for i in "${dependencies[@]}"; do
    command -v $i > /dev/null 2>&1 || { echo "$i not installed" >&2; exit 1; }
done

composer update

cd web/themes/contrib/cob
composer update

cd vendor/City-of-Bloomington/factory-number-one
npm install
