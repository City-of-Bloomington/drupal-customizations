#!/bin/bash
DIR=`pwd`
BUILD=$DIR/build

echo "Checking dependencies"
declare -a dependencies=(node-sass node npm)
for i in "${dependencies[@]}"; do
    command -v $i > /dev/null 2>&1 || { echo "$i not installed" >&2; exit 1; }
done

# The FN1 dependency is only needed for the variables file contained in its source.
#
# WE currently do not need to actually compile FN1 here.
# The compiled version of FN1 is configured as an external library.
# We are hosting it on our main web server.
#
#cd web/themes/contrib/cob/vendor/City-of-Bloomington/factory-number-one
#./gulp

echo "Compiling theme SCSS"
cd web/themes/contrib/cob/css
node-sass --output-style compact --source-map ./ screen.scss ./screen.css

cd $DIR
if [ ! -d $BUILD ]
	then mkdir $BUILD
fi
rsync -rl --exclude-from=$DIR/buildignore --delete $DIR/ $BUILD/
