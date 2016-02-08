Kawaii Gherkin
==============

[![Build Status](https://travis-ci.org/malukenho/kawaii-gherkin.svg?branch=master)](https://travis-ci.org/malukenho/kawaii-gherkin)
[![Code Coverage](https://scrutinizer-ci.com/g/malukenho/kawaii-gherkin/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/malukenho/kawaii-gherkin/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/malukenho/kawaii-gherkin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/malukenho/kawaii-gherkin/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/malukenho/kawaii-gherkin/v/stable)](https://packagist.org/packages/malukenho/kawaii-gherkin)
[![Total Downloads](https://poser.pugx.org/malukenho/kawaii-gherkin/downloads)](https://packagist.org/packages/malukenho/kawaii-gherkin)
[![Latest Unstable Version](https://poser.pugx.org/malukenho/kawaii-gherkin/v/unstable)](https://packagist.org/packages/malukenho/kawaii-gherkin)
[![License](https://poser.pugx.org/malukenho/kawaii-gherkin/license)](https://packagist.org/packages/malukenho/kawaii-gherkin)

**Kawaii Gherkin** is a small tool to fix and verify gherkin code style. 

### Installing

```sh
$ composer require --dev malukenho/kawaii-gherkin
```

### Analyzing code

To analyze code style, simple run:

```sh
$ vendor/bin/kawaii gherkin:check [--align [right|left]] <directory>
```

### Fixing code

To fix code style, simple run:

```sh
$ vendor/bin/kawaii gherkin:fix [--align [right|left]] <directory>
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
