@config.exclusion
Feature: Excluding Resource Properties
    In order to exclude a resource property from the output
    As a developer
    I need to configure the exclude option

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
        And There is a config file containing the following:
            """
            dogs:
                entity: Fortune\Test\Entity\Dog
                exclude: [name]
            """

    Scenario: Excluding a single property
        When I send a GET request to "/dogs/1"
        Then The response should contain JSON:
            """
            {"id":"1"}
            """
        And The response should not contain key "name"

    Scenario: Excluding a property when accessing collection
        When I send a GET request to "/dogs"
        Then The response should contain JSON:
            """
            [{"id":"1"}]
            """
