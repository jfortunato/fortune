@repository.doctrine
Feature: Doctrine Repository
    In order to do CRUD with doctrine
    As the creator of this library
    I need to make sure the doctrine driver works

    Background:
        Given The repository is created using the "Fortune\Test\Entity\Dog" resource
        And The following dogs exist:
            | id | name  |
            | 1  | Fido  |
            | 2  | Daisy |

    Scenario: Finding all of a resource
        When I use the "findAll" method with arguments:
            | |
        Then The result should be an array of "Fortune\Test\Entity\Dog" objects
        And The objects should have the following ids:
            | 1  | 2 |

    Scenario: Finding a single resource
        When I use the "find" method with arguments:
            | 1 |
        Then The result should be a "Fortune\Test\Entity\Dog" object

    Scenario: Creating a resource with valid input
        When I use the "create" method with the following input:
            | name |
            | Test |
        Then The result should be a "Fortune\Test\Entity\Dog" object
        When I use the "find" method with arguments:
            | 3 |
        Then The result should be a "Fortune\Test\Entity\Dog" object

    #Scenario: Creating a resource with invalid input
        #When I use the "create" method with the following input:
            #| foo |
            #| bar |
        #Then I should get an "InvalidArgument" exception
