<?php
// Simple test to see if POST data is being received
error_log("==========================================");
error_log("TEST PROFILE UPDATE - " . date('Y-m-d H:i:s'));
error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("POST data: " . print_r($_POST, true));
error_log("==========================================");

echo "<h1>Profile Update Test</h1>";
echo "<p><strong>REQUEST_METHOD:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<h2>POST Data:</h2>";
echo "<pre>" . print_r($_POST, true) . "</pre>";
echo "<p><a href='/jacarandalibraryms/profile'>Back to Profile</a></p>";
?>
