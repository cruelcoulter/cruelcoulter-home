$(document).ready(
    function () {
        $('#family_member_id').change(
            function () {
                var family_member_id = $("#family_member_id").val();

                $.ajax({
                        url: "load_data.php",
                        type: "POST",
                        data: {
                            family_member_id: family_member_id
                        },
                        //dataType: 'json',
                        success: function (data) {
                            $('#attachment_id').html(data);
                            //alert("Success");
                            //$('select[name="attachment_id"]').empty();
                            //$.each(data, function(key, value) {
                            //	$('select[name="attachment_id"]').append('<option value="'+ key + '">' + value + '</option>');
                        }
                    
                }
                )

            }
        );
    }

);