Feature: Fix gherkin file content format

    Scenario: Fix file align right
        Given the gherkin file
          """
          @users

                          @another-feature
              @kawaii
          Feature: User registration
              In order to order products
                 As a visitor
              I need to be able to create an account in the store

              Background: Nice Background
                        Given store has default configuration
                And there are following users:
                        | email       | password |
                            | bar@bar.com | foo1sasdasdasdadsasd     |
               And the following customers exist:
                           | email              |
                   | customer@email.com |
               And the following zones are defined:
                          | name         | type    | members |
                           | Poland       | country | Poland  |
               And the following orders exist:
                   | customer                | address                                        |
                   | customer@email.com      | Jan Kowalski, Wawel 5 , 31-001, Kraków, Poland |
          """
         When I run fix --align right
         Then the file should contain
          """
          @users @another-feature @kawaii
          Feature: User registration
              In order to order products
              As a visitor
              I need to be able to create an account in the store

              Background: Nice Background
                  Given store has default configuration
                    And there are following users:
                      | email       | password             |
                      | bar@bar.com | foo1sasdasdasdadsasd |
                    And the following customers exist:
                      | email              |
                      | customer@email.com |
                    And the following zones are defined:
                      | name   | type    | members |
                      | Poland | country | Poland  |
                    And the following orders exist:
                      | customer           | address                                        |
                      | customer@email.com | Jan Kowalski, Wawel 5 , 31-001, Kraków, Poland |
          """

    Scenario: Fix file align left
        Given the gherkin file
          """
          @users

                          @another-feature
              @kawaii
          Feature: User registration
              In order to order products
                 As a visitor
              I need to be able to create an account in the store

              Background: Nice Background
                        Given store has default configuration
                And there are following users:
                        | email       | password |
                            | bar@bar.com | foo1sasdasdasdadsasd     |
               And the following customers exist:
                           | email              |
                   | customer@email.com |
               And the following zones are defined:
                          | name         | type    | members |
                           | Poland       | country | Poland  |
               And the following orders exist:
                   | customer                | address                                        |
                   | customer@email.com      | Jan Kowalski, Wawel 5 , 31-001, Kraków, Poland |
          """
         When I run fix --align left
         Then the file should contain
          """
          @users @another-feature @kawaii
          Feature: User registration
              In order to order products
              As a visitor
              I need to be able to create an account in the store

              Background: Nice Background
                  Given store has default configuration
                  And there are following users:
                      | email       | password             |
                      | bar@bar.com | foo1sasdasdasdadsasd |
                  And the following customers exist:
                      | email              |
                      | customer@email.com |
                  And the following zones are defined:
                      | name   | type    | members |
                      | Poland | country | Poland  |
                  And the following orders exist:
                      | customer           | address                                        |
                      | customer@email.com | Jan Kowalski, Wawel 5 , 31-001, Kraków, Poland |
          """

    Scenario: Fix file align right with examples
        Given the gherkin file
          """
          @users

                          @another-feature
              @kawaii
          Feature: User registration
              In order to order products
                 As a visitor
              I need to be able to create an account in the store

              Scenario Outline: Scenario 1
                  Given somthing
              When something else
                  Then the result

          Examples:
              | test |
              | 1 |
              | 23 |

          Scenario: other scenario
              Given something
              When something else
              Then the result
          """
         When I run fix --align right
         Then the file should contain
          """
          @users @another-feature @kawaii
          Feature: User registration
              In order to order products
              As a visitor
              I need to be able to create an account in the store

              Scenario Outline: Scenario 1
                  Given somthing
                   When something else
                   Then the result

                  Examples:
                    | test |
                    | 1    |
                    | 23   |

              Scenario: other scenario
                  Given something
                   When something else
                   Then the result
          """


