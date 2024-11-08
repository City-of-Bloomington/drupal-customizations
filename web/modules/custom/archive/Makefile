SHELL := /bin/bash
APPNAME := archive

default: clean package

clean:
	rm -Rf build/${APPNAME}*

package:
	[[ -d build ]] || mkdir build
	rsync -rl --exclude-from=buildignore . build/${APPNAME}
	cd build && tar czf ${APPNAME}.tar.gz ${APPNAME}
