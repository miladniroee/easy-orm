<?php

echo "WARNING: running this test would update, delete from and insert into database.\n";
echo "Are you sure you want to continue?\n";
echo "Type 'yes' or 'y' to continue: ";

$handle = fopen("php://stdin","r");
$line = trim(fgets($handle));

if($line !== 'yes' && $line !== 'y'){
    echo "Testing stopped.\n";
    exit;
}

//configuration for database
$_ENV['DB_HOST'] = "localhost";
$_ENV['DB_PORT'] = "3306";
$_ENV['DB_USER'] = "root";
$_ENV['DB_PASS'] = "12345";
$_ENV['DB_NAME'] = "telegram_bot";