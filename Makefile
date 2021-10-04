.PHONY: lint tests \
		install-dependencies update-dependencies \
		start-db-tests stop-db-tests

default: start-db-tests lint tests

lint:
	vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php --dry-run

lint-fix:
	vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php

tests:
	vendor/bin/phpunit --configuration phpunit.xml

install-dependencies:
	composer install

update-dependencies:
	composer update

start-db-tests:
	docker start serieall-tests-mysql || docker run  \
		--name serieall-tests-mysql \
		-p 3306:3306 \
		-e MYSQL_DATABASE="serieall-tests" \
		-e MYSQL_ROOT_PASSWORD="serieall" \
		-d mysql:5.7

stop-db-tests:
	docker stop serieall-tests-mysql
	docker rm serieall-tests-mysql
