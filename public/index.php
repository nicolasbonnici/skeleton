<?php
session_start();
session_name('ssession');
// Grab microtime at load
define('FRAMEWORK_STARTED', microtime(true));

// @see boostrap application
require_once __DIR__ . '/../app/Bootstrap.php';
Bootstrap::getInstance();

?>
