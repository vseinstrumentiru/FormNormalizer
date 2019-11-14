.PHONY: test
test: vendor/bin/phpunit
	./vendor/bin/phpunit -c phpunit.xml.dist --coverage-html=./coverage

vendor/bin/phpunit:
	composer install --prefer-dist
