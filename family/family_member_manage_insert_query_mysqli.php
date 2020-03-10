<?php
$ins_qry_text = "insert into family_member (";
       
    
       if (strlen($first_name)){
       $ins_qry_text .= "first_name";
       }
       if (strlen($family_id)){
       $ins_qry_text .= ", family_id";
       }
       if (strlen($last_name)){
       $ins_qry_text .= ", last_name";
       }
       if (strlen($gender)){
       $ins_qry_text .= ", gender";
       }
       if (strlen($married_name)){
       $ins_qry_text .= ", married_name";
       }
       if (strlen($married_name_id)){
       $ins_qry_text .= ", married_name_id";
       }
       if (strlen($birthdate_est)){
       $ins_qry_text .= ", birthdate_est";
       }
       if (strlen($birthdate)){
       $ins_qry_text .= ", birthdate";
       }
       if (strlen($birth_city_twp_id)){
       $ins_qry_text .= ", birth_city_twp_id";
       }
       if (strlen($birth_state_id)){
       $ins_qry_text .= ", birth_state_id";
       }
       if (strlen($birth_county_id)){
       $ins_qry_text .= ", birth_county_id";
       }
       if (strlen($birth_country_id)){
       $ins_qry_text .= ", birth_country_id";
       }
       if (strlen($deathdate_est)){
       $ins_qry_text .= ", deathdate_est";
       }
       if (strlen($deathdate)){
       $ins_qry_text .= ", deathdate";
       }
       if (strlen($death_city_twp_id)){
       $ins_qry_text .= ", death_city_twp_id";
       }
       if (strlen($death_state_id)){
       $ins_qry_text .= ", death_state_id";
       }
       if (strlen($death_county_id)){
       $ins_qry_text .= ", death_county_id";
       }
       if (strlen($death_country_id)){
       $ins_qry_text .= ", death_country_id";
       }
       if (strlen($mother_member_id)){
       $ins_qry_text .= ", mother_member_id";
       }
       if (strlen($father_member_id)){
       $ins_qry_text .= ", father_member_id";
       }
       if (strlen($family_member_notes)){
           $ins_qry_text .= ", family_member_notes";
       }
       if (strlen($family_member_slug)){
           $ins_qry_text .= ", family_member_slug";
       }
       $ins_qry_text .= ", access_level";
       
       $ins_qry_text .= ") VALUES (";
       
       if (strlen($first_name)){
           $ins_qry_text .= "'" . $first_name . "'";
       }
       if (strlen($family_id)){
           $ins_qry_text .= ", " . $family_id;
       }
       if (strlen($last_name)){
           $ins_qry_text .= ", '" . $last_name . "'";
       }
       if (strlen($gender)){
           $ins_qry_text .= ", '" . $gender . "'";
       }
       if (strlen($married_name)){
           $ins_qry_text .= ", '" . $married_name . "'";
       }
       if (strlen($married_name_id)){
           $ins_qry_text .= ", " . $married_name_id;
       }
       if (strlen($birthdate_est)){
           $ins_qry_text .= ", '" . $birthdate_est . "'";
       }
       if (strlen($birthdate)){
           $ins_qry_text .= ", '" . $birthdate . "'";
       }
       if (strlen($birth_city_twp_id)){
           $ins_qry_text .= ", " . $birth_city_twp_id;
       }
       if (strlen($birth_state_id)){
           $ins_qry_text .= ", " . $birth_state_id;
       }
       if (strlen($birth_county_id)){
           $ins_qry_text .= ", " . $birth_county_id;
       }
       if (strlen($birth_country_id)){
           $ins_qry_text .= ", " . $birth_country_id;
       }
       if (strlen($deathdate_est)){
           $ins_qry_text .= ", '" . $deathdate_est . "'";
       }
       if (strlen($deathdate)){
           $ins_qry_text .= ", '" . $deathdate . "'";
       }
       if (strlen($death_city_twp_id)){
           $ins_qry_text .= ", " . $death_city_twp_id;
       }
       if (strlen($death_state_id)){
           $ins_qry_text .= ", " . $death_state_id;
       }
       if (strlen($death_county_id)){
           $ins_qry_text .= ", " . $death_county_id;
       }
       if (strlen($death_country_id)){
           $ins_qry_text .= ", " . $death_country_id;
       }
       if (strlen($mother_member_id)){
           $ins_qry_text .= ", " . $mother_member_id;
       }
       if (strlen($father_member_id)){
           $ins_qry_text .= ", " . $father_member_id;
       }
       if (strlen($family_member_notes)){
           $ins_qry_text .= ", '" . $family_member_notes . "'";
       }
       if (strlen($family_member_slug)){
           $ins_qry_text .= ", '" . $family_member_slug . "'";
       }
       $ins_qry_text .= ", '" . $access_level . "'";
   $ins_qry_text .= ")";
?>