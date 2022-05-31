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

    var seedsTable = $('#seedsTable').DataTable( {
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

    $(".bracket-button").click(function(e){
        e.preventDefault();
        $(".bracket-button").removeClass('selected-button');
        $(this).addClass('selected-button');

        const tournament_id = $('#tournament_id').html();
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

                $bracketPositions = data.bracketPositions;
                $nonAdvanceablePositions = ['champion', 'consolation-champion', 'third-place', 'seventh-place'];
                for (const [$position, $value] of Object.entries($bracketPositions)) {
                    if(!($position == 'tournament_id' || $position == 'bracket' || $position == 'id' || $position == 'created_at' || $position == 'updated_at')) {
                        if($value === 0) {
                            continue;
                        }
                        $positionWithDashes = $position.replace(/_/g, '-');
                        if($position.indexOf('school') !== -1) {
                            //does include the substring school
                            $('#' + $positionWithDashes).html($value);
                        } else if ($position.indexOf('id') !== -1){
                            //does include the substring id;
                            $positionCorrected = $positionWithDashes.slice(0, -3);
                            $('#' + $positionCorrected).attr('data-id', $value);
                        } else {
                            $('#' + $positionWithDashes).html($value);
                            if(!$nonAdvanceablePositions.includes($positionWithDashes)) {
                                $('#' + $positionWithDashes).addClass('advanceable');
                            }
                        }
                    }
                }

                $matches = data.matches;
                $matches.forEach(function($match, $index) {
                    $winnerPosition = $match.score_input.slice(0, -12);
                    $winnerPositions = ['champion', 'consolation-champion', 'third-place', 'seventh-place'];
                    $('#' + $match.winner_bracket_position).addClass('winner');
                    $('#' + $match.score_input).removeAttr('hidden').val($match.score).addClass('match-complete');
                    if($winnerPositions.includes($winnerPosition)) {
                        $('#' + $winnerPosition).addClass('winner');
                    }
                });

            }
        });
    }


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
                $winner = $(this).attr('data-winner');
                $loser = $(this).attr('data-loser');
                $winnerBracketPosition = $(this).attr('data-winner-bracket-position');
                $loserBracketPosition = $(this).attr('data-loser-bracket-position');
                $newWinnerBracketPosition = $(this).attr('data-new-winner-bracket-position');
                $newLoserBracketPosition = $(this).attr('data-new-loser-bracket-position');

                saveScore($bracket, $winner, $loser, $winnerBracketPosition, $loserBracketPosition, $newWinnerBracketPosition, $newLoserBracketPosition, $score, tournament_id, scoreInput);

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

    function saveScore(bracket, winner, loser, winnerBracketPosition, loserBracketPosition, newWinnerBracketPosition, newLoserBracketPosition, score, tournament_id,scoreInput) {
        $.ajax({
            type:'POST',
            url:'/saveScore',
            data:{
                bracket:bracket,
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


