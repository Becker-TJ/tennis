if (document.documentElement.clientWidth < 481) {
    document.querySelector("meta[name=viewport]").setAttribute(
        'content',
        'width=device-width, initial-scale=.7, maximum-scale=1.0, user-scalable=0');
}

$('#viewSchoolsNavTitle').addClass('active-nav-title');

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
            { targets: [2,3,4,5,6], orderable: false, "className": "center-align"},
            {targets: [2], width: "5%" },
            {targets: [6], width: "5%" },
            {targets: [3], width: "15%" },
        ]

    } );


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
            { targets: [2,3,4,5,6], orderable: false, "className": "center-align"},
            {targets: [2], width: "5%" },
            {targets: [6], width: "5%" },
            {targets: [3], width: "15%" },
        ]

    } );

    checkCoach();
    $('#girlsSchoolTable').attr('hidden', true);

    function checkCoach() {
        var isCoach = ($('#coach').attr('data-coach') === 'true');

        if(!isCoach) {
            schoolTable.columns([2,6]).visible(false);
            girlsSchoolTable.columns([2,6]).visible(false);
        }

    }



    var varsityOrder = [
        '#1 Singles',
        '#2 Singles',
        '#1 Doubles',
        '#1 Doubles',
        '#2 Doubles',
        '#2 Doubles',
    ];

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

    $('.edit-pen').click(function(e) {
        e.preventDefault();

        if($('#boys-roster').hasClass('selected-roster-button')) {
            $tableIDString = '#schoolTable';
        } else {
            $tableIDString = '#girlsSchoolTable';
        }

        $('#edit_grade_freshman').removeClass('active');
        $('#edit_grade_sophomore').removeClass('active');
        $('#edit_grade_junior').removeClass('active');
        $('#edit_grade_senior').removeClass('active');
        $('#edit_gender_male').removeClass('active');
        $('#edit_gender_female').removeClass('active');

        $('#edit_grade_freshman_input').removeAttr('checked');
        $('#edit_grade_sophomore_input').removeAttr('checked');
        $('#edit_grade_junior_input').removeAttr('checked');
        $('#edit_grade_senior_input').removeAttr('checked');
        $('#edit_gender_male_input').removeAttr('checked');
        $('#edit_gender_female_input').removeAttr('checked');

        var currentRow = $(this).closest('tr');
        var data = $($tableIDString).DataTable().row(currentRow).data();
        var playerID = data[1];
        $('#edit_player_id').val(playerID);

        $.ajax({
            type:'POST',
            url:'/getPlayerDetails',
            data:{playerID:playerID},
            success:function(data){

                console.log(data.player.gender.toLowerCase());
                $('#edit_player_first_name').val(data.player.first_name);
                $('#edit_player_last_name').val(data.player.last_name);
                $('#edit_grade_' + data.player.grade.toLowerCase()).addClass('active');
                $('#edit_grade_' + data.player.grade.toLowerCase() + '_input').attr('checked', 'checked')
                $('#edit_gender_' + data.player.gender.toLowerCase()).addClass('active');
                $('#edit_gender_' + data.player.gender.toLowerCase() + '_input').attr('checked', 'checked');
            }
        });

    });

    $('#boys-roster').click(function(e) {
        e.preventDefault();

        $('#girls-roster').removeClass('selected-roster-button');
        $('#boys-roster').addClass('selected-roster-button');

        $('#playerForStatsModal').attr('data-player-table', 'schoolTable');

        $('#girlsSchoolTable').attr('hidden', true);
        $('#schoolTable').removeAttr('hidden');
    });

    $('#girls-roster').click(function(e) {
        e.preventDefault();

        $('#boys-roster').removeClass('selected-roster-button');
        $('#girls-roster').addClass('selected-roster-button');

        $('#playerForStatsModal').attr('data-player-table', 'girlsSchoolTable');

        $('#schoolTable').attr('hidden', true);
        $('#girlsSchoolTable').removeAttr('hidden');

        checkCoach();
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


    });


});
