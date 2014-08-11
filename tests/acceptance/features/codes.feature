@resource.codes
Feature: Response Codes
    In order to get response feedback from the API
    As a client
    I need to send requests to the API

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
            | 2  | Daisy |

    Scenario Outline: 200 OK - when requesting to view resource
        When I send a GET request to "<url>"
        Then The response code should be 200
    Examples:
            | url     |
            | /dogs   |
            | /dogs/1 |
            | /dogs/2 |

    Scenario: 201 CREATED - when creating a new resource
        Given I send the following parameters:
            | name  |
            | Lizzy |
        When I send a POST request to "/dogs"
        Then The response code should be 201

    Scenario: 204 NO CONTENT - when updating an existing resource
        Given I send the following parameters:
            | name  |
            | Lucca |
        When I send a PUT request to "/dogs/1"
        Then The response code should be 204

    Scenario: 204 NO CONTENT - when deleting a resource
        When I send a DELETE request to "/dogs/1"
        Then The response code should be 204

    Scenario: 400 BAD REQUEST - when creating resource with bad input parameters
        Given I send the following parameters:
            | foo |
            | bar |
        When I send a POST request to "/dogs"
        Then The response code should be 400

    Scenario: 404 NOT FOUND - when individual resource doesn't exist
        When I send a GET request to "/dogs/3"
        Then The response code should be 404

    Scenario: 404 NOT FOUND - when trying to update resource that doesn't exist
        Given I send the following parameters:
            | name  |
            | Lucca |
        When I send a PUT request to "/dogs/3"
        Then The response code should be 404

    Scenario Outline: 403 FORBIDDEN - when login is required
        Given I am not logged in
        And The resource requires authentication
        When I send a <method> request to "<url>"
        Then The response code should be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: 403 FORBIDDEN - when role is required
        Given I am logged in
        But I dont have a role
        And The resource requires role "admin"
        When I send a <method> request to "<url>"
        Then The response code should be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: 403 FORBIDDEN - when owner is required
        Given I am not logged in
        And The resource requires owner for access
        When I send a <method> request to "<url>"
        Then The response code should be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |
