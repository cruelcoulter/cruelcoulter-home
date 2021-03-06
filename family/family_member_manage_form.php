<!-- started 2/24/20. Not tested -->
<?php 
function returnff($ff, $to_edit, $fmrow)
{
    if ($to_edit === true && strlen($fmrow[$ff])) 
        { echo "value=\"{$fmrow[$ff]}\""; }
}
?>
    <h3>Format for dates is YYYY-MM-DD</h3>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="form-row"> <!-- row 1 -->
        <div class="form-group col-md-4">
            <label for="first_name">First Name</label>
            
            <input class="form-control" name="first_name" id="first_name" type="text" maxlength="20"
                   <?php returnff("first_name", $to_edit, $fmrow) ?> />
        </div>

        <div class="form-group col-md-4">
            <label for="family_id">Last Name</label>
                <select class="form-control" name="family_id" id="family_id">
                    <option value=""></option>
                    <?php 
                    while ($row = mysqli_fetch_array($result)) {
                        checklistmatch($row["family_id"], "family_id", $row["family_name"], "family_name", $fmrow["family_id"]);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="gender">Gender</label>
                <select class="form-control" name="gender" id="gender">
                <option value=""></option>
                <?php
                if ($fmrow["gender"] == "male") { 
                    echo "<option value=\"male\" selected>male</option>";
                } else {
				    echo "<option value=\"male\">male</option>";
				}
				
				if ($fmrow["gender"] == "female") {
					echo "<option value=\"female\" selected>female</option>";
				} else {
					echo "<option value=\"female\">female</option>";
				}
                ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="married_name_id">Married Name</label>
                <select class="form-control" name="married_name_id" id="married_name_id">
                    <option value=""></option>
                    <?php
		                mysqli_data_seek($result,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($result)) {
                    checklistmatch($row["family_id"], "family_id", $row["family_name"], "family_name", $fmrow["married_name_id"]);
                    }
                    ?>
                </select>
            </div>

            </div> <!-- //row 1 -->

            <div class="form-row"> <!-- row 2 -->

            <div class="form-group col-md-2">
            <label for="birthdate_est">Est Birth Date</label>
            <input class="form-control" name="birthdate_est" id="birthdate_est" type="text" maxlength="4" placeholder="YYYY (if known)" 
                   <?php returnff("birthdate_est", $to_edit, $fmrow) ?>/>
            </div>

            <div class="form-group col-md-2">
            <label for="birthdate">Birth Date</label>
            <input class="form-control" name="birthdate" id="birthdate" type="text" maxlength="25" placeholder="YYYY-MM-DD" 
                 <?php returnff("birthdate", $to_edit, $fmrow) ?>  />
            </div>

            <div class="form-group col-md-2">
            <label for="birth_city_twp_id">Birthplace (city/twp)</label>
                <select class="form-control" name="birth_city_twp_id" id="birth_city_twp_id">
                    <option value=""></option>
                    <?php
                    while ($row = mysqli_fetch_array($res_city_twp)) {
                        checklistmatch($row["city_twp_id"], "city_twp_id", $row["city_name"], "city_name", $fmrow["birth_city_twp_id"]);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="birth_state_id">Birthplace (State)</label>
                <select class="form-control" name="birth_state_id" id="birth_state_id">
                    <option value=""></option>
                    <?php
                    while ($row = mysqli_fetch_array($res_state)) {
                        checklistmatch($row["state_id"], "state_id", $row["state"], "state", $fmrow["birth_state_id"]);
                        }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="birth_county_id">Birthplace (County)</label>
                <select class="form-control" name="birth_county_id" id="birth_county_id">
                    <option value=""></option>
                    <?php
                    while ($row = mysqli_fetch_array($res_county)) {
                        checklistmatch($row["county_id"], "county_id", $row["county"], "county", $fmrow["birth_county_id"]);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="birth_country_id">Birthplace (Country)</label>
                <select class="form-control" name="birth_country_id" id="birth_country_id">
                    <option value=""></option>
                    <?php
                    while ($row = mysqli_fetch_array($res_country)) {
                        checklistmatch($row["country_id"], "country_id", $row["country"], "country", $fmrow["birth_country_id"]);
                    }
                    ?>
                </select>
            </div>

            </div> <!-- //row 2 -->


            <div class="form-row"> <!-- row 3 -->

            <div class="form-group col-md-2">
            <label for="deathdate_est">Est Death Date</label>
            <input class="form-control" name="deathdate_est" id="deathdate_est" type="text" maxlength="4" placeholder="YYYY (if known)" 
                   <?php returnff("deathdate_est", $to_edit, $fmrow) ?>/>
            </div>

            <div class="form-group col-md-2">
            <label for="deathdate">Death Date</label>
            <input class="form-control" name="deathdate" id="deathdate" type="text" maxlength="25" placeholder="YYYY-MM-DD" 
                 <?php returnff("deathdate", $to_edit, $fmrow) ?>  />
            </div>

            <div class="form-group col-md-2">
            <label for="death_city_twp_id">Deathplace (city/twp)</label>
                <select class="form-control" name="death_city_twp_id" id="death_city_twp_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($res_city_twp,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_city_twp)) {
                        checklistmatch($row["city_twp_id"], "city_twp_id", $row["city_name"], "city_name", $fmrow["death_city_twp_id"]);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="death_state_id">Deathplace (State)</label>
                <select class="form-control" name="death_state_id" id="death_state_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($res_state,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_state)) {
                    checklistmatch($row["state_id"], "state_id", $row["state"], "state", $fmrow["death_state_id"]);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="death_county_id">Deathplace (County)</label>
                <select class="form-control" name="death_county_id" id="death_county_id">
                    <option value=""></option>
                    <?php
		mysqli_data_seek($res_county,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_county)) {
                    checklistmatch($row["county_id"], "county_id", $row["county"], "county", $fmrow["death_county_id"]);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
            <label for="death_country_id">Deathplace (Country)</label>
                <select class="form-control" name="death_country_id" id="death_country_id">
                    <option value=""></option>
                    <?php
		            mysqli_data_seek($res_country,0); //have to restart at the beginning
                    while ($row = mysqli_fetch_array($res_country)) {
                    checklistmatch($row["country_id"], "country_id", $row["country"], "country", $fmrow["death_country_id"]);
                    }
                    ?>
                </select>
            </div>

            </div> <!-- //row 3 -->

            <div class="form-row"> <!-- row 4 -->

            <div class="form-group col-md-3">
            <label for="mother_member_id">Mother</label>
                <select class="form-control" name="mother_member_id" id="mother_member_id">
                    <option value=""></option>
                    <?php
                    while ($row = mysqli_fetch_array($res_mother)) {
                    checklistmatch($row['family_member_id'], "family_member_id", $row['full_name'], "full_name", $fmrow["mother_member_id"]);
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-3">
            <label for="father_member_id">Father</label>
                <select class="form-control" name="father_member_id" id="father_member_id">
                    <option value=""></option>
                    <?php
                    while ($row = mysqli_fetch_array($res_father)) {
                        checklistmatch($row['family_member_id'], "family_member_id", $row['full_name'], "full_name", $fmrow["father_member_id"]);
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group col-md-6">
            <label for="family_member_notes">Notes</label>
            <textarea class="form-control" name="family_member_notes" id="family_member_notes"><?php echo $fmrow["family_member_notes"]?></textarea>
			</div>
            
            </div> <!-- //row 4 -->

            <div class="form-row"> <!-- row 5 -->
            <div class="form-group col-md-6">
            <label for="family_member_slug">Slug</label>
            <input type="text" class="form-control" id="family_member_slug" name="family_member_slug" <?php returnff("family_member_slug", $to_edit, $fmrow) ?> />
            <button type="button" value="build-slug" name="build-slug" class="btn" onclick="buildslug()">Build Slug</button>
            </div>
  
            
            <div class="form-group col-md-6">
                <label for="access_level">Access Level</label>
                <select class="form-control" name="access_level" id="access_level">
                <option value="PUBLIC">Public</option>
                <option value="PRIVATE">Private</option>
                </select>
                </div>
            </div>    <!-- //row 5 -->
            
            <?php 
            if ($to_edit == true) {
            	echo "<input type=\"hidden\" name=\"family_member_id\" value=" . $family_member_id . ">";
            }
            
            ?>
            
            <button type="submit" value="submit" name="submit" class="btn btn-primary">Submit</button>


</form>
