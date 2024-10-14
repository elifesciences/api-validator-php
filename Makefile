
.PHONY: build test clean test
build:
	docker buildx build --build-arg=PHP_VERSION=$(PHP_VERSION) -t php-composer:$(PHP_VERSION) .

test: build
	docker run --rm -it -v ./:/code -v/code/vendor -e dependencies=lowest php-composer:$(PHP_VERSION) bash -c "cd /code && ./project_tests.sh"
	docker run --rm -it -v ./:/code -v/code/vendor -e dependencies=highest php-composer:$(PHP_VERSION) bash -c "cd /code && ./project_tests.sh"


test-7.1:
	@$(MAKE) PHP_VERSION=7.1 test
test-7.2:
	@$(MAKE) PHP_VERSION=7.2 test
test-7.3:
	@$(MAKE) PHP_VERSION=7.3 test
test-7.4:
	@$(MAKE) PHP_VERSION=7.4 test
test-8.0:
	@$(MAKE) PHP_VERSION=8.0 test
test-8.1:
	@$(MAKE) PHP_VERSION=8.1 test
test-8.2:
	@$(MAKE) PHP_VERSION=8.2 test

test-all: test-7.1 test-7.2 test-7.3 test-7.4 test-8.0 test-8.1 test-8.2
