@parents.multi
Feature: Multiple Resource Parents
    In order to request child resources with a deep parent structure
    As a developer
    I need to make the correct API calls

    Background:
        Given The following dogs exist:
            | id | name |
            | 1  | Fido |
        And The following puppies exist:
            | id | name  |
            | 1  | Daisy |
        And The following toys exist:
            | id | toy         |
            | 1  | tennis ball |
            | 2  | bone        |
        And The puppy "Daisy" belongs to dog "Fido"
        And The toy "tennis ball" belongs to puppy "Daisy"
        And The toy "bone" belongs to puppy "Daisy"
        And There is a config file containing the following:
            """
            dogs: ~
            puppies:
                parent: dogs
            toys:
                parent: puppies
            """

    Scenario: Getting a list of all toys with correct url
        When I send a GET request to "/dogs/1/puppies/1/toys"
        Then The response code should be 200
        Then The response is JSON
        And The response should contain JSON:
            """
            [{"toy":"tennis ball"}, {"toy":"bone"}]
            """

    Scenario: Getting a list of all toys with incorrect url
        When I send a GET request to "/dogs/999/puppies/1/toys"
        Then The response code should be 404
        And The error message should contain "Resource Not Found"

    Scenario: Getting a single toy with correct url
        When I send a GET request to "/dogs/1/puppies/1/toys/1"
        Then The response code should be 200
        Then The response is JSON
        And The response should contain JSON:
            """
            {"toy":"tennis ball"}
            """

    Scenario: Getting a single toy with incorrect url
        When I send a GET request to "/dogs/999/puppies/1/toys/1"
        Then The response code should be 404
        And The error message should contain "Resource Not Found"

    Scenario: Creating a new toy with correct url
        Given I send the following parameters:
            | toy |
            | tug |
        When I send a POST request to "/dogs/1/puppies/1/toys"
        Then The response code should be 201
        And The response should contain JSON:
            """
            {"id":"3"}
            """
        When I send a GET request to "/dogs/1/puppies/1/toys/3"
        Then The response should contain JSON:
            """
            {"toy":"tug"}
            """

    Scenario: Creating a new toy with incorrect url
        Given I send the following parameters:
            | toy |
            | tug |
        When I send a POST request to "/dogs/999/puppies/1/toys"
        Then The response code should be 404
        And The error message should contain "Resource Not Found"

    Scenario: Updating a toy with correct url
        Given I send the following parameters:
            | toy |
            | tug |
        When I send a PUT request to "/dogs/1/puppies/1/toys/1"
        Then The response code should be 204
        When I send a GET request to "/dogs/1/puppies/1/toys/1"
        Then The response should contain JSON:
            """
            {"toy":"tug"}
            """

    Scenario: Updating a toy with incorrect url
        Given I send the following parameters:
            | toy |
            | tug |
        When I send a PUT request to "/dogs/999/puppies/1/toys/1"
        Then The response code should be 404
        And The error message should contain "Resource Not Found"

    Scenario: Deleting a toy with correct url
        When I send a DELETE request to "/dogs/1/puppies/1/toys/1"
        Then The response code should be 204
        When I send a GET request to "/dogs/1/puppies/1/toys/1"
        Then The response code should be 404

    Scenario: Deleting a toy with incorrect url
        When I send a DELETE request to "/dogs/999/puppies/1/toys/1"
        Then The response code should be 404
