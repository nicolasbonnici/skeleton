<?php
session_start();
session_name('session');
error_reporting(-1);
// Grab microtime at load
define('FRAMEWORK_STARTED', microtime(true));

// @see boostrap application
require_once __DIR__ . '/../Library/Core/Bootstrap.php';
\Library\Core\Bootstrap::getInstance();
?>