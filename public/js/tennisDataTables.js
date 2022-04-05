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

    $(".bracket-button").click(function(e){
        e.preventDefault();
        $(".bracket-button").removeClass('selected-button');
        $(this).addClass('selected-button');

        var tournament_id = $('#tournament_id').html();
        var requestedBracket = $(this).attr('id');

        fillBracketData(requestedBracket, tournament_id);
    });

    function fillBracketData(requestedBracket, tournament_id) {
        $.ajax({
            type:'POST',
            url:'/getBracketData',
            data:{tournament_id:tournament_id,requestedBracket:requestedBracket},
            success:function(data){
                //repopulates tournament bracket with user selection of boys, girls, 1 singles, 2 singles, etc
                // for(const seedNumber in data.seeds) {
                //     $('#' + seedNumber + '-seed').html(data.seeds[seedNumber].first_name + ' ' + data.seeds[seedNumber].last_name);
                //     $('#' + seedNumber + '-seed').attr('data-id', data.seeds[seedNumber].id);
                //     $('#' + seedNumber + '-seed-school').html(data.seeds[seedNumber].school_name);
                // }

                $increment = 1;
                $bracketPositions = data.bracketPositions;
                for (const [$position, $value] of Object.entries($bracketPositions)) {
                    if($increment > 4) {
                        if($value === 0) {
                            continue;
                        }
                        $positionWithDashes = $position.replace(/_/g, '-');
                        if($position.indexOf('school') !== -1) {
                            //does include the substring school
                            $('#' + $positionWithDashes).html($value);
                        } else if ($position.indexOf('id') !== -1){
                            //does include the substring id;
                            console.log($positionWithDashes, $value);
                            $positionCorrected = $positionWithDashes.slice(0, -3);
                            $('#' + $positionCorrected).attr('data-id', $value);
                        }else {
                            $('#' + $positionWithDashes).html($value);
                            console.log('#' + $positionWithDashes);
                            console.log($position);
                            console.log($value);
                        }
                    }
                    $increment++;
                }
            }
        });
    }

    $(".score-input").keydown(function(e) {
        $inputString = $(this).val();
        var checkScoreValidity = true;
        var tournament_id = $('#tournament_id').html();
        var scoreInput = $(this).attr('id');

        if (e.which == 48 || e.which == 96) {
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
        } else if (e.which === 8 || e.which === 46) {
            $(this).val('');
            $(this).removeClass('invalid-score');
            $(this).removeClass('match-complete');
            return false;
        } else {
            return false;
        }

        if($inputString.length === 0) {
            if (numberPressed === 7) {
                $(this).val('7-6');
            } else if(numberPressed === 6) {
                $(this).val(numberPressed + '-');
            } else if(numberPressed === 5) {
                $(this).val(numberPressed + '-7');
            } else {
                $(this).val(numberPressed + '-6');
            }
        }

        else if (($inputString.length === 2)) {
            if(numberPressed === 5 || numberPressed === 6) {
                return false;
            }
            $(this).val($inputString + numberPressed);
        }

        else if ($inputString.length === 3) {

            if (numberPressed === 7) {
                $inputString += ', 7-6'
                $(this).val($inputString);
            } else if (numberPressed === 6) {
                $inputString += ', 6-';
                checkScoreValidity = false;
                $(this).val($inputString);
            } else if(numberPressed === 5) {
                $inputString += ', 5-7';
                $(this).val($inputString);
            } else {
                $inputString += ', ' + numberPressed + '-6'
                $(this).val($inputString);
            }
            if(checkScoreValidity) {
                $setsWon = calculateSetsWon($inputString);
                if($setsWon === 0) {
                    $(this).addClass('invalid-score');
                } else if ($setsWon === 2) {
                    $(this).addClass('match-complete');
                    $winner = $(this).attr('data-winner');
                    $loser = $(this).attr('data-loser');
                    $winnerBracketPosition = $(this).attr('data-winner-bracket-position');
                    $loserBracketPosition = $(this).attr('data-loser-bracket-position');
                    $newWinnerBracketPosition = $(this).attr('data-new-winner-bracket-position');
                    $newLoserBracketPosition = $(this).attr('data-new-loser-bracket-position');
                    $score = $inputString;
                    saveScore($winner, $loser, $winnerBracketPosition, $loserBracketPosition, $newWinnerBracketPosition, $newLoserBracketPosition, $score, tournament_id, scoreInput);
                }
            }
        }

        else if (($inputString.length === 7)) {
            if(numberPressed === 5 || numberPressed === 6) {
                return false;
            }
            $inputString += numberPressed;
            $(this).val($inputString);
            $setsWon = calculateSetsWon($inputString);
            if($setsWon === 0) {
                $(this).addClass('invalid-score');
            } else if ($setsWon === 2) {
                $(this).addClass('match-complete');
                $winner = $(this).attr('data-winner');
                $loser = $(this).attr('data-loser');
                $winnerBracketPosition = $(this).attr('data-winner-bracket-position');
                $loserBracketPosition = $(this).attr('data-loser-bracket-position');
                $newWinnerBracketPosition = $(this).attr('data-new-winner-bracket-position');
                $newLoserBracketPosition = $(this).attr('data-new-loser-bracket-position');
                $score = $inputString;
                saveScore($winner, $loser, $winnerBracketPosition, $loserBracketPosition, $newWinnerBracketPosition, $newLoserBracketPosition, $score, tournament_id, scoreInput);
            }
        }

        else if ($inputString.length === 8) {
            checkScoreValidity = true;
            if (numberPressed === 7) {
                $inputString += ', 7-6'
                $(this).val($inputString);
            } else if (numberPressed === 6) {
                $inputString += ', 6-';
                checkScoreValidity = false;
                $(this).val($inputString);
            } else if(numberPressed === 5) {
                $inputString += ', 5-7';
                $(this).val($inputString);
            } else {
                $inputString += ', ' + numberPressed + '-6'
                $(this).val($inputString);
            }
            if(checkScoreValidity) {
                $setsWon = calculateSetsWon($inputString);
                if($setsWon === 0 || $setsWon === 1) {
                    $(this).addClass('invalid-score');
                } else if ($setsWon === 2) {
                    $(this).addClass('match-complete');
                    $winner = $(this).attr('data-winner');
                    $loser = $(this).attr('data-loser');
                    $winnerBracketPosition = $(this).attr('data-winner-bracket-position');
                    $loserBracketPosition = $(this).attr('data-loser-bracket-position');
                    $newWinnerBracketPosition = $(this).attr('data-new-winner-bracket-position');
                    $newLoserBracketPosition = $(this).attr('data-new-loser-bracket-position');
                    $score = $inputString;
                    saveScore($winner, $loser, $winnerBracketPosition, $loserBracketPosition, $newWinnerBracketPosition, $newLoserBracketPosition, $score, tournament_id, scoreInput);
                }
            }

        } else if ($inputString.length === 12) {
            if(numberPressed === 5 || numberPressed === 6) {
                return false;
            }
            $inputString += numberPressed;
            $(this).val($inputString);
            $setsWon = calculateSetsWon($inputString);
            if($setsWon === 0 || $setsWon === 1) {
                $(this).addClass('invalid-score');
            } else if ($setsWon === 2) {
                $(this).addClass('match-complete');
                $winner = $(this).attr('data-winner');
                $loser = $(this).attr('data-loser');
                $winnerBracketPosition = $(this).attr('data-winner-bracket-position');
                $loserBracketPosition = $(this).attr('data-loser-bracket-position');
                $newWinnerBracketPosition = $(this).attr('data-new-winner-bracket-position');
                $newLoserBracketPosition = $(this).attr('data-new-loser-bracket-position');
                $score = $inputString;
                saveScore($winner, $loser, $winnerBracketPosition, $loserBracketPosition, $newWinnerBracketPosition, $newLoserBracketPosition, $score, tournament_id, scoreInput);
            }
        }

        return false;
    });

    function saveScore(winner, loser, winnerBracketPosition, loserBracketPosition, newWinnerBracketPosition, newLoserBracketPosition, score, tournament_id,scoreInput) {
        $.ajax({
            type:'POST',
            url:'/saveScore',
            data:{
                winner:winner,
                loser:loser,
                winnerBracketPosition:winnerBracketPosition,
                loserBracketPosition:loserBracketPosition,
                score:score,
                tournament_id:tournament_id,
                scoreInput:scoreInput,
                newLoserBracketPosition:newLoserBracketPosition,
                newWinnerBracketPosition:newWinnerBracketPosition
            },
            success:function(data){
                alert(data.success);
            }
        });
    }

    function calculateSetsWon(string) {
        $setsWon = 0;
        $firstScore = parseInt(string.charAt(0));
        $secondScore = parseInt(string.charAt(2));
        $thirdScore = parseInt(string.charAt(5));
        $fourthScore = parseInt(string.charAt(7));

        console.log($firstScore);
        console.log($secondScore);
        console.log($thirdScore);
        console.log($fourthScore);

        if($firstScore > $secondScore) {
            $setsWon++;
        }
        if($thirdScore > $fourthScore) {
            $setsWon++;
        }
        if(string.length > 8){
            $fifthScore = parseInt(string.charAt(10));
            $sixthScore = parseInt(string.charAt(12));
            if($fifthScore > $sixthScore) {
                $setsWon++;
            }
        }

        return $setsWon;
    }

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
        'order': [],
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

        var tournament_id = $('#tournament_id').html();
        var schoolInviteeIDs = [];

        $('li.school_invitee').each(function(index, li) {
            console.log(index);
            console.log(li.dataset.value);
            schoolInviteeIDs.push(li.dataset.value);
        });

        $.ajax({
            type:'POST',
            url:'/inviteSchools',
            data:{schoolInviteeIDs:schoolInviteeIDs, tournament_id:tournament_id},
            success:function(data){
                alert(data.success);
            }
        });
    });
} );

