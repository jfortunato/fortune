@config
Feature: Configuration
    In order to setup resources for use
    As a developer using this library
    I need to have the correct configuration

    Scenario: Getting a configured resource
        Given There is a config file containing the following:
            """
            dogs:
                entity: Foo
                validator: FooValidator
                access_control: ~
            """
        When I parse the config file
        And I send the parsed config to a Configuration object
        Then The Configuration should contain the "dogs" ResourceConfiguration
        And The ResourceConfiguration's "EntityClass" should be "Foo"
        And The ResourceConfiguration's "ValidatorClass" should be "FooValidator"
