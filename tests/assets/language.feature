# language: fr
@users @another-feature @another-tag
Fonctionnalité: User registration
    In order to order products
    As a visitor
    I need to be able to create an account in the store

    Contexte: Nice Background
        Soit store has default configuration
          Et there are following users:
            | email       | password             |
            | bar@bar.com | foo1sasdasdasdadsasd |

    @javascript @bug-features
#        Comment
    Scénario: Successfully creating account in store
        Soit I am on the store homepage
        Et I follow "Register"
        Quand I fill in the following:
            | First name | John |
            | Last name  | Doe  |
        Et I press "Register"
        Alors I should see "Welcome"
        Et I should see "Logout"
