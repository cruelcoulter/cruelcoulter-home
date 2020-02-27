<?php
//functions.php
//11/26/13 - baseline
//12/04/13 - added pdo functions
//12/09/13 - removed closing php tag
//09/22/14 - add AmIAdmin function
//11/23/14 - added checkpage function
//12/01/14 - added checkaccess funtion. NOT DONE
//02/11/18 - accomodate seo friendly URLs
function AmILoggedIn() {
	if(isset($_SESSION['IsLoggedIn']) && $_SESSION['IsLoggedIn']) {
		return true;
	} else {
		return false;
	}
}
function checkAccess($tablename,$primarykey) {
  switch ($tablename) {
     case "album":
     $accessstring = "select album_id from album where album_id = :primarykey and access_level = 'PUBLIC'";
     //LEFT OFF
  }

}
function AmIAdmin() {
	if(isset($_SESSION['User_Role']) && $_SESSION['User_Role'] == "ADMIN") {
		return true;
	} else {
		return false;
	}
}
//Added 11/26/13
function logout()
{
	$_SESSION = array(); //destroy all of the session variables
	session_destroy();
}
    function checkpage($currpage, $linkpage) {
    if($currpage == $linkpage) {
      return " class=\"nav-item active\"";
    }  else {
      return " class=\"nav-item\"";
    }
    }
function getlastname($family_id, $link) {
    $lastqry = "select family_name from family where family_id = " . $family_id;
    $lastres = mysqli_query($link, $lastqry);
    $row = mysqli_fetch_array($lastres);
    $family_name = " " . $row['family_name'];
    return $family_name;
}
function getlastnamepdo($family_id, $link) {
	$lastqry = "select family_name from family where family_id = :family_id";
	$lastnameqry = $link->prepare($lastqry);
	$lastnameqry->bindParam(':family_id', $family_id, PDO::PARAM_INT);
	$lastnameqry->execute();
	$row = $lastnameqry->fetch();
	$family_name = " " . $row['family_name'];
	return $family_name;
}
//added 4/7/15
function getmarriednamepdo($married_name_id, $link) {
  if (is_numeric($married_name_id)) {
	$lastqry = "select family_name from family where family_id = :married_name_id";
	$lastnameqry = $link->prepare($lastqry);
	$lastnameqry->bindParam(':married_name_id', $family_id, PDO::PARAM_INT);
	$lastnameqry->execute();
	$row = $lastnameqry->fetch();
	$married_name = " " . $row['family_name'] . " ";
    } else {
      $married_name = "";
    }
	return $married_name;
}

/* this query won't work for mothers since the last name is different.
 * Added as family_name 9/19/13 */
function getfather($father_member_id, $family_id, $link) {
	$getfatherq = "select first_name, (select family_name from family where
			family_id = " . $family_id . ") as family_name from family_member where family_member_id
			= " . $father_member_id;
	$getfatherres = mysqli_query($link, $getfatherq);

	if ($getfatherres !== false ) {
		$getfatherrow = mysqli_fetch_array($getfatherres);
		$father_name = $getfatherrow['first_name'] . " " . $getfatherrow['family_name'];
	} else {
		$father_name = "";
	}

	return $father_name;
}
//12/3/13
//added paragraph wrapper 4/10/15
function getfatherpdo($father_member_id, $family_id, $link) {
	$getfatherq = "select first_name, (select family_name from family where
	family_id = :family_id) as family_name from family_member where family_member_id
	= :father_member_id";
	$getfatherres = $link->prepare($getfatherq);
	$getfatherres->bindparam(':family_id', $family_id, PDO::PARAM_INT);
	$getfatherres->bindparam(':father_member_id', $father_member_id, PDO::PARAM_INT);
	$getfatherres->execute();
	if ($getfatherres !== false ) {
		$getfatherrow = $getfatherres->fetch();
		$father_name = "<p>" . $getfatherrow['first_name'] . " " . $getfatherrow['family_name'] . "</p>";
	} else {
		$father_name = "";
	}
	return $father_name;
}
//02/24/18
//use slug instead of id
function getfatherpdoslug($father_member_id, $family_id, $link) {
	$getfatherq = "select first_name, (select family_name from family where
	family_id = :family_id) as family_name, family_member_slug from family_member where family_member_id
	= :father_member_id";
	$getfatherres = $link->prepare($getfatherq);
	$getfatherres->bindparam(':family_id', $family_id, PDO::PARAM_INT);
	$getfatherres->bindparam(':father_member_id', $father_member_id, PDO::PARAM_INT);
	$getfatherres->execute();
	if ($getfatherres !== false ) {
		$getfatherrow = $getfatherres->fetch();
		$father_link = "<p><a href=\"" . FAMILY_URL_ROOT . "family_member\\" . $getfatherrow['family_member_slug'] . "\">". $getfatherrow['first_name'] . " "
		. $getfatherrow['family_name'] . "</a></p>";
	} else {
		$father_link = "";
	}
	return $father_link;
}

/* this query won't work for mothers since the last name is different */
function getmother($mother_member_id, $family_id, $link) {
/* 	$getmotherq = "select first_name, (select family_name from family where
			family_id = " . $family_id . ") from family_member where family_member_id
			= " . $mother_member_id;
 */
	$getmotherq = "select first_name, (select family_name from family where
			family_id = (select married_name_id from family_member where family_member_id =" . $mother_member_id . ")) as married_name  from family_member where family_member_id
			= " . $mother_member_id;

	$getmotherres = mysqli_query($link, $getmotherq);
	if ($getmotherres !== false ) {
		$getmotherrow = mysqli_fetch_array($getmotherres);
		$mother_name = $getmotherrow['first_name'] . " " . $getmotherrow['married_name'];
		$mother_link = "<a href=\"" . FAMILY_URL_ROOT . "family_member\\" .
				$mother_member_id . "\">" . $mother_name . "</a>";
	} else {
		$mother_link = "";
	}
	return $mother_link;
}
//12-3-13
function getmotherpdo($mother_member_id, $family_id, $link) {
	$getmotherq = "select first_name, (select family_name from family where
	family_id = (select married_name_id from family_member where family_member_id = :mother_member_id))
	as married_name from family_member where family_member_id
	= :mother_member_id";
	$getmotherres = $link->prepare($getmotherq);
	$getmotherres->bindparam(':mother_member_id', $mother_member_id, PDO::PARAM_INT);
	$getmotherres->execute();
	if ($getmotherres !== false ) {
		$getmotherrow = $getmotherres->fetch();
		$mother_name = $getmotherrow['first_name'] . " " . $getmotherrow['married_name'];
		$mother_link = "<a href=\"" . FAMILY_URL_ROOT . "family_member\\" .
				$mother_member_id . "\">" . $mother_name . "</a>";
	} else {
		$mother_link = "";
	}
	return $mother_link;
}
//2-25-18
function getmotherpdoslug($mother_member_id, $family_id, $link) {
	$getmotherq = "select first_name, (select family_name from family where
	family_id = (select married_name_id from family_member where family_member_id = :mother_member_id))
	as married_name, family_member_slug from family_member where family_member_id
	= :mother_member_id";
	$getmotherres = $link->prepare($getmotherq);
	$getmotherres->bindparam(':mother_member_id', $mother_member_id, PDO::PARAM_INT);
	$getmotherres->execute();
	if ($getmotherres !== false ) {
		$getmotherrow = $getmotherres->fetch();
		$mother_name = $getmotherrow['first_name'] . " " . $getmotherrow['married_name'];
		$mother_link = "<a href=\"" . FAMILY_URL_ROOT . "family_member\\" .
				$getmotherrow['family_member_slug'] . "\">" . $mother_name . "</a>";
	} else {
		$mother_link = "";
	}
	return $mother_link;
}
//TODO: create buildplacenamepdo
function buildplacename($city_twp_id=null, $county_id=null, $state_id=null, $country_id=null, $link) {
	$placename = "";

	if (!is_null($city_twp_id)) {
		$query = "select concat(city_name, ' ', city_twp) as city from city_twp where city_twp_id = " . $city_twp_id;
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$placename .= $row['city'] . ', ';
	}

	if (!is_null($county_id)) {
		$query = "select county from county where county_id = " . $county_id;
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$placename .= $row['county'] . ' County, ';
	}
	if (!is_null($state_id)) {
		$query = "select state from state where state_id = " . $state_id;
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$placename .= $row['state']. ' ';
	}
	if (!is_null($country_id)) {
		$query = "select country from country where country_id = " . $country_id;
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$placename .= $row['country'];
	}

	return $placename;

}
// buildplacenamepdo 12-3-13
function buildplacenamepdo($city_twp_id=null, $county_id=null, $state_id=null, $country_id=null, $link) {
	$placename = "";
	if (!is_null($city_twp_id)) {
		$query = "select concat(city_name, ' ', city_twp) as city from city_twp where city_twp_id = :city_twp_id";
		$city_twp_res = $link->prepare($query);
		$city_twp_res->bindParam(':city_twp_id', $city_twp_id, PDO::PARAM_INT);
		$city_twp_res->execute();
		if ($city_twp_res !== false) {
			$row = $city_twp_res->fetch();
			$placename .= $row['city'] . ', ';
		}
	}

	if (!is_null($county_id)) {
		$query = "select county from county where county_id = :county_id";
		$countyres = $link->prepare($query);
		$countyres->bindParam(':county_id', $county_id, PDO::PARAM_INT);
		$countyres->execute();
		if ($countyres!== false) {
			$row = $countyres->fetch();
			$placename .= $row['county'] . ' County, ';
		}
	}
	if (!is_null($state_id)) {
		$query = "select state from state where state_id = :state_id";
		$stateres = $link->prepare($query);
		$stateres->bindParam(':state_id', $state_id, PDO::PARAM_INT);
		$stateres->execute();
		if ($stateres !== false) {
			$row = $stateres->fetch();
			$placename .= $row['state']. ' ';
		}
	}
	if (!is_null($country_id)) {
		$query = "select country from country where country_id = :country_id";
		$countryres = $link->prepare($query);
		$countryres->bindParam(':country_id', $country_id, PDO::PARAM_INT);
		$countryres->execute();
		if ($countryres !== false) {
			$row = $countryres->fetch();
			$placename .= $row['country'];
		}
	}
	return $placename;
}