$(document).on('click', '.advanceable', function() {

    let matchupAssociations = {};
    matchupAssociations['1-seed'] = '8-seed';
    matchupAssociations['2-seed'] = '7-seed';
    matchupAssociations['3-seed'] = '6-seed';
    matchupAssociations['4-seed'] = '5-seed';
    matchupAssociations['5-seed'] = '4-seed';
    matchupAssociations['6-seed'] = '3-seed';
    matchupAssociations['7-seed'] = '2-seed';
    matchupAssociations['8-seed'] = '1-seed';

    matchupAssociations['first-winners-round-one-top'] = 'first-winners-round-one-bottom';
    matchupAssociations['first-winners-round-one-bottom'] = 'first-winners-round-one-top';
    matchupAssociations['second-winners-round-one-top'] = 'second-winners-round-one-bottom';
    matchupAssociations['second-winners-round-one-bottom'] = 'second-winners-round-one-top';
    matchupAssociations['first-consolation-round-one-top'] = 'first-consolation-round-one-bottom';
    matchupAssociations['first-consolation-round-one-bottom'] = 'first-consolation-round-one-top';
    matchupAssociations['second-consolation-round-one-top'] = 'second-consolation-round-one-bottom';
    matchupAssociations['second-consolation-round-one-bottom'] = 'second-consolation-round-one-top';

    matchupAssociations['first-winners-round-two-top'] = 'first-winners-round-two-bottom';
    matchupAssociations['first-winners-round-two-bottom'] = 'first-winners-round-two-top';
    matchupAssociations['first-consolation-round-two-top'] = 'first-consolation-round-two-bottom';
    matchupAssociations['first-consolation-round-two-bottom'] = 'first-consolation-round-two-top';

    matchupAssociations['first-winners-lower-bracket-round-one-top'] = 'first-winners-lower-bracket-round-one-bottom';
    matchupAssociations['first-winners-lower-bracket-round-one-bottom'] = 'first-winners-lower-bracket-round-one-top';
    matchupAssociations['first-consolation-lower-bracket-round-one-top'] = 'first-consolation-lower-bracket-round-one-bottom';
    matchupAssociations['first-consolation-lower-bracket-round-one-bottom'] = 'first-consolation-lower-bracket-round-one-top';


    let winningPathAssociations = {};
    winningPathAssociations['1-seed'] = 'first-winners-round-one-top';
    winningPathAssociations['2-seed'] = 'second-winners-round-one-bottom';
    winningPathAssociations['3-seed'] = 'second-winners-round-one-top';
    winningPathAssociations['4-seed'] = 'first-winners-round-one-bottom';
    winningPathAssociations['5-seed'] = 'first-winners-round-one-bottom';
    winningPathAssociations['6-seed'] = 'second-winners-round-one-top';
    winningPathAssociations['7-seed'] = 'second-winners-round-one-bottom';
    winningPathAssociations['8-seed'] = 'first-winners-round-one-top';

    winningPathAssociations['first-winners-round-one-top'] = 'first-winners-round-two-top';
    winningPathAssociations['first-winners-round-one-bottom'] = 'first-winners-round-two-top';
    winningPathAssociations['second-winners-round-one-top'] = 'first-winners-round-two-bottom';
    winningPathAssociations['second-winners-round-one-bottom'] = 'first-winners-round-two-bottom';

    winningPathAssociations['first-consolation-round-one-top'] = 'first-consolation-round-two-top';
    winningPathAssociations['first-consolation-round-one-bottom'] = 'first-consolation-round-two-top';
    winningPathAssociations['second-consolation-round-one-top'] = 'first-consolation-round-two-bottom';
    winningPathAssociations['second-consolation-round-one-bottom'] = 'first-consolation-round-two-bottom';

    winningPathAssociations['first-winners-round-two-top'] = 'champion';
    winningPathAssociations['first-winners-round-two-bottom'] = 'champion';

    winningPathAssociations['first-consolation-round-two-top'] = 'consolation-champion';
    winningPathAssociations['first-consolation-round-two-bottom'] = 'consolation-champion';

    winningPathAssociations['first-winners-lower-bracket-round-one-top'] = 'third-place';
    winningPathAssociations['first-winners-lower-bracket-round-one-bottom'] = 'third-place';

    winningPathAssociations['first-consolation-lower-bracket-round-one-top'] = 'seventh-place';
    winningPathAssociations['first-consolation-lower-bracket-round-one-bottom'] = 'seventh-place';


    let losingPathAssociations = {};
    losingPathAssociations['1-seed'] = 'first-consolation-round-one-top';
    losingPathAssociations['2-seed'] = 'second-consolation-round-one-bottom';
    losingPathAssociations['3-seed'] = 'second-consolation-round-one-top';
    losingPathAssociations['4-seed'] = 'first-consolation-round-one-bottom';
    losingPathAssociations['5-seed'] = 'first-consolation-round-one-bottom';
    losingPathAssociations['6-seed'] = 'second-consolation-round-one-top';
    losingPathAssociations['7-seed'] = 'second-consolation-round-one-bottom';
    losingPathAssociations['8-seed'] = 'first-consolation-round-one-top';

    losingPathAssociations['first-consolation-round-one-top'] = 'first-consolation-lower-bracket-round-one-top';
    losingPathAssociations['first-consolation-round-one-bottom'] = 'first-consolation-lower-bracket-round-one-top';

    losingPathAssociations['second-consolation-round-one-top'] = 'first-consolation-lower-bracket-round-one-bottom';
    losingPathAssociations['second-consolation-round-one-bottom'] = 'first-consolation-lower-bracket-round-one-bottom';

    losingPathAssociations['first-winners-round-one-top'] = 'first-winners-lower-bracket-round-one-top';
    losingPathAssociations['first-winners-round-one-bottom'] = 'first-winners-lower-bracket-round-one-top';

    losingPathAssociations['second-winners-round-one-top'] = 'first-winners-lower-bracket-round-one-bottom';
    losingPathAssociations['second-winners-round-one-bottom'] = 'first-winners-lower-bracket-round-one-bottom';


    $winningPlayerBracketPosition = $(this).attr('id');
    $winningPlayerName = $(this).html();
    $winningPlayerID = $(this).attr('data-id');

    $losingPlayerBracketPosition = matchupAssociations[$winningPlayerBracketPosition];
    $losingPlayerName = $('#' + $losingPlayerBracketPosition).html();
    $losingPlayerID = $('#' + $losingPlayerBracketPosition).attr('data-id');

    $winningPath = winningPathAssociations[$winningPlayerBracketPosition];
    $losingPath = losingPathAssociations[$losingPlayerBracketPosition];

    $('#' + $winningPlayerBracketPosition).removeClass('winner');
    $('#' + $losingPlayerBracketPosition).removeClass('winner');

    $('#' + $winningPath).html($winningPlayerName);
    $('#' + $winningPath).addClass('advanceable');
    $('#' + $winningPath).attr('data-id', $winningPlayerID);
    $('#' + $winningPath + '-score-input').removeAttr('hidden');
    $('#' + $winningPath + '-score-input').attr('data-winner', $winningPlayerID);
    $('#' + $winningPath + '-score-input').attr('data-loser', $losingPlayerID);
    $('#' + $winningPath + '-score-input').attr('data-winner-bracket-position', $winningPlayerBracketPosition);
    $('#' + $winningPath + '-score-input').attr('data-loser-bracket-position', $losingPlayerBracketPosition);
    $('#' + $winningPath + '-score-input').attr('data-new-winner-bracket-position', $winningPath);
    $('#' + $winningPath + '-score-input').attr('data-new-loser-bracket-position', $losingPath);

    $('#' + $losingPath).html($losingPlayerName);
    $('#' + $losingPath).addClass('advanceable');
    $('#' + $losingPath).attr('data-id', $losingPlayerID);

    $(this).addClass('winner');
    if($winningPath == 'champion') {
        $('#champion').addClass('winner');
    } else if ($winningPath == 'consolation-champion') {
        $('#consolation-champion').addClass('winner');
    } else if ($winningPath == 'third-place') {
        $('#third-place').addClass('winner');
    } else if ($winningPath == 'seventh-place') {
        $('#seventh-place').addClass('winner');
    }
});


