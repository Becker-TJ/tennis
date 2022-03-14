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
            { targets: [0,1], visible: false },
            { targets: [2,3,4,5], orderable: false }
        ]
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
        '2 Doubles',
    ];

    //this resets the order of the far left column for a school roster after a click and drag table row event(1 singles, 2 singles, etc)
    function displayPositionNamesInCorrectOrder() {
        $('.position_name_td').each(function(index, tableCell){
            if(varsityOrder[index] !== undefined) {
                tableCell.innerHTML = varsityOrder[index];
            } else {
                tableCell.innerHTML = 'JV';
            }
        });
    }

    function createPositionRowBorders() {
        $('#schoolTable > tbody > tr').each(function(index, row){
            if(index == 0 || index == 1 || index == 3 || index == 5) {
                row.classList.add('border_bottom');
            } else {
                row.classList.remove('border_bottom');
            }

            if(index < 6) {
                row.classList.add('position_highlight');
            }   else {
                row.classList.remove('position_highlight');
            }
        });
    }

    displayPositionNamesInCorrectOrder();
    createPositionRowBorders();

    schoolTable.on('row-reordered', function (e, diff, edit) {
        displayPositionNamesInCorrectOrder();
        createPositionRowBorders();

        var table = $("#schoolTable").DataTable();

        //drawing will update the data table with any changes made through reordering rows
        table.one( 'draw', function () {

            var updatedPositionOrder = [];

            table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                updatedPositionOrder.push(this.data());
            });

            $.ajax({
                type:'POST',
                url:'/savePlayerPositions',
                data:{updatedPositionOrder:updatedPositionOrder},
                success:function(data){
                    console.log('saved positions.');
                }
            });

        });
    } );

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#savePlayerPositionsButton").click(function(e){
        e.preventDefault();

        var name = 'tj';
        var password = 'becker';
        var email = 'rocks';

        var tableData = $("#schoolTable").DataTable();
        console.log(tableData);

        $('#schoolTable > tbody > tr').each(function(index, row){
            // console.log(index);
            // console.log(row);
            console.log(tableData.row(index).data());
        });

        $.ajax({
            type:'POST',
            url:'/savePlayerPositions',
            data:{name:name, password:password, email:email},
            success:function(data){
                alert(data.success);
            }
        });
    });

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

    $('#tournamentsTable').DataTable( {
        paging:false,
        "lengthChange": false,
        'columns': [
            { data: 'name' }, /* index = 0 */
            { data: 'location_name' }, /* index = 1 */
            { data: 'date' }, /* index = 2 */
            { data: 'gender' }, /* index = 3 */
            { data: 'team_count' }, /* index = 5 */
            { data: 'level' }, /* index = 6 */
            { data: 'actions' } /* index = 7 */
        ],
        'columnDefs': [ {
            'targets':[6],
            'orderable': false
        }]


    } );

    $('#schools_to_invite').change(function() {
        $schoolID = $('#schools_to_invite').find(":selected").val();
        $htmlString = $('#list_to_invite').html();
        $schoolSelected =  $('#schools_to_invite').find(":selected").text();
        
        $removed = $('#schools_to_invite').find(":selected").remove();

        $htmlString +='<li class="school_invitee" data-value="' + $schoolID + '">' + $schoolSelected + '</li><span aria-hidden="true">&times;</span>';

        $('#list_to_invite').html($htmlString);

        $('li.school_invitee').each(function(index, li) {
            console.log(index);
            console.log(li.dataset.value);
        });

    });

    $("#invite_schools_button").click(function(e){
        e.preventDefault();

        var tournamentID = $('#tournamentID').html();
        var schoolInviteeIDs = [];

        $('li.school_invitee').each(function(index, li) {
            console.log(index);
            console.log(li.dataset.value);
            schoolInviteeIDs.push(li.dataset.value);
        });

        $.ajax({
            type:'POST',
            url:'/inviteSchools',
            data:{schoolInviteeIDs:schoolInviteeIDs, tournamentID:tournamentID},
            success:function(data){
                alert(data.success);
            }
        });
    });
} );


