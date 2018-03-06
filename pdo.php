<?php
//pdo.php
//12/04/13 - first release
//12/09/13 - removed closing php tag
$dsn = "mysql:host=localhost;dbname=" . DATABASE_NAME;
	try {
$link = new PDO($dsn, DATABASE_USERNAME, DATABASE_PASSWORD);
}
catch (PDOException $e)
{
	echo $e->getMessage();
}

$quotesdsn = "mysql:host=localhost;dbname=" . QUOTES_DB;
	try {
$quoteslink = new PDO($quotesdsn, DATABASE_USERNAME, DATABASE_PASSWORD);
}

catch (PDOException $e)

{

	echo $e->getMessage();

}
