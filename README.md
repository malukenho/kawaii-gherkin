Kawaii Gherkin
==============

**Kawaii Gherkin** is a small tool to fix gherkin code styles. 

### Installing

```sh
$ composer require --dev malukenho/kawaii-gherkin:dev-master
```

### Fixing code

To fix code style, simple run:

```sh
$ vendor/bin/kawaii-gherkin kawaii:gherkin <directory>
```

# Example

### Before

```gherkin
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
```

### After

```gherkin
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
```

### Author

- Jefersson Nathan ([@malukenho](http://github.com/malukenho))
