$(document).ready( function () {

    //related to dropdown sorting
    $('.select2').select2();
    $('.select2').select2({
        width: 'resolve' // need to override the changed default
    });

    var schoolTable = $('#schoolTable').DataTable( {
        paging:false,
        searching:false,
        rowReorder: {
            selector: 'tr',
            snapX:true,
        },
        bInfo:false,
        "lengthChange": false,

        'columnDefs': [
            { targets: 0, visible: false },
            { targets: [1,2,3,4,5,6], orderable: false }

        ],
        //this will be useful for adding a button in the same line as the search bar for creating tournaments etc
        // "initComplete": function( settings, json ) {
        //     $('#myTable_filter').html("<div id='myTable_filter' class='dataTables_filter'><div><label>Search:<input type='search' class='' placeholder='' aria-controls='myTable'></label><button id='roster_button' type='submit' class='btn btn-primary'>Create</button></div></div>");
        // },
    } );

    var varsityOrder = [
        '1 Singles',
        '2 Singles',
        '1 Doubles',
        '1 Doubles',
        '2 Doubles',
        '2 Doubles'
    ];

        //this resets the order of the far left column for a school roster after a click and drag table row event(1 singles, 2 singles, etc)
    schoolTable.on('row-reorder', function (e, diff, edit) {
        $('.varsity_order').each(function(index, tableCell){
            tableCell.innerHTML = varsityOrder[index];
        });
    } );


    //this is assigning settings for the sortable tables
    $('#myTable').DataTable( {
        paging:false,
        "lengthChange": false,
        'columns': [
            { data: 'name' }, /* index = 0 */
            { data: 'address' }, /* index = 1 */
            { data: 'team_count' }, /* index = 2 */
            { data: 'level' }, /* index = 3 */
            { data: 'actions' } /* index = 4 */
        ],
        'columnDefs': [ {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
        }],
        //this will be useful for adding a button in the same line as the search bar for creating tournaments etc
        // "initComplete": function( settings, json ) {
        //     $('#myTable_filter').html("<div id='myTable_filter' class='dataTables_filter'><div><label>Search:<input type='search' class='' placeholder='' aria-controls='myTable'></label><button id='roster_button' type='submit' class='btn btn-primary'>Create</button></div></div>");
        // },
    } );



} );
