<?php
header("Content-type: application/json");
/*
01/28/16 - initial version
05/13/16 - point to family_detail instead of family_member_detail
 */

$json_data = array();

require '../../db_config.php';

require "../../pdo.php";

//don't include notes until I figure out how to escape html
$family_find_query = "select
concat('<a href=\"family_member\\\', fm.family_member_slug, '\">', fm.family_member_slug, '</a>'),
fm.first_name,
(select family_name from family where family_id = fm.family_id) as last_name,
fm.gender,
(select family_name from family where family_id = fm.married_name_id) as married_name,
(select city_name from city_twp where city_twp_id = fm.birth_city_twp_id) as birth_city,
(select state from state where state_id = fm.birth_state_id) as birth_state,
(select country from country where country_id = fm.birth_country_id) as birth_country,
coalesce(fm.birthdate_est, year(fm.birthdate), '?') as birthyear,
(select city_name from city_twp where city_twp_id = fm.death_city_twp_id) as death_city,
(select state from state where state_id = fm.death_state_id) as death_state,
(select country from country where country_id = fm.death_country_id) as death_country,
coalesce(fm.deathdate_est, year(fm.deathdate), '?') as deathyear
from
family_member fm
WHERE
access_level = 'PUBLIC'
order by
fm.family_id,
birthyear";

//$family_find_result = mysql_query($family_find_query);

$fistatement = $link->prepare($family_find_query);

$fistatement->execute();

$family_find = $fistatement->fetchAll(PDO::FETCH_NUM);

$json_data = array('aaData' => $family_find);

echo json_encode($json_data);

?>
