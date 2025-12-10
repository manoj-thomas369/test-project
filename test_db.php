<?php
require 'db.php';

$stmt = $pdo->query("SELECT username FROM users");
$rows = $stmt->fetchAll();

echo "<pre>";
print_r($rows);
echo "</pre>";