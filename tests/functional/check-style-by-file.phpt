--TEST--
Check style of one specific file
--FILE--
<?php

require __DIR__ . '/init.php';

$kawaiiGherkinCheck('tests/assets/left-aligned.feature');

?>
--EXPECTF--
Finding files on tests/assets

Wrong style: %A/tests/assets/left-aligned.feature
--- Original
+++ Expected
-          And there are following users:
+        And there are following users:
