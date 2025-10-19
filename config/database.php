<?php
$host = '127.0.0.1';
$user = 'simasuser';
$pass = 'dedi1234';
$db   = 'simas';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB error: ".$conn->connect_error);
$conn->set_charset('utf8mb4');