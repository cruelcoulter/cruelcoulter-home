function buildslug () {
var first_name = $('#first_name').val().toLowerCase();
//var last_name = $('#last_name').val();
var ln = document.getElementById('family_id');
var birthdateest = document.getElementById('birthdate_est').value;
var testbirthdate = testdate(birthdateest);
var deathdateest = document.getElementById('deathdate_est').value;
var testdeathdate = testdate(deathdateest); 
var last_name = ln.options[ln.selectedIndex].text.toLowerCase();
    document.getElementById('family_member_slug').value = first_name + "-" + last_name + "-" + testbirthdate + "-" + testdeathdate;
}

function testdate(incomingdate) {
//alert(incomingdate);
var testresult = /^[0-9]{4}$/.test(incomingdate);
if (testresult == true ) {
    return incomingdate;
} else {
    return "?";
}
}


