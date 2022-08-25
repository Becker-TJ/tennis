$(document).ready( function () {

    $("#school_id_to_tie").change(function() {
        $("#confirm_school_button").attr('hidden', false);
        $("#switch_button_name").html("Confirm");
    });

    $("#not_listed").change(function() {
        if (this.checked) {
            $("#confirm_school_button").attr('hidden', false);
            $("#existing_schools").attr('hidden', true);
            $("#toggle_school_not_listed_section").attr('hidden', false);
            $(".toggle_input").removeAttr('disabled').attr('required', true);
            $("#switch_button_name").html("Add New School");
        } else {
            var schoolToTie = $('#school_id_to_tie :selected').text();
            $("#existing_schools").attr('hidden', false);
            $("#toggle_school_not_listed_section").attr('hidden', true);
            $(".toggle_input").attr('disabled', true).val('').attr('required', false);
            $("#switch_button_name").html("Confirm");
        }
    });

});







