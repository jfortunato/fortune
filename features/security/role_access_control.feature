@security.role
Feature: Role resource security
    In order to protect a resource that requires a role
    As a developer
    I need to configure role access control

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
                    role: admin
            """

    Scenario Outline: Denied access when role is required and we have no role
        Given I am logged in
        But I dont have a role
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

    Scenario Outline: Denied access when role is required and we have a different role
        Given I am logged in
        And I have the role "foo"
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

    Scenario Outline: Granted access when role is required and we have that role
        Given I am logged in
        And I have the role "admin"
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |
