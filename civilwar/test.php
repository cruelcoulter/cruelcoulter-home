
<?php 
function getactivestatus($thisname, $thislink)
{
	$thisname = str_replace("/", "", $thisname);

	if ($thisname == $thislink)
	{
		$thisclass = "active";
	}
	else {
		$thisclass = "nomatch";
	}
	return $thisclass;
}

$activestatus = getactivestatus($_SERVER['PHP_SELF'], "test1.php");


?>
<p>activestatus <?php echo $activestatus; ?></p>

