@valitron
Feature: Valitron Validation
    In order to validate input with valitron
    As a user of the valitron driver
    I need extend and use the driver correctly

    Background:
        Given I have extended the ValitronResourceValidator

    Scenario: Passing simple validation
        Given I have the following input:
            | foo |
            | bar |
        And The input "foo" is required
        When I validate the input
        Then The input should be valid

    Scenario: Failing simple validation
        Given I have the following input:
            | foo |
            |     |
        And The input "foo" is required
        When I validate the input
        Then The input should be invalid
