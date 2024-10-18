<?php
$password = '123456';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Generated hash: " . $hash . "\n";

$verify = password_verify($password, $hash);
echo "Verification result: " . ($verify ? 'true' : 'false') . "\n";

$manual_verify = (crypt($password, $hash) === $hash);
echo "Manual verification result: " . ($manual_verify ? 'true' : 'false') . "\n";

