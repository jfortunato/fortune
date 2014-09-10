@crud
Feature: Resource CRUD Actions
    In order to keep logic DRY and simplify CRUD for a resource
    As an implementer of this library
    I need make the correct API calls

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
            | 2  | Daisy |
        And There is a config file containing the following:
            """
            dogs:
                entity: Fortune\Test\Entity\Dog
                validator: Fortune\Test\Validator\DogValidator
                parent: ~
                access_control:
                    authentication: false
                    role: ~
                    owner: false
            """

    Scenario: Getting a list of all dogs
        When I send a GET request to "/dogs"
        Then The response code should be 200
        Then The response is JSON
        And The response should contain JSON '[{"name":"Fido"}, {"name":"Daisy"}]'

    Scenario: Getting a a single dog
        When I send a GET request to "/dogs/1"
        Then The response code should be 200
        Then The response is JSON
        And The response should contain JSON '{"name":"Fido"}'

    Scenario: Creating a new dog
        Given I send the following parameters:
            | name  |
            | Lizzy |
        When I send a POST request to "/dogs"
        Then The response code should be 201
        Then The response is JSON
        And The response should contain JSON '{"id":"3"}'
        When I send a GET request to "/dogs/3"
        Then The response should contain JSON '{"name":"Lizzy"}'

    Scenario: Updating an existing dog
        Given I send the following parameters:
            | name  |
            | Lucca |
        When I send a PUT request to "/dogs/1"
        Then The response code should be 204
        When I send a GET request to "/dogs/1"
        Then The response should contain JSON '{"name":"Lucca"}'

    Scenario: Deleting a dog
        When I send a DELETE request to "/dogs/1"
        Then The response code should be 204
        When I send a GET request to "/dogs/1"
        Then The response code should be 404
