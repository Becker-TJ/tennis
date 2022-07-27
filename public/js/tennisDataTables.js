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
        //this will be useful for adding a button in the same line as the search bar for creating tournaments etc
        // "initComplete": function( settings, json ) {
        //     $('#myTable_filter').html("<div id='myTable_filter' class='dataTables_filter'><div><label>Search:<input type='search' class='' placeholder='' aria-controls='myTable'></label><button id='roster_button' type='submit' class='btn btn-primary'>Create</button></div></div>");
        // },
    } );











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






    $(".score-input").keydown(function(e) {
        $inputString = $(this).val();
        var checkScoreValidity = false;
        var tournament_id = $('#tournament_id').html();
        var scoreInput = $(this).attr('id');
        var validScoresAfterOneThroughFour = [6];
        var validScoresAfterFive = [7];
        var validScoresAfterSix = [0,1,2,3,4,7];
        var validScoresAfterSeven = [5,6];

        if (e.which === 48 || e.which === 96) {
            var numberPressed = 0;
        } else if (e.which === 49 || e.which === 97) {
            var numberPressed = 1;
        } else if (e.which === 50 || e.which === 98) {
            var numberPressed = 2;
        } else if (e.which === 51 || e.which === 99) {
            var numberPressed = 3;
        } else if (e.which === 52 || e.which === 100) {
            var numberPressed = 4;
        } else if (e.which === 53 || e.which === 101) {
            var numberPressed = 5;
        } else if (e.which === 54 || e.which === 102) {
            var numberPressed = 6;
        } else if (e.which === 55 || e.which === 103) {
            var numberPressed = 7;
        } else if ((e.which === 56 || e.which === 104) && $inputString.length > 7) {
            var numberPressed = 8;
        } else if ((e.which === 57 || e.which === 105) && $inputString.length > 7) {
            var numberPressed = 9;
        } else if (e.which === 8 || e.which === 46) {
            $(this).val('');
            $(this).removeClass('invalid-score');
            $(this).removeClass('match-complete');
            return false;
        } else {
            return false;
        }

        if($(this).hasClass('match-complete') || $(this).hasClass('invalid-score')) {
            return false;
        }

        var firstScore = parseInt($inputString.charAt(0));
        var secondScore = parseInt($inputString.charAt(2));
        var thirdScore = parseInt($inputString.charAt(5));
        var fourthScore = parseInt($inputString.charAt(7));

        if($inputString.length === 0) {
            $(this).val(numberPressed + '-');
        }

        else if ($inputString.length === 2) {
            if(firstScore === 7) {
                if(validScoresAfterSeven.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                }
            }
            else if(firstScore === 6) {
                if(validScoresAfterSix.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                }
            } else if(firstScore === 5) {
                if(validScoresAfterFive.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                }
            } else if(firstScore < 5) {
                if(validScoresAfterOneThroughFour.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                }
            }
        }


        else if ($inputString.length === 3) {
            $(this).val($inputString + ', ' + numberPressed + '-');
        }


        else if ($inputString.length === 7) {
            if(thirdScore === 7) {
                if(validScoresAfterSeven.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                    checkScoreValidity = true;
                }
            }
            else if(thirdScore === 6) {
                if(validScoresAfterSix.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                    checkScoreValidity = true;
                }
            } else if(thirdScore === 5) {
                if(validScoresAfterFive.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                    checkScoreValidity = true;
                }
            } else if(thirdScore < 5) {
                if(validScoresAfterOneThroughFour.includes(numberPressed)) {
                    $(this).val($inputString + numberPressed);
                    checkScoreValidity = true;
                }
            }
        }


        else if ($inputString.length === 8) {
            if(numberPressed === 0) {
                return false;
            }
            $(this).val($inputString + ', (' + numberPressed);
        }

        else if ($inputString.length === 12) {
            $(this).val($inputString + numberPressed + '-');
        }

        else if ($inputString.length === 14) {
            $(this).val($inputString + numberPressed);
            checkScoreValidity = true;
        }

        else if ($inputString.length === 15) {
            $(this).val($inputString + numberPressed);
            checkScoreValidity = true;
        }


        if(checkScoreValidity) {
            $score = $inputString + numberPressed;

            if(isScoreValid($score) === 'third set needed' || isScoreValid($score) === 'needs to finish score') {
                return false;
            }

            if(isScoreValid($score)) {
                if($score.length > 8) {
                    $score += ')';
                }
                $(this).addClass('match-complete');

                $bracket = $('.selected-button').attr('id');
                $scoreInput = $(this).attr('id');

                saveScore($bracket, tournament_id, $score, $scoreInput);

                $(this).val($score);
            } else {
                if($score.length > 8) {
                    $score += ')';
                }
                $(this).val($score);
                $(this).addClass('invalid-score');
            }
        }

        return false;

    });

    function isScoreValid(string) {
        $setsWon = 0;
        $firstScore = parseInt(string.charAt(0));
        $secondScore = parseInt(string.charAt(2));
        $thirdScore = parseInt(string.charAt(5));
        $fourthScore = parseInt(string.charAt(7));
        if(string.length > 8){
            $fifthScore = parseInt(string.substring(11,13));
            if(string.length === 15) {
                $sixthScore = parseInt(string.charAt(14));
            } else if (string.length === 16) {
                $sixthScore = parseInt(string.substring(14,16));
            }

            if($sixthScore + 2 > $fifthScore) {
                return false;
            }

            if(($fifthScore > 10) && ($sixthScore != ($fifthScore - 2))) {
                if($sixthScore.toString().length > 1) {
                    return false;
                }
                return 'needs to finish score';
            }

            if($fifthScore > $sixthScore) {
                $setsWon++;
            }
        }

        if($firstScore > $secondScore) {
            $setsWon++;
        }
        if($thirdScore > $fourthScore) {
            $setsWon++;
        }

        if($setsWon === 2) {
            return true;

        } else if ($setsWon === 1) {
            return 'third set needed';
        }

        return false;

    }

    //this is assigning settings for the sortable tables
    $('#myTable').DataTable( {
        paging:false,
        "lengthChange": false,
        scrollX:true,
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
        'order': [],
        'columnDefs': [ {
            'targets':[6],
            'orderable': false
        }]


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
                //this will be useful for adding a button in the same line as the search bar for creating tournaments etc
                // "initComplete": function( settings, json ) {
                //     $('#myTable_filter').html("<div id='myTable_filter' class='dataTables_filter'><div><label>Search:<input type='search' class='' placeholder='' aria-controls='myTable'></label><button id='roster_button' type='submit' class='btn btn-primary'>Create</button></div></div>");
                // },

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








