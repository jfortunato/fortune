@parents
Feature: Resource Parents
    In order to request sub-resources
    As a user of this library
    I need to make the correct API calls

    Background:
        Given The following dogs exist:
            | id | name |
            | 1  | Fido |
        And The following puppies exist:
            | id | name  |
            | 1  | Daisy |
            | 2  | Lucca |
            | 3  | Lizzy |
        And The puppy "Daisy" belongs to dog "Fido"
        And The puppy "Lizzy" belongs to dog "Fido"
        And There is a config file containing the following:
            """
            dogs:
                parent: ~
                entity: Fortune\Test\Entity\Dog
                validator: Fortune\Test\Validator\DogValidator
                access_control:
                    authentication: false
                    role: ~
                    owner: false
            puppies:
                parent: dogs
                entity: Fortune\Test\Entity\Puppy
                validator: Fortune\Test\Validator\PuppyValidator
            """

    Scenario: Getting a list of all puppies
        When I send a GET request to "/dogs/1/puppies"
        Then The response code should be 200
        Then The response is JSON
        And The response should contain JSON:
            """
            [{"name":"Daisy"}, {"name":"Lizzy"}]
            """

    Scenario: Getting a single puppy
        When I send a GET request to "/dogs/1/puppies/1"
        Then The response code should be 200
        Then The response is JSON
        And The response should contain JSON:
            """
            {"name":"Daisy"}
            """

    Scenario: Creating a new puppy
        Given I send the following parameters:
            | name   |
            | Digger |
        When I send a POST request to "/dogs/1/puppies"
        Then The response code should be 201
        And The response should contain JSON:
            """
            {"id":"4"}
            """
        When I send a GET request to "/dogs/1/puppies/4"
        Then The response should contain JSON:
            """
            {"name":"Digger"}
            """

    Scenario: Updating a puppy
        Given I send the following parameters:
            | name |
            | Ava  |
        When I send a PUT request to "/dogs/1/puppies/1"
        Then The response code should be 204
        When I send a GET request to "/dogs/1/puppies/1"
        Then The response should contain JSON:
            """
            {"name":"Ava"}
            """

    Scenario: Deleting a puppy
        When I send a DELETE request to "/dogs/1/puppies/1"
        Then The response code should be 204
        When I send a GET request to "/dogs/1/puppies/1"
        Then The response code should be 404

    Scenario: Denied access when resource has parent but we try to directly access the child
        When I send a GET request to "/puppies"
        Then The response code should be 403
