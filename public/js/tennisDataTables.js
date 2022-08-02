$(document).ready( function () {

    var schoolTable = $('#schoolTable').DataTable( {
        paging:false,
        searching:false,
        rowReorder: {
            selector: '.reorder-cell',
            snapX:true,
        },
        bInfo:false,
        "lengthChange": false,

        'columnDefs': [
            { targets: [0,1], visible: false },
            { targets: [2,3,4,5,6], orderable: false },
            {targets: [2], width: "5%" },
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

    var allSchoolsTable = $('#allSchoolsTable').DataTable( {
        paging:false,
        searching:false,
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
    function displayPositionNamesInCorrectOrder() {
        $('#schoolTable .position_name_td').each(function(index, tableCell){
            if(varsityOrder[index] !== undefined) {
                tableCell.innerHTML = varsityOrder[index];
            } else {
                tableCell.innerHTML = 'JV';
            }
        });

        $('#girlsSchoolTable .position_name_td').each(function(index, tableCell){
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

        $('#girlsSchoolTable > tbody > tr').each(function(index, row){
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


    $('.edit-pen').click(function(e) {
        e.preventDefault();

        if($('#boys-roster').hasClass('selected-roster-button')) {
            $tableIDString = '#schoolTable';
        } else {
            $tableIDString = '#girlsSchoolTable';
        }

        $('#class-freshman').removeClass('active');
        $('#class-sophomore').removeClass('active');
        $('#class-junior').removeClass('active');
        $('#class-senior').removeClass('active');
        $('#gender-male').removeClass('active');
        $('#gender-female').removeClass('active');

        var currentRow = $(this).closest('tr');
        var data = $($tableIDString).DataTable().row(currentRow).data();
        var playerID = data[1];

        $.ajax({
            type:'POST',
            url:'/getPlayerDetails',
            data:{playerID:playerID},
            success:function(data){

                console.log(data.player.gender.toLowerCase());
                $('#edit_player_first_name').val(data.player.first_name);
                $('#edit_player_last_name').val(data.player.last_name);
                $('#class-' + data.player.class.toLowerCase()).addClass('active');
                $('#gender-' + data.player.gender.toLowerCase()).addClass('active');
            }
        });

    });

    $('#boys-roster').click(function(e) {
        e.preventDefault();

        $('#girls-roster').removeClass('selected-roster-button');
        $('#boys-roster').addClass('selected-roster-button');

        $('#girlsSchoolTable').attr('hidden', true);
        $('#schoolTable').removeAttr('hidden');
    });

    $('#girls-roster').click(function(e) {
        e.preventDefault();

        $('#boys-roster').removeClass('selected-roster-button');
        $('#girls-roster').addClass('selected-roster-button');

        $('#schoolTable').attr('hidden', true);
        $('#girlsSchoolTable').removeAttr('hidden');


        if (! $.fn.DataTable.isDataTable('#girlsSchoolTable')) {
            var girlsSchoolTable = $('#girlsSchoolTable').DataTable( {
                paging:false,
                searching:false,
                rowReorder: {
                    selector: '.reorder-cell',
                    snapX:true,
                },
                bInfo:false,
                "lengthChange": false,

                'columnDefs': [
                    { targets: [0,1], visible: false },
                    { targets: [2,3,4,5,6], orderable: false },
                    {targets: [2], width: "5%" },
                ]



            } );

            displayPositionNamesInCorrectOrder();
            createPositionRowBorders();

            girlsSchoolTable.on('row-reordered', function (e, diff, edit) {
                displayPositionNamesInCorrectOrder();
                createPositionRowBorders();
                var table = $("#girlsSchoolTable").DataTable();

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
        }

    });


} );








