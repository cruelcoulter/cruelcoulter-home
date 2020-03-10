$edit_qry_text = "update family_member set ";
	if (strlen($first_name)){
		$edit_qry_text .= " first_name='" . $first_name . "'";
	} else {
		$edit_qry_text .= " first_name=NULL";
		}
	
	if (strlen($family_id)){
		$edit_qry_text .= ", family_id=" . $family_id;
	}
	
	if (strlen($last_name)){
		$edit_qry_text .= ", last_name='" . $last_name . "'";
	}
	
	if (strlen($gender)){
		$edit_qry_text .= ", gender='" . $gender . "'";
	}
	
	if (strlen($married_name)){
		$edit_qry_text .= ", married_name='" . $married_name . "'";
	}
	
	if (strlen($married_name_id)){
		$edit_qry_text .= ", married_name_id=" . $married_name_id;
	}
	
	if (strlen($birthdate_est)){
		$edit_qry_text .= ", birthdate_est='" . $birthdate_est . "'";
	}
	
	if (strlen($birthdate)){
		$edit_qry_text .= ", birthdate='" . $birthdate . "'";
	}
	
	if (strlen($birth_city_twp_id)){
		$edit_qry_text .= ", birth_city_twp_id=" . $birth_city_twp_id;
	}
	
	if (strlen($birth_state_id)){
		$edit_qry_text .= ", birth_state_id=" . $birth_state_id;
	}
	
	if (strlen($birth_county_id)){
		$edit_qry_text .= ", birth_county_id=" . $birth_county_id;
	}
	
	if (strlen($birth_country_id)){
		$edit_qry_text .= ", birth_country_id=" . $birth_country_id;
	}
	
	if (strlen($deathdate_est)){
		$edit_qry_text .= ", deathdate_est='" . $deathdate_est . "'";
	}
	
	if (strlen($deathdate)){
		$edit_qry_text .= ", deathdate='" . $deathdate . "'";
	}
	
	if (strlen($death_city_twp_id)){
		$edit_qry_text .= ", death_city_twp_id=" . $death_city_twp_id;
	}
	
	if (strlen($death_state_id)){
		$edit_qry_text .= ", death_state_id=" . $death_state_id;
	}
	
	if (strlen($death_county_id)){
		$edit_qry_text .= ", death_county_id=" . $death_county_id;
	}
	
	if (strlen($death_country_id)){
		$edit_qry_text .= ", death_country_id=" . $death_country_id;
	}
	
	if (strlen($mother_member_id)){
		$edit_qry_text .= ", mother_member_id=" . $mother_member_id;
	}
	
	if (strlen($father_member_id)){
		$edit_qry_text .= ", father_member_id=" . $father_member_id;
	}
	
	if (strlen($family_member_notes)){
		$edit_qry_text .= ", family_member_notes='" . $family_member_notes . "'";
	} 
	if (strlen($family_member_slug)){
		$edit_qry_text .= ", family_member_slug='" . $family_member_slug . "'";
	} 
	$edit_qry_text .= ", access_level='" . $access_level . "'";
	
    $edit_qry_text .= " where family_member_id = " . $_POST["family_member_id"];
    
