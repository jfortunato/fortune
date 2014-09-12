@config.minimum
Feature: Minimum Configuration
    In order to keep configuration simple
    As a developer
    I can configure the minimum amount of options

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |

    Scenario: Supplying a minimum config file
        Given There is a config file containing the following:
            """
            dogs: ~
            """
        When I send a GET request to "/dogs"
        Then The response should contain JSON:
            """
            [{"name":"Fido"}]
            """

    Scenario: Only specifying parent
        Given The following puppies exist:
            | id | name  |
            | 1  | Daisy |
        And The puppy "Daisy" belongs to dog "Fido"
        And There is a config file containing the following:
            """
            dogs: ~
            puppies:
                parent: dogs
            """
        When I send a GET request to "/dogs/1/puppies"
        Then The response should contain JSON:
            """
            [{"name":"Daisy"}]
            """
