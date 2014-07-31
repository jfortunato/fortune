@resource
Feature: Resource
    In order to keep logic DRY and simplify CRUD for a resource
    As an implementer of this library
    I need make the correct API calls

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
            | 2  | Daisy |

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
