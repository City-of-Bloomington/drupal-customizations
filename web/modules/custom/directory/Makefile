default: clean package

clean:
	rm -Rf build
	mkdir build

package:
	rsync -rl --exclude-from=buildignore --delete . build/directory
	cd build && tar czf directory.tar.gz directory
