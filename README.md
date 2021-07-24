Kawaii - Gherkin Formatter
==========================

*This package is a fork of the awesome [malukenho/kawaii-gerkin](https://github.com/malukenho/kawaii-gherkin) package made by Jefersson NATHAN ([@malukenho](http://github.com/malukenho)).*

**Kawaii** is a small tool to fix and verify gherkin code style. 

### Installing

```sh
$ composer require --dev pedrotroller/kawaii
```

### Analyzing code

To analyze code style, simple run:

```sh
$ vendor/bin/kawaii check [--align [right|left]] <directory>
```

### Fixing code

To fix code style, simple run:

```sh
$ vendor/bin/kawaii fix [--align [right|left]] <directory>
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

### Maintainer

- Pierre PLAZANET ([@pedrotroller](http://github.com/pedrotroller))
