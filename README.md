### Fortune-API
> This PHP Package is currently a work in progress.

### Running the tests
There are 3 levels of testing: acceptance, integration, and unit.

##### Running all the tests at once

    make tests

> Check the Makefile for other shortcuts to running the tests. Simply type `make` from the project root and you will get a list of available targets.

You can also run each test individually.

##### Acceptance Tests

    # start the PHP built-in server - do this in a separate terminal.
    # requires PHP 5.4+
    make server

    # run the tests
    ./vendor/bin/behat

##### Integration Tests

    # run the tests
    ./vendor/bin/phpunit

##### Unit Tests / Specs

    # run the specs
    ./vendor/bin/phpspec run
