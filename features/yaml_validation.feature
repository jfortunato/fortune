@yaml_validation
Feature: YAML Validation
    In order to use yaml for validation
    As a developer
    I need to configure validation correctly

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
        And There is a config file containing the following:
            """
            dogs:
                parent: ~
                entity: Fortune\Test\Entity\Dog
                validation:
                    name: required
            """

    Scenario: Passing validation
        Given I send the following parameters:
            | name  |
            | Lizzy |
        When I send a POST request to "/dogs"
        Then The response code should be 201

    Scenario: Failing validation
        Given I send the following parameters:
            | foo   |
            | Lizzy |
        When I send a POST request to "/dogs"
        Then The response code should be 400

    Scenario: Passing with multiple rules
        Given There is a config file containing the following:
            """
            dogs:
                parent: ~
                entity: Fortune\Test\Entity\Dog
                validation:
                    name: required|lengthMin:3
            """
        And I send the following parameters:
            | name  |
            | Lizzy |
        When I send a POST request to "/dogs"
        Then The response code should be 201

    Scenario: Failing with multiple rules
        Given There is a config file containing the following:
            """
            dogs:
                parent: ~
                entity: Fortune\Test\Entity\Dog
                validation:
                    name: required|lengthMin:3
            """
        And I send the following parameters:
            | name |
            | Bo   |
        When I send a POST request to "/dogs"
        Then The response code should be 400
