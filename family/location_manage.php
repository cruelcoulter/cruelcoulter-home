<?php  
session_start();
require 'functions.php';
if ( ! AmILoggedIn()) {
	$thispage = substr($_SERVER['PHP_SELF'], 1);
	header("Location:login.php?backto=" . $thispage);
}
require '../../db_config.php';
/* 
09/18/13 - create
10/06/13 - added navheader include
10/24/13 - converted to pdo
11/27/13 - moved to family folder. Format to match other files.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8" />
<!-- Bootstrap core CSS -->
<?PHP require 'include_fonts_css.php'; ?>

<style type="text/css">
.container {
padding-top: 30px;
margin-top: 30px;
}
</style>
<title>Manage city/state/county/country</title>
<?php 
if (ENVIRON == "PROD") {
	include 'google_script.php';
}
?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo BOOTSTRAP_PATH; ?>assets/js/html5shiv.js"></script>
      <script src="<?php echo BOOTSTRAP_PATH; ?>assets/js/respond.min.js"></script>
    <![endif]-->
<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed"
	href="<?php echo BOOTSTRAP_PATH; ?>assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon"
	href="../includes/favicon.png">
</head>
<body>
	<?php
	
	include 'navheader.php';
	//$link = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD)
	//or die("<p>Error connecting: " . mysqli_error($link) . "</p>");
	
	try {
		$pdolink = new PDO('mysql:host=localhost;dbname=cruelcou_family', DATABASE_USERNAME,DATABASE_PASSWORD);
		}
	catch (PDOException $e)
	{
		echo $e->getMessage();
	}
	//mysqli_select_db($link, DATABASE_NAME)
	//or die("<p>Error selecting: " . mysqli_error($link) . "</p>");
	$country = (isset($_POST['country']) ? $_POST['country'] : "");
	$state = (isset($_POST['state']) ? $_POST['state'] : "");
	$county = (isset($_POST['county']) ? $_POST['county'] : "");
	$city_name = (isset($_POST['city_name']) ? $_POST['city_name'] : "");
	$city_twp = (isset($_POST['city_twp']) ? $_POST['city_twp'] : "");
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (strlen($country)) {
//$countryqry = "insert into country (country) VALUES ('" . $country . "')";
$countrypdo = $pdolink->prepare('insert into country (country) VALUES (:country)');
$countrypdo->execute(array(':country' => $country));
if ($countrypdo) {
	echo 'Country added';
		} 
		else 
		{
	print_r($countrypdo->errorInfo());
		}
}
/* 
 * echo "<pre>Debug: " . $countryqry . "</pre>\m";
$countryqryresult = mysqli_query($link, $countryqry);
if ( false===$countryqryresult ) {
  printf("error: %s\n", mysqli_error($link));
}
else {
  echo 'done.';
}
 */

if (strlen($state)){
//$stateqry = "insert into state (state) VALUES ('" . $state . "')";
$statepdo = $pdolink->prepare("insert into state (state) VALUES (:state)");
$statepdo->bindParam(':state', $state);
$statepdo->execute();
if ($statepdo) {
	echo 'State added';
}
else
{
	print_r($statepdo->errorInfo());
}

}
/* echo "<pre>Debug:" . $stateqry . "</pre>\m";
$stateqryresult = mysqli_query($link, $stateqry);
if ( false===$stateqryresult ) {
  printf("error: %s\n", mysqli_error($link));
}
else {
  echo 'done.';
}
}
 */
if (strlen($county)) {
$countypdo = $pdolink->prepare("INSERT INTO county (county) VALUES (:county)");
$countypdo->bindParam(':county', $county);
$countypdo->execute();
if ($countypdo) {
	echo 'County added';
}
else
{
	print_r($countypdo->errorInfo());
}
/* $countyqry = "insert into county (county) VALUES ('" . $county . "')";
echo "<pre>Debug:" . $countyqry . "</pre>\m";
$countyqryresult = mysqli_query($link, $countyqry);
if ( false===$countyqryresult ) {
  printf("error: %s\n", mysqli_error($link));
}
else {
  echo 'done.';
}
 */
}
if (strlen($city_name)) {
$citypdo = $pdolink->prepare("insert into city_twp (city_name, city_twp) VALUES (:city_name, :city_twp)");
$citypdo->bindParam(':city_name', $city_name);
$citypdo->bindParam(':city_twp', $city_twp);
$citypdo->execute();
if ($citypdo) {
	echo 'City added';
}
else
{
	print_r($citypdo->errorInfo());
}

/* $cityqry = "insert into city_twp (city_name, city_twp) VALUES ('" . $city_name . "', '" . $city_twp . "')";
echo "<pre>Debug:" . $cityqry . "</pre>\m";
$cityqryresult = mysqli_query($link, $cityqry);
if ( false===$cityqryresult ) {
  printf("error: %s\n", mysqli_error($link));
}
else {
  echo 'done.';
}
}
 */
}
	$pdolink = null;
	} //if ($_SERVER['REQUEST_METHOD'] === 'POST')
		?>
	<div class="container">
		<form action="location_manage.php" method="post">
			<div class="form-group">
				<label for="country">Add Country</label> <input type="text"
					class="form-control" id="country" placeholder="Country"
					name="country">
			</div>
			<div class="form-group">
				<label for="state">Add state</label> <input type="text"
					class="form-control" id="state" placeholder="State" name="state">
			</div>
			<div class="form-group">
				<label for="county">Add county</label> <input type="text"
					class="form-control" id="county" placeholder="County" name="county">
			</div>
			<div class="form-group">
				<label for="city_name">Add City/Township</label> <input type="text"
					class="form-control" id="city_name" placeholder="City or Township"
					name="city_name">
				<div class="radio">
					<label> <input type="radio" name="city_twp" id="city" value="city">City
					</label>
				</div>
				<div class="radio">
					<label> <input type="radio" name="city_twp" id="township"
						value="township">Township
					</label>
				</div>
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
	<?php include 'include_js.php'; ?>
</body>
</html>
