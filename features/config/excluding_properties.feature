@config.exclusion
Feature: Excluding Resource Properties
    In order to exclude a resource property from the output
    As a developer
    I need to configure the exclude option

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |

    # only works with doctrine as of now
    @skip
    Scenario: Excluding a single property
        Given There is a config file containing the following:
            """
            dogs:
                entity: Fortune\Test\Entity\Dog
                exclude: [name]
            """
        When I send a GET request to "/dogs/1"
        Then The response should contain JSON:
            """
            {"id":"1"}
            """
        And The response should not contain key "name"
