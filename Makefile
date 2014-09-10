PHP_SERVER_HOST  = localhost
PORT             = 8000
PHP_SERVER_ROOT  = ./tests/acceptance/slim-framework/public/
START_SERVER_CMD = php -S $(PHP_SERVER_HOST):$(PORT) --docroot $(PHP_SERVER_ROOT)
KILL_SERVER_CMD  = fuser -k $(PORT)/tcp # Linux only
#KILL_SERVER_CMD  = kill -9 $(shell lsof -i tcp:$(PORT) -t) # Mac/Linux - but its alternating working / not working every run

BEHAT_BIN        = ./vendor/bin/behat
BEHAR_ARGS       =

PHPUNIT_BIN      = ./vendor/bin/phpunit
PHPUNIT_ARGS     =

PHPSPEC_BIN      = ./vendor/bin/phpspec
PHPSPEC_ARGS     = --format=dot

.PHONY: help tests test-acceptance test-integration test-unit server

help:
	@echo
	@echo "-----------------------"
	@echo "Usage: 'make <target>'"
	@echo "-----------------------"
	@echo "Available targets:"
	@echo "  install          does a composer install"
	@echo "  tests            run all the test suites"
	@echo "  test-acceptance  run the acceptance tests"
	@echo "  test-integration run the integration tests"
	@echo "  test-unit        run the unit tests"
	@echo "  server           start the PHP built in server. You can change the port number with 'make server PORT=8888' (default PORT is 8000)"
	@echo

install:
	@composer install -q

tests: test-acceptance test-integration test-unit

test-acceptance: install
	@echo
	@echo
	@echo "#######################"
	@echo "ACCEPTANCE"
	@echo "#######################"
	@nohup $(START_SERVER_CMD) > /dev/null &
	@$(BEHAT_BIN) $(BEHAR_ARGS)
	@$(KILL_SERVER_CMD)

test-integration: install
	@echo
	@echo
	@echo "#######################"
	@echo "INTEGRATION"
	@echo "#######################"
	@$(PHPUNIT_BIN) $(PHPUNIT_ARGS)

test-unit: install
	@echo
	@echo
	@echo "#######################"
	@echo "UNIT"
	@echo "#######################"
	@$(PHPSPEC_BIN) $(PHPSPEC_ARGS) run

server:
	@$(START_SERVER_CMD)
