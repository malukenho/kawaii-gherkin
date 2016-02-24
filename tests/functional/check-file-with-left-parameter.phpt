--TEST--
Check file aligned to right
--FILE--
<?php

require __DIR__ . '/init.php';

$kawaiiGherkinCheck('--align=right tests/assets');

?>
--EXPECTF--
Finding files on tests/assets

Wrong style: %A/tests/assets/left-aligned.feature
--- Original
+++ Expected
-        And I follow "Register"
-        When I fill in the following:
+          And I follow "Register"
+         When I fill in the following:
-        And I press "Register"
-        Then I should see "Welcome"
-        And I should see "Logout"
+          And I press "Register"
+         Then I should see "Welcome"
+          And I should see "Logout"
