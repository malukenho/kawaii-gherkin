--TEST--
File have to be checked using unix line endings
--FILE--
<?php

require __DIR__ . '/init.php';

$kawaiiGherkinCheck('tests/assets/issue-3.feature');

?>
--EXPECTF--
Finding files on tests/assets/issue-3.feature

Wrong style: %A/tests/assets/issue-3.feature
--- Original
+++ Expected
-  Scenario Outline:
-      When I navigate in <locale> language
-      Then I should see "<message>"
-      Examples:
-        | locale | message                           |
-        | fr     | Bienvenue %USERNAME%.             |
-        | de     | Herzlich Willkommen %USERNAME%.   |
-        | ar     | مرحبا %USERNAME%                  |
-        | ja     | ようこそ %USERNAME%                |
+    Scenario Outline:
+        When I navigate in <locale> language
+        Then I should see "<message>"
+
+        Examples:
+          | locale | message                         |
+          | fr     | Bienvenue %USERNAME%.           |
+          | de     | Herzlich Willkommen %USERNAME%. |
+          | ar     | مرحبا %USERNAME%                |
+          | ja     | ようこそ %USERNAME%                 |
