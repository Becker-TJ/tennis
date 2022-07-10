
$("#not_listed").change(function() {
    if (this.checked) {
        $(".toggle_show").removeAttr('disabled').attr('required', true);
        $("#switch_button_name").html("Add New School");
    } else {
        $(".toggle_show").attr('disabled', true).val('').attr('required', false);
        $("#switch_button_name").html("Tie Existing School");
    }
});


