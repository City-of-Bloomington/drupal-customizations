APP_NAME = onboard

default: clean package

clean:
	rm -Rf build
	mkdir build

package:
	rsync -rl --exclude-from=buildignore --delete . build/${APP_NAME}
	cd build && tar czf ${APP_NAME}.tar.gz ${APP_NAME}
