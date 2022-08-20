$(document).ready( function () {


    var allSchoolsTable = $('#allSchoolsTable').DataTable( {
        paging:false,
        searching:true,
        rowReorder: {
            selector: '.reorder-cell',
            snapX:true,
        },
        bInfo:false,
        "lengthChange": false,

        'columnDefs': [
            { targets: [0,1,2], orderable: true },
            { targets: [2], "className": "center-align"}
        ]

    } );

    var varsityOrder = [
        '1 Singles',
        '2 Singles',
        '1 Doubles',
        '1 Doubles',
        '2 Doubles',
        '2 Doubles',
    ];

    //this resets the order of the far left column for a school roster after a click and drag table row event(1 singles, 2 singles, etc)


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("#not_listed").change(function() {
        if (this.checked) {
            $(".toggle_show").removeAttr('disabled').attr('required', true);
            $("#switch_button_name").html("Add New School");
        } else {
            $(".toggle_show").attr('disabled', true).val('').attr('required', false);
            $("#switch_button_name").html("Tie Existing School");
        }
    });



    //this is assigning settings for the sortable tables
    $('#playerDisplayTable').DataTable( {
        paging:false,
        "lengthChange": false,
        'columns': [
            { data: 'name' }, /* index = 0 */
            { data: 'address' }, /* index = 1 */
            { data: 'team_count' }, /* index = 2 */
            { data: 'level' }, /* index = 3 */
        ],
        'columnDefs': [ {
            'orderable': false, /* true or false */

        },{ targets: [3], "className": "center-align"}],

    } );

    $('#tournamentsTable').DataTable( {
        paging:false,
        searching:true,
        bInfo:false,
        "lengthChange": false,
        'columns': [
            { data: 'name' }, /* index = 0 */
            { data: 'location_name' }, /* index = 1 */
            { data: 'date' }, /* index = 2 */
            { data: 'level' }, /* index = 3 */
            { data: 'gender' }, /* index = 4 */
            { data: 'team_count' }, /* index = 5 */

        ],
        'order': [],



    } );



} );








