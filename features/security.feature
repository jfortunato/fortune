@security
Feature: Resource Security
    In order to protect a resource
    As a user of this library
    I need to configure security correctly

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
            puppies:
                entity: Fortune\Test\Entity\Puppy
                validator: Fortune\Test\Validator\PuppyValidator
                parent: dogs
            """

    Scenario Outline: Denied access when login is required
        Given I am not logged in
        And The resource "dogs" requires authentication
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
        And The resource "dogs" requires authentication
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Denied access when role is required and we have no role
        Given I am logged in
        But I dont have a role
        And The resource "dogs" requires role "admin"
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
        And The resource "dogs" requires role "admin"
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
        And The resource "dogs" requires role "admin"
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Denied access when owner is required and we are not the resource owner
        Given I am not logged in
        And The resource "dogs" requires owner for access
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
        And The resource "dogs" requires owner for access
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario: Denied access when resource has parent but we try to directly access the child
        Given The resource "puppies" belongs to resource "dogs"
        When I send a GET request to "/puppies"
        Then The response code should be 403

    Scenario: Serializing resource without security in output
        When I send a GET request to "/dogs/1"
        Then The response should not contain key "requires_authentication"
        And The response should not contain key "requires_role"
        And The response should not contain key "requires_owner"
