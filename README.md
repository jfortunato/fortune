# Fortune-API
Simplify repetitive tasks when building RESTful API's.

## Installation

    composer require fortune/fortune ~0.1

## Getting Started

Fortune can be used with native PHP or with many popular PHP frameworks. The [Slim PHP framework](http://www.slimframework.com/) is easy to get started with mainly for its simple routing, and support for it has been built directly into this library. This guide will walk through a few simple steps to get started using Fortune with Slim.
 
 > Native PHP without a framework will require a way to handle routing. There are many good routing libraries that can be found through composer on [packagist.](http://packagist.org)

##### Setting up a database

Fortune requires a database to be setup for use. You can use **either** native PDO or the Doctrine ORM library. Probably the simplest way to get started is to use PDO with a basic sqlite database.

    $database = new \PDO("sqlite:" . __DIR__ . "/db.sqlite");

Now you can create that database on the filesystem and use any sqlite admin tool to start creating tables.

##### Configuring resources

For every resource / table that you want to make available in the database, a matching config entry should be present. The recommended way to configure resources is with YAML, but a simple PHP array will do as well. Assuming you have a `dogs` table in your sqlite database, a basic configuration should be setup as follows: 
###### YAML (Recommended - easier to read & manage)

    # YAML (using Symfony2 yaml component)
    composer require symfony/yaml ~2.0

    # resources.yaml
    dogs: ~

    $config = Yaml::parse(file_get_contents("path/to/resources.yaml"));

###### PHP Array (Easier to get started with)

    # php array
    $config = array('dogs');

##### Using Fortune with Slim

Follow the [documentation for slim](http://docs.slimframework.com/#Installation) to get started with a Hello World application with slim. Once you are comfortable with the very basics of slim and have a Hello World route setup, you can begin setting up Fortune-API.

> **TIP**: You don't need to setup apache to get started quickly. Just use the PHP built-in server by using the command:
>
> php -S localhost:8000

    # index.php

    # setup Slim $app, $database & $config
    ...

    $factory = new ResourceFactory($database, $config);
    $factory->generateSlimRoutes($app);

    ...

    $app->run();

Just like that, you should be able to navigate to `localhost:8000/dogs` in your browser and depending on the data in the database, you should see a JSON representation of the data.

###### Available routes that were generated

    GET    /dogs       # list all dogs  
    GET    /dogs/:id   # view a single dog  
    POST   /dogs       # create a new dog
    PUT    /dogs/:id   # update an existing dog
    DELETE /dogs/:id   # delete a dog

##### Using Fortune with native PHP

> TODO

## Configuration

All it takes to get started is a basic configuration file that specifies which resources to use. (Using yaml here, PHP arrays work too.)

    # resources.yaml
    dogs: ~

##### Child / Parent Resources

If a resource belongs to another resource, then it should be accessed in a RESTful manner with the parent identifier in the URL. So to access all the puppies which belong to a dog with id 1, the url that should be accessed is `localhost:8000/dogs/1/puppies`. Configuration for this url is dead simple.

    # resources.yaml
    dogs: ~
    puppies:
        parent: dogs

##### Validation

> Check out the [Valitron](https://github.com/vlucas/valitron) validator documentation for rules that can be used.

The simplest way to do validation is directly in the resource config.

    # resources.yaml
    dogs:
        validation:
            name: required

You can also specify a class that you can use that will take care of validation. This class must be a subclass of `Fortune\Validator\ValitronResourceValidator` and implement the `addRules` method. Its in this method that you directly access the valitron `Validator` object that will be used.

    # resources.yaml
    dogs:
        validation: My\Namespace\DogValidator

    # DogValidator.php
    <?php

    namespace My\Namespace;

    use Fortune\Validator\ValitronResourceValidator;
    use Valitron\Validator;

    class DogValidator extends ValitronResourceValidator
    {
        protected $rules = [
            'required' => 'name',
        ];

        public function addRules(Validator $v)
        {
            $v->rules($this->rules);
        }
    }

##### Resource Security

Any resource can be restricted based on certain criteria. Currently, this criteria is based on authentication, role, and owner. Any combination of these may be used, but if only one fails access will be denied for the resource.

###### Authentication checks if a user is logged in before allowing a resource to be accessed.

    # resources.yaml
    dogs:
        access_control:
            authentication: true

###### Role checks if a logged in user has a certain role before allowing a resource to be accessed.

    # resources.yaml
    dogs:
        access_control:
            role: ADMIN

###### Owner restricts access to the resource to its owner.

    # resources.yaml
    dogs:
        access_control:
            owner: true

##### Doctrine Entity

If you are using doctrine, you should pass the entity class to the config file.

    # resources.yaml
    dogs:
        entity: My\Namespace\Dog

## Running the tests
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
