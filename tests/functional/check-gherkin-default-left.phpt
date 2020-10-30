--TEST--
Check file with default diff
--FILE--
<?php

require __DIR__ . '/init.php';

$kawaiiGherkinCheck('tests/assets/left-aligned.feature');

?>
--EXPECTF--
Finding files on tests/assets/left-aligned.feature

Wrong style: %A/tests/assets/left-aligned.feature
--- Original
+++ Expected
@@ @@
 
     Background: Nice Background
         Given store has default configuration
-          And there are following users:
+        And there are following users:
             | email       | password             |
             | bar@bar.com | foo1sasdasdasdadsasd |
