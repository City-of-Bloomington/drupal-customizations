REQS := sassc
K := $(foreach r, ${REQS}, $(if $(shell command -v ${r} 2> /dev/null), '', $(error "${r} not installed")))

default: clean compile package

clean:
	rm -Rf build
	mkdir build

compile:
	cd web/themes/custom/cob/css && sassc -t compact -m screen.scss screen.css

package:
	rsync -rl --exclude-from=buildignore . build/drupal
	cd build && tar czf drupal.tar.gz drupal
