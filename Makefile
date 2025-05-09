default: clean compile package

clean:
	rm -Rf build
	mkdir build

compile:

package:
	rsync -rl --exclude-from=buildignore . build/drupal
	cd build && tar czf drupal.tar.gz drupal
