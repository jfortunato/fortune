@resource.parents
Feature: Resource Parents
    In order to request sub-resources
    As a user of this library
    I need to make the correct API calls

    Background:
        Given The following dogs exist:
            | id | name |
            | 1  | Fido |
        And The following puppies exist:
            | id | name  |
            | 1  | Daisy |
            | 2  | Lucca |
            | 3  | Lizzy |
        And The puppy "Daisy" belongs to dog "Fido"
        And The puppy "Lizzy" belongs to dog "Fido"

    @target
    Scenario: Getting a list of all puppies
        When I send a GET request to "/dogs/1/puppies"
        Then The response code should be 200
        Then The response is JSON
        And The response should contain JSON '[{"name":"Daisy"}, {"name":"Lizzy"}]'
