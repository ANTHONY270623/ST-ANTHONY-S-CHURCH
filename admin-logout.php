<?php
require_once __DIR__ . '/admin_auth.php';
admin_logout();
header('Location: admin-login.php');
exit;
?>
