DOCROOT = public/
BACKEND_SERVER_PORT = 8080
BACKEND_SERVER_LISTEN_IP = 127.0.0.1
PHP_PATH = php

.PHONY: start
start:
	$(PHP_PATH) -S $(BACKEND_SERVER_LISTEN_IP):$(BACKEND_SERVER_PORT) -t $(DOCROOT)

.PHONY: setup
setup: composer.phar db-reset
	$(PHP_PATH) composer.phar install

composer.phar:
	curl -sSfL -o composer-setup.php https://getcomposer.org/installer
	$(PHP_PATH) composer-setup.php --filename=composer.phar
	rm composer-setup.php

.PHONY: db-reset
db-reset:
	-rm sqlite.db
	sqlite3 sqlite.db < db/sqlite_ddl.sql

.PHONY: test
test:
	$(PHP_PATH) vendor/bin/phpunit tests/

.PHONY: shell
shell:
	$(PHP_PATH) shell.php
