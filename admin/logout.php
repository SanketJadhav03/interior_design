<?php
session_start();
session_destroy();
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/interior_design/admin/authentication/'; 
header("Location: $base_url");
?>