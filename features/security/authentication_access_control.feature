@security.authentication
Feature: Authentication resource security
    In order to protect a resource that requires authentication
    As a developer
    I need to configure authentication access control

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
        And There is a config file containing the following:
            """
            dogs:
                parent: ~
                entity: Fortune\Test\Entity\Dog
                validator: Fortune\Test\Validator\DogValidator
                access_control:
                    authentication: true
            """

    Scenario Outline: Denied access when login is required
        Given I am not logged in
        When I send a <method> request to "<url>"
        Then The response code should be 403
        And The error message should contain "Access Denied"
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Granted access when login is required and we are logged in
        Given I am logged in
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |
