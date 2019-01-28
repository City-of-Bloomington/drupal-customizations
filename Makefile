SASS := $(shell command -v sassc 2> /dev/null)

default: clean compile package

deps:
ifndef SASS
	$(error "sassc is not installed")
endif

clean:
	rm -Rf build
	mkdir build

compile: deps
	cd web/themes/contrib/cob/css && sassc -t compact -m screen.scss screen.css

package:
	rsync -rl --exclude-from=buildignore . build/drupal
	cd build && tar czf drupal.tar.gz drupal
