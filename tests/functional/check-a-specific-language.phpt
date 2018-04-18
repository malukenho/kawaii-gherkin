--TEST--
Check a specific language
--FILE--
<?php

require __DIR__ . '/init.php';

$kawaiiGherkinCheck('tests/assets/language.feature');

?>
--EXPECTF--

Finding files on tests/assets/language.feature

Wrong style: %A/tests/assets/language.feature
--- Original
+++ Expected
+# language: fr
-          Et there are following users:
+        Et there are following users:
