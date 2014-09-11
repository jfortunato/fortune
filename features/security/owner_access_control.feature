@security.owner
Feature: Owner resource security
    In order to protect a resource that requires an owner
    As a developer
    I need to configure owner access control

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
                    owner: true
            """

    Scenario Outline: Denied access when owner is required and we are not the resource owner
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

    Scenario Outline: Granted access when owner is required and we are the owner
        Given I am logged in
        And I am the owner of the resource
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |
