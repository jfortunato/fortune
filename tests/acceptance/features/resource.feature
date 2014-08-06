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

    Scenario: Getting Not Found error when dog doesn't exist
        When I send a GET request to "/dogs/3"
        Then The response code should be 404

    Scenario: Getting Not Found error when trying to update dog that doesnt exist
        Given I send the following parameters:
            | name  |
            | Lucca |
        When I send a PUT request to "/dogs/3"
        Then The response code should be 404

    Scenario: Getting Bad Request error when creating new dog with bad parameters
        Given I send the following parameters:
            | foo |
            | bar |
        When I send a POST request to "/dogs"
        Then The response code should be 400

    Scenario Outline: Getting Access Denied error when login is required
        Given I am not logged in
        And The "Fortune\Test\Entity\Dog" entity requires authentication
        When I send a <method> request to "<url>"
        Then The response code should be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Not getting Access Denied error when login is required and we are logged in
        Given I am logged in
        And The "Fortune\Test\Entity\Dog" entity requires authentication
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Getting Access Denied error when role is required and we have no role
        Given I am logged in
        But I dont have a role
        And The "Fortune\Test\Entity\Dog" resource requires role "admin"
        When I send a <method> request to "<url>"
        Then The response code should be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Getting Access Denied error when role is required and we have different role
        Given I am logged in
        And I have the role "foo"
        And The "Fortune\Test\Entity\Dog" resource requires role "admin"
        When I send a <method> request to "<url>"
        Then The response code should be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Not getting Access Denied error when role is required and we have that role
        Given I am logged in
        And I have the role "admin"
        And The "Fortune\Test\Entity\Dog" resource requires role "admin"
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario: Serializing resource without security in output
        When I send a GET request to "/dogs/1"
        Then The response should not contain key "requires_authentication"
        And The response should not contain key "requires_role"
