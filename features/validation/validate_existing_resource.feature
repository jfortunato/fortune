@validation.existing
Feature: Valdating input for existing resource
    In order to validate input for a resource that exists
    As a developer
    I only need to send the fields that require updating

    # dog_id needs to be changed to dog for doctrine
    @doctrine
    Scenario: Passing validation when updating a single required field but not another
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
        And The following puppies exist:
            | id | name  |
            | 1  | Daisy |
        And The puppy "Daisy" belongs to dog "Fido"
        And There is a config file containing the following:
            """
            dogs:
                entity: Fortune\Test\Entity\Dog
            puppies:
                entity: Fortune\Test\Entity\Puppy
                parent: dogs
                validation:
                    name: required
                    dog_id: required
            """
        And I send the following parameters:
            | name  |
            | Lizzy |
        When I send a PUT request to "/dogs/1/puppies/1"
        Then The response code should be 204
