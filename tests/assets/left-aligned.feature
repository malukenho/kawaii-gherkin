# Nussa sinhora
@users @another-feature @another-tag
Feature: User registration
    In order to order products
    As a visitor
    I need to be able to create an account in the store

    Background: Nice Background
        Given store has default configuration
          And there are following users:
            | email       | password             |
            | bar@bar.com | foo1sasdasdasdadsasd |

    @javascript @bug-features
#        Comment
    Scenario: Successfully creating account in store
        Given I am on the store homepage
        And I follow "Register"
        When I fill in the following:
            | First name | John |
            | Last name  | Doe  |
        And I press "Register"
        Then I should see "Welcome"
        And I should see "Logout"
