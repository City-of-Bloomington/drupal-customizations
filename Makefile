SASS := $(shell command -v pysassc 2> /dev/null)

default: clean compile package

deps:
ifndef SASS
	$(error "pysassc is not installed")
endif

clean:
	rm -Rf build
	mkdir build

compile: deps
	cd web/themes/contrib/cob/css && pysassc -t compact -m screen.scss screen.css

package:
	rsync -rl --exclude-from=buildignore --delete . build/drupal
	cd build && tar czf drupal.tar.gz drupal