<?php
/*
UPDATE THIS DOCUMENT with your info
Move one level above your webroot
Update links if need
for the _URL_ROOT values and BOOTSTRAP_PATH, provide a full URL including
a trailing slash, e.g., http://yourdomain.com/
*/
$myhost = $_SERVER["SERVER_ADDR"];
if ($myhost == "your.local.ip") {
	define("DATABASE_HOST", "localhost");
	define("DATABASE_USERNAME", "");
	define("DATABASE_PASSWORD", "");
	define("QUOTES_DB", "cruelcou_quotes");
	define("DATABASE_NAME", "cruelcou_family");
	define("BOOTSTRAP_PATH", "");
	define("ENVIRON", "DEV");
	define("FAMILY_URL_ROOT", "");
	define("SITE_URL_ROOT", "");
} else {
	//production connection info 7/27/12
	define("DATABASE_HOST", "localhost");
	define("DATABASE_USERNAME", "cruelcou_family");
	define("DATABASE_PASSWORD", "");
	define("QUOTES_DB", "cruelcou_quotes");
	define("DATABASE_NAME", "cruelcou_family");
	define("BOOTSTRAP_PATH", "");
	define("ENVIRON", "PROD");
	define("FAMILY_URL_ROOT", "");
	define("SITE_URL_ROOT", "");
}
