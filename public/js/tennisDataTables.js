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
            selector: '.reorder-cell',
            snapX:true,
            dataSrc:'seq'
        },
        bInfo:false,
        "lengthChange": false,

        'columns': [
            { data: 'seq' }, /* index = 0 */
            { data: 'id' }, /* index = 1 */
            { data: 'reorder'},/* index = 2 */
            { data: 'position' }, /* index = 3 */
            { data: 'name' }, /* index = 4 */
            { data: 'conference' }, /* index = 5 */
            { data: 'actions' }, /* index = 6 */
        ],

        'columnDefs': [
            { targets: [0,1], visible: false },
            { targets: [2], orderable: false, className:'reorder-cell'},
            { targets: [3,5,6], orderable: false, 'className': 'center-align' },
            { targets: [4], orderable:false}
        ]
        //this will be useful for adding a button in the same line as the search bar for creating tournaments etc
        // "initComplete": function( settings, json ) {
        //     $('#myTable_filter').html("<div id='myTable_filter' class='dataTables_filter'><div><label>Search:<input type='search' class='' placeholder='' aria-controls='myTable'></label><button id='roster_button' type='submit' class='btn btn-primary'>Create</button></div></div>");
        // },
    } );

    var editRosterTable = $('#editRosterTable').DataTable( {
        paging:false,
        searching:false,
        rowReorder: {
            selector: 'tr',
            snapX:true,
            dataSrc:'seq'
        },
        bInfo:false,
        "lengthChange": false,

        'columns': [
            { data: 'seq' }, /* index = 0 */
            { data: 'id' }, /* index = 1 */
            { data: 'position' }, /* index = 2 */
            { data: 'name' }, /* index = 3 */
            { data: 'conference' }, /* index = 4 */
            { data: 'actions' }, /* index = 5 */
        ],

        'columnDefs': [
            { targets: [0,1], visible: false },
            { targets: [2,4,5], orderable: false, 'className': 'center-align' },
            { targets: [3], orderable:false}
        ]
        //this will be useful for adding a button in the same line as the search bar for creating tournaments etc
        // "initComplete": function( settings, json ) {
        //     $('#myTable_filter').html("<div id='myTable_filter' class='dataTables_filter'><div><label>Search:<input type='search' class='' placeholder='' aria-controls='myTable'></label><button id='roster_button' type='submit' class='btn btn-primary'>Create</button></div></div>");
        // },
    } );

    seedsTable.on('row-reordered', function (e, diff, edit) {

        $('#seedsTable').DataTable().rows(function () {
            // data.position = (rowIdx + 1) + '_seed';
            // console.log(data.position);
        }).draw();




        var table = $("#seedsTable").DataTable();

        table.one( 'draw', function () {
            var bracket = $('.selected-button').attr('id');
            var tournament_id = $('#tournament_id').html();

            let playerSeeds = {};
            table.rows().every(function(index, element) {
                var seed = this.data().position;
                var id = this.data().id;
                playerSeeds[seed] = id;
            });

            $.ajax({
                type:'POST',
                url:'/saveTournamentSeeds',
                data:{playerSeeds:playerSeeds, tournament_id:tournament_id, bracket:bracket},
                success:function(data){
                    fillBracketData();
                }
            });

        });
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

    $(".bracket-button").click(function(e){
        e.preventDefault();
        $(".bracket-button").removeClass('selected-button');
        $(this).addClass('selected-button');

        fillBracketData();
        $showRosterCurrentlyEnabled = $("#showEditRosterTable").hasClass('show-roster-enabled');
        if($showRosterCurrentlyEnabled) {
            fillRosterTable();
        }
    });

    function removePlayerHighlights() {
        $('#seedsTable').DataTable().rows(function (idx, data, node) {
            $(node).removeClass('highlight-player');
        });
    }

    $("#showEditRosterTable").click(function(e) {
        e.preventDefault();
        $currentlyEnabled = $("#showEditRosterTable").hasClass('show-roster-enabled');
        if($currentlyEnabled) {
            $('#editRosterTable').hide();
            $("#showEditRosterTable").removeClass('show-roster-enabled');
            removePlayerHighlights();

            // $('#seedsTable > tbody > tr').each(function(index, tr) {
            //     tr.removeClass('accepted-invite');
            // });
        } else {
            $('#editRosterTable').show();
            $("#showEditRosterTable").addClass('show-roster-enabled');
            removePlayerHighlights();
        }
        fillRosterTable();
    });

    $("#rosterSelect").change(function(e) {
        e.preventDefault();
        removePlayerHighlights();
        fillRosterTable();
    });

    function fillRosterTable() {
        $editRosterDataTable = $("#editRosterTable").DataTable();
        $editRosterDataTable.clear();
        var tournament_id = $('#tournament_id').html();
        var gender = $('.selected-button').attr('data-gender');
        var bracket = $('.selected-button').attr('id');
        var school_id = $('#rosterSelect').find(':selected').val();

        $.ajax({
            type:'POST',
            url:'/getRosterForTournament',
            async:false,
            data:{gender:gender, school_id:school_id, tournament_id:tournament_id},
            success:function(data){

                $players = data.schoolPlayers;

                for (const $key in $players) {
                    $player = $players[$key];

                    $sequence = $player.position;
                    $id = $player.id;
                    $position = $player[bracket];
                    $name = $player.first_name + ' ' + $player.last_name;
                    $conference = 'yeah';

                    if($position === true) {
                        $actions = "";
                    } else {
                        $actions = '<span data-id="remove_school_button" aria-hidden="true">&#10060;</span>';
                    }

                    var row = $editRosterDataTable.row.add({
                        'seq': $player.id,
                        'id': $increment,
                        'position': $position,
                        'name': $name,
                        'conference': $conference,
                        'actions': $actions
                    }).draw().node();

                    if($position === true) {
                        $(row).addClass('highlight-player');
                    }

                    $('#seedsTable').DataTable().rows(function (idx, data, node) {
                        if(data.id === $player.id){
                            $(node).addClass('highlight-player');
                        }
                    });


                }
            }
        });
    }

    $("#not_listed").change(function() {
        if (this.checked) {
            $(".toggle_show").removeAttr('disabled').attr('required', true);
            $("#switch_button_name").html("Add New School");
        } else {
            $(".toggle_show").attr('disabled', true).val('').attr('required', false);
            $("#switch_button_name").html("Tie Existing School");
        }
    });

    fillBracketData();
    function fillBracketData() {
        var tournament_id = $('#tournament_id').html();
        var requestedBracket = $('.selected-button').attr('id');

        $.ajax({
            async: false,
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

                $seedsDataTable = $('#seedsTable').DataTable();
                $seedsDataTable.clear();

                $increment = 0;
                $reorder = '<img className="reorder-icon" style="width:25px;height:25px" src="/images/reorder-icon.png">'
                $actions = '<span data-id="remove_seeded_player" aria-hidden="true">&#10060;</span>';

                for (const [$position, $value] of Object.entries($bracketPositions)) {
                    if(!($position == 'tournament_id' || $position == 'bracket' || $position == 'id' || $position == 'created_at' || $position == 'updated_at')) {
                        $increment++;
                        $positionWithDashes = $position.replace(/_/g, '-');

                        if($position.indexOf('school') !== -1) {
                            //does include the substring school
                            $('#' + $positionWithDashes).html($value);
                        } else if ($position.indexOf('id') !== -1){
                            //does include the substring id;
                            $positionCorrected = $positionWithDashes.slice(0, -3);
                            $('#' + $positionCorrected).attr('data-id', $value);
                        } else {

                            $name = $value;
                            if($name === 0) {
                                $name = "";
                            }

                            $('#' + $positionWithDashes).html($name);
                            if(!$nonAdvanceablePositions.includes($positionWithDashes)) {
                                $('#' + $positionWithDashes).addClass('advanceable');
                            }
                        }

                        $isASeed = $position.length < 8;
                        if(!$isASeed) {
                            continue;
                        }


                        if($value === 0) {
                            $seedsDataTable.row.add({
                                'seq': $increment,
                                'id': 0,
                                'reorder': $reorder,
                                'position': $positionWithDashes,
                                'name': "-",
                                'conference': "-",
                                'actions': "-",
                            }).draw();
                            continue;
                        }



                        $positionConference = $position + '_conference';
                        $positionID = $position + '_id';

                        if($isASeed) {
                            console.log($increment);
                            $seedsDataTable.row.add({
                                'seq': $increment,
                                'id': $bracketPositions[$positionID],
                                'reorder': $reorder,
                                'position': $positionWithDashes,
                                'name': $value,
                                'conference': $bracketPositions[$positionConference],
                                'actions': $actions,
                            }).draw();
                        }
                    }
                }

                $matches = data.matches;
                $matches.forEach(function($match, $index) {
                    $winnerPosition = $match.score_input.slice(0, -12);
                    $winnerPositions = ['champion', 'consolation-champion', 'third-place', 'seventh-place'];
                    $('#' + $match.winner_bracket_position).addClass('winner');
                    $('#' + $match.winner_bracket_position + '-school').addClass('winner');
                    $('#' + $match.score_input).removeAttr('hidden').val($match.score).addClass('match-complete');
                    if($winnerPositions.includes($winnerPosition)) {
                        $('#' + $winnerPosition).addClass('winner');
                    }
                });

            }
        });

        activate_remove_seeded_player_buttons();
    }

    function activate_remove_seeded_player_buttons() {
        $('[data-id=remove_seeded_player]').click(function(e) {
            e.preventDefault();

            var currentRow = $(this).closest('tr');
            var data = $('#seedsTable').DataTable().row(currentRow).data();

            var tournament_id = $('#tournament_id').html();
            var bracket = $('.selected-button').attr('id');
            var player_id = data.id;
            var seed = data.position.replace(/-/g, '_');

            $.ajax({
                type:'POST',
                async:false,
                url:'/removeSeededPlayer',
                data:{tournament_id:tournament_id, bracket:bracket, player_id:player_id, seed:seed},
                success:function(data){
                    fillBracketData();
                    fillRosterTable();
                }
            });

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

    var invitesTable = $('#invitesTable').DataTable( {
        paging:false,
        "lengthChange": false,
        'columns': [
            { data: 'seq' }, /* index = 0 */
            { data: 'id' }, /* index = 1 */
            { data: 'name' }, /* index = 2 */
            { data: 'invite_status' }, /* index = 3 */
            { data: 'conference' }, /* index = 4 */
            { data: 'actions' }, /* index = 5 */
        ],
        'order': [],
        searching:false,

        'columnDefs': [
            { targets: [0], visible: false, type:'de_datetime' },
            { targets: [1], visible: false },
            { targets: [2,3,4,5], orderable: false }
        ],

        'autoWidth': false


    } );


    $('#schools_to_invite').change(function() {
        $schoolID = $('#schools_to_invite').find(":selected").val();
        $htmlString = $('#list_to_invite').html();
        $schoolSelected =  $('#schools_to_invite').find(":selected").text();
        $conference = $('#schools_to_invite').find(":selected").attr('data-conference');
        $inviteStatus = $('#schools_to_invite').find(":selected").attr('data-invite-status');

        $removed = ($('#schools_to_invite').find(":selected").remove());

        table = $('#invitesTable').DataTable();

        let newdata = Array.from(table.data());
        newdata.unshift({
            'seq': Date.now,
            'id': $schoolID,
            'name': $schoolSelected,
            'invite_status': $inviteStatus ?? 'Not Sent',
            'conference': $conference,
            'actions': '<span data-id="remove_school_button" aria-hidden="true">&#10060;</span>',
        });
        table.clear();
        for (const row of newdata) {
            table.row.add(row);
        }
        table.draw();
        $increment = 0;
        $('#invitesTable tr').each(function(){
            $increment++;
            if($increment === 1) {
                return;
            }
            $inviteStatus = $(this).find('td:nth-child(2)').text();
            $removeTD = $(this).find('td:nth-child(4)');
            $removeTD.addClass('center-align');
            if($inviteStatus === "Accepted") {
                $(this).addClass('accepted-invite');
            }
            if($inviteStatus === "Pending") {
                $(this).addClass('pending-invite');
            }
            if($inviteStatus === "Declined") {
                $(this).addClass('declined-invite');
            }
        });

        $('#list_to_invite').html($htmlString);

        activate_remove_school_button();

    });
    activate_remove_school_button();

    function activate_remove_school_button() {
        $('[data-id=remove_school_button]').click(function(e) {
            e.preventDefault();

            var currentRow = $(this).closest('tr');
            var data = $('#invitesTable').DataTable().row(currentRow).data();

            //TRYING TO LET YOU ADD BACK IN A SCHOOL THAT HAS ALREADY BEEN REMOVED ONCEEEE
            $('#schools_to_invite').append($('<option>', {
                value:data['id'],
                text:data['name']
            }));

            $('#schools_to_invite option[value="' + data['id'] + '"]').attr('data-conference', data['conference']);
            $('#schools_to_invite option[value="' + data['id'] + '"]').attr('data-invite-status', data['invite_status']);

            $('#invitesTable').DataTable().row(currentRow).remove().draw();

        });
    }

    $('#select2-schools_to_invite-container').click(function() {
        //this prevents the first option in the dropdown from locking after a selection has been made
        $("#select2-schools_to_invite-results li:nth-child(2)").attr('aria-selected', false);
    });

    $("#invite_schools_button").click(function(e){
        e.preventDefault();

        var tournament_id = $('#tournament_id').html();
        // var schoolInviteeIDs = [];

        // $('li.school_invitee').each(function(index, li) {
        //     schoolInviteeIDs.push(li.dataset.value);
        // });

        var inviteeSchoolIDs = [];
        var inviteStatuses = [];
        var inviteStatus = 'empty';

        $increment = 0;
        $('#invitesTable tr').each(function(){
            console.log($increment);
            $increment++;
            if($increment === 1) {
                return;
            }

            inviteStatus = $(this).find('td:nth-child(2)').text().toLowerCase();
            var data = $('#invitesTable').DataTable().row(this).data();

            inviteeSchoolIDs.push(data['id']);
            inviteStatuses.push(inviteStatus);
        });

        $.ajax({
            type:'POST',
            url:'/inviteSchools',
            data:{inviteeSchoolIDs:inviteeSchoolIDs, inviteStatuses:inviteStatuses, tournament_id:tournament_id},
            success:function(data){
                $increment = 0;
                $('#invitesTable tr').each(function(){
                    $increment++;
                    if($increment === 1) {
                        return;
                    }
                    inviteStatus = $(this).find('td:nth-child(2)').text();
                    if(inviteStatus === 'Not Sent') {
                        $(this).find('td:nth-child(2)').text('Pending');
                        $(this).addClass('pending-invite');
                    }
                });
                alert('Invites Sent and Changes Saved.')
            }
        });
    });

    $("#decline_tournament_invitation_button").click(function(e){
        e.preventDefault();

        var tournament_id = $('#tournament_id').html();
        var user_school_id = 80;

        $.ajax({
            type:'POST',
            url:'/declineInvite',
            data:{tournament_id:tournament_id, user_school_id:user_school_id},
            success:function(data){
                window.location = data.redirect_url;
            }
        });
    });

    $("#accept_tournament_invitation_button").click(function(e){
        e.preventDefault();

        var tournament_id = $('#tournament_id').html();

        $.ajax({
            type:'POST',
            url:'/acceptInvite',
            data:{tournament_id:tournament_id},
            success:function(data){
                window.location = data.redirect_url;
            }
        });
    });

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

    $IDwithNoSchoolString = $(this).attr('id').replace('-school','');
    $selectorStringForID = '#' + $IDwithNoSchoolString;

    $winningPlayerBracketPosition = $IDwithNoSchoolString;
    $winningPlayerName = $($selectorStringForID).html();
    $winningPlayerID = $($selectorStringForID).attr('data-id');

    $losingPlayerBracketPosition = matchupAssociations[$winningPlayerBracketPosition];
    $losingPlayerName = $('#' + $losingPlayerBracketPosition).html();
    $losingPlayerID = $('#' + $losingPlayerBracketPosition).attr('data-id');

    $winningPath = winningPathAssociations[$winningPlayerBracketPosition];
    $losingPath = losingPathAssociations[$losingPlayerBracketPosition];

    $('#' + $winningPlayerBracketPosition).removeClass('winner');
    $('#' + $losingPlayerBracketPosition).removeClass('winner');
    $('#' + $winningPlayerBracketPosition + '-school').removeClass('winner');
    $('#' + $losingPlayerBracketPosition + '-school').removeClass('winner');

    $('#' + $winningPath).html($winningPlayerName);
    $('#' + $winningPath).addClass('advanceable');
    $('#' + $winningPath).attr('data-id', $winningPlayerID);
    $('#' + $winningPath + '-score-input').removeAttr('hidden');
    $('#' + $winningPath + '-score-input').html('');
    $('#' + $winningPath + '-score-input').attr('data-winner', $winningPlayerID);
    $('#' + $winningPath + '-score-input').attr('data-loser', $losingPlayerID);
    // $('#' + $winningPath + '-score-input').attr('data-winner-bracket-position', $winningPlayerBracketPosition);
    // $('#' + $winningPath + '-score-input').attr('data-loser-bracket-position', $losingPlayerBracketPosition);
    // $('#' + $winningPath + '-score-input').attr('data-new-winner-bracket-position', $winningPath);
    // $('#' + $winningPath + '-score-input').attr('data-new-loser-bracket-position', $losingPath);

    $('#' + $losingPath).html($losingPlayerName);
    $('#' + $losingPath).addClass('advanceable');
    $('#' + $losingPath).attr('data-id', $losingPlayerID);

    $bracket = $('.selected-button').attr('id');
    $tournament_id = $('#tournament_id').html();

    saveMatch($bracket, $winningPlayerID, $losingPlayerID, $winningPlayerBracketPosition, $losingPlayerBracketPosition, '', $tournament_id);

    $($selectorStringForID).addClass('winner');
    $('#' + $($selectorStringForID).attr('id') + '-school').addClass('winner');
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

function saveScore(bracket, tournament_id, score, scoreInput) {
    $.ajax({
        type:'POST',
        url:'/saveScore',
        data:{
            bracket:bracket,
            tournament_id:tournament_id,
            score: score,
            scoreInput:scoreInput
        },
        success:function(data){
            console.log('Saved score successfully');
        }
    });
}

function saveMatch(bracket, winner, loser, winnerBracketPosition, loserBracketPosition, score, tournament_id) {
    $.ajax({
        type:'POST',
        url:'/saveMatch',
        data:{
            bracket:bracket,
            winner:winner,
            loser:loser,
            score:score,
            tournament_id:tournament_id,
            winnerBracketPosition:winnerBracketPosition,
            loserBracketPosition:loserBracketPosition,
        },
        success:function(data){
            console.log('Saved match successfully');
        }
    });
}






