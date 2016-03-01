Feature: Test Welcome page
  Scenario Outline:
      When I navigate in <locale> language
      Then I should see "<message>"

      Examples:
        | locale | message                           |
        | fr     | Bienvenue %USERNAME%.             |
        | de     | Herzlich Willkommen %USERNAME%.   |
        | ar     | مرحبا %USERNAME%                  |
        | ja     | ようこそ %USERNAME%                |
