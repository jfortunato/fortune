@security.custom
Feature: Custom resource security
    In order to protect a resource in a custom manner
    As a developer
    I need to create a class that extends a Bouncer and configure it

    Background:
        Given The following dogs exist:
            | id | name  |
            | 1  | Fido  |
        And There is a config file containing the following:
            """
            dogs:
                access_control:
                    custom: [Fortune\Test\Generated\CustomBouncer]
            """

    Scenario Outline: Denied access when custom bouncer denies access
        Given The class file "tests/acceptance/slim-framework/src/Generated/CustomBouncer.php" contains:
            """
            <?php

            namespace Fortune\Test\Generated;

            use Fortune\Security\Bouncer\Bouncer;

            class CustomBouncer extends Bouncer
            {
                public function check($method, $resource, $identifiers = null)
                {
                    return false;
                }
            }
            """
        When I send a <method> request to "<url>"
        Then The response code should be 403
        And The error message should contain "Access Denied"
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |

    Scenario Outline: Granted access when custom bouncer grants access
        Given The class file "tests/acceptance/slim-framework/src/Generated/CustomBouncer.php" contains:
            """
            <?php

            namespace Fortune\Test\Generated;

            use Fortune\Security\Bouncer\Bouncer;

            class CustomBouncer extends Bouncer
            {
                public function check($method, $resource, $identifiers = null)
                {
                    return true;
                }
            }
            """
        When I send a <method> request to "<url>"
        Then The response code should not be 403
    Examples:
            | method | url     |
            | GET    | /dogs   |
            | GET    | /dogs/1 |
            | POST   | /dogs   |
            | PUT    | /dogs/1 |
            | DELETE | /dogs/1 |
