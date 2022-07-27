$(document).ready( function () {

    //related to dropdown sorting
    $('.select2').select2();
    $('.select2').select2({
        width: 'resolve' // need to override the changed default
    });

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
                $fullTournament = data.fullTournament[bracket];

                var brackets = [
                    'One Singles',
                    'Two Singles',
                    'One Doubles',
                    'One Doubles',
                    'Two Doubles',
                    'Two Doubles'
                ]

                $increment = 1;
                for (const $key in $players) {
                    $player = $players[$key];

                    $sequence = $player.position;
                    $id = $player.id;
                    $position = $player[bracket];
                    $inTournament = $player['in_tournament'];
                    $name = $player.first_name + ' ' + $player.last_name;
                    $conference = $player.grade;
                    $bracketName = $player.bracket_name;
                    $realPlayer = $player.real_player;

                    // if (($increment in brackets) && (brackets[0] != $bracketName)){
                    //     $editRosterDataTable.row.add({
                    //         'seq': $increment,
                    //         'id': 0,
                    //         'position': brackets[$increment],
                    //         'name': "-",
                    //         'conference': "-",
                    //         'actions': "-"
                    //     }).draw().node();

                    if($position === true || $inTournament || !$realPlayer || $fullTournament) {
                        $actions = "";
                    } else if ($realPlayer && !$fullTournament){
                        $actions = '<img class="add-player-to-tournament-action" data-id="add-player-to-tournament-action" src="/images/plus-sign.jpg">'
                    }


                    var row = $editRosterDataTable.row.add({
                        'seq': $increment,
                        'id': $id,
                        'position': $bracketName,
                        'name': $name,
                        'conference': $conference,
                        'actions': $actions
                    }).draw().node();

                    if($position === true) {
                        $(row).addClass('highlight-player');
                    }

                    if(($position === false || $position == null) && $inTournament) {
                        $(row).addClass('pending-invite');
                    }

                    $('#seedsTable').DataTable().rows(function (idx, data, node) {
                        if(data.id === $player.id && data.id != 0){
                            $(node).addClass('highlight-player');
                        }
                    });
                    $increment++;
                }
            }
        });
        activateAddPlayerToTournamentActions();
    }

    function activateAddPlayerToTournamentActions() {
        $('[data-id=add-player-to-tournament-action]').click(function(e) {
            e.preventDefault();

            var currentRow = $(this).closest('tr');
            var data = $('#editRosterTable').DataTable().row(currentRow).data();

            var tournament_id = $('#tournament_id').html();
            var bracket = $('.selected-button').attr('id');
            var player_id = data.id;

            $.ajax({
                type:'POST',
                async:false,
                url:'/addNewSeededPlayer',
                data:{tournament_id:tournament_id, bracket:bracket, player_id:player_id},
                success:function(data){
                    fillBracketData();
                    fillRosterTable();
                }
            });

        });
    }

    fillBracketData();
    fillRosterTable();
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
                $reorder = '<img class="reorder-icon" src="/images/reorder-icon.png">'
                $actions = '<img class="remove-seeded-player" data-id="remove-seeded-player" src="/images/x-icon.png"</img>';

                for (const [$position, $value] of Object.entries($bracketPositions)) {
                    if(!($position == 'tournament_id' || $position == 'bracket' || $position == 'id' || $position == 'created_at' || $position == 'updated_at')) {
                        $increment++;
                        $positionWithDashes = $position.replace(/_/g, '-');
                        $isASeed = $position.length < 8;

                        if($position.indexOf('school') !== -1) {
                            //does include the substring school
                            $('#' + $positionWithDashes).html($value);
                        } else if ($position.indexOf('id') !== -1){
                            //does include the substring id;
                            $positionCorrected = $positionWithDashes.slice(0, -3);
                            $('#' + $positionCorrected).attr('data-id', $value);
                        } else {


                            if($value === 0) {
                                $name = "";
                            } else if (!$isASeed){
                                $bracketPositionTitle = $position + '_school';
                                $name = $bracketPositions[$bracketPositionTitle];
                            } else {
                                $name = $value;
                            }

                            $('#' + $positionWithDashes).html($name);
                            if(!$nonAdvanceablePositions.includes($positionWithDashes)) {
                                $('#' + $positionWithDashes).addClass('advanceable');
                            }
                        }

                        if(!$isASeed) {
                            continue;
                        }


                        if($value === "-") {
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
                    $('#' + $match.loser_bracket_position).removeClass('winner');
                    $('#' + $match.loser_bracket_position + '-school').removeClass('winner');
                    $('#' + $match.winner_bracket_position).addClass('winner');
                    $('#' + $match.winner_bracket_position + '-school').addClass('winner');
                    $('#' + $match.score_input).removeAttr('hidden').val($match.score);
                    if($match.score !== null) {
                        $('#' + $match.score_input).addClass('match-complete');
                    }
                    if($winnerPositions.includes($winnerPosition)) {
                        $('#' + $winnerPosition).addClass('winner');
                    }
                });

            }
        });

        activateRemoveSeededPlayerButtons();
    }

    function activateRemoveSeededPlayerButtons() {
        $('[data-id=remove-seeded-player]').click(function(e) {
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
            'actions': '<span data-id="remove-school-button" aria-hidden="true">&#10060;</span>',
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

        activateRemoveSchoolButton();

    });
    activateRemoveSchoolButton();

    function activateRemoveSchoolButton() {
        $('[data-id=remove-school-button]').click(function(e) {
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
        $winningSchoolName = $($selectorStringForID + '-school').html();
        $winningPlayerID = $($selectorStringForID).attr('data-id');

        $losingPlayerBracketPosition = matchupAssociations[$winningPlayerBracketPosition];
        $losingSchoolName = $('#' + $losingPlayerBracketPosition + '-school').html();
        $losingPlayerID = $('#' + $losingPlayerBracketPosition).attr('data-id');

        $winningPath = winningPathAssociations[$winningPlayerBracketPosition];
        $losingPath = losingPathAssociations[$losingPlayerBracketPosition];

        // $('#' + $winningPlayerBracketPosition).removeClass('winner');
        // $('#' + $losingPlayerBracketPosition).removeClass('winner');
        // $('#' + $winningPlayerBracketPosition + '-school').removeClass('winner');
        // $('#' + $losingPlayerBracketPosition + '-school').removeClass('winner');
        //
        // $('#' + $winningPath).html($winningSchoolName);
        // $('#' + $winningPath).addClass('advanceable');
        // $('#' + $winningPath).attr('data-id', $winningPlayerID);
        // $('#' + $winningPath + '-score-input').removeAttr('hidden');
        // $('#' + $winningPath + '-score-input').html('');
        // $('#' + $winningPath + '-score-input').attr('data-winner', $winningPlayerID);
        // $('#' + $winningPath + '-score-input').attr('data-loser', $losingPlayerID);
        //
        // $('#' + $losingPath).html($losingSchoolName);
        // $('#' + $losingPath).addClass('advanceable');
        // $('#' + $losingPath).attr('data-id', $losingPlayerID);

        $bracket = $('.selected-button').attr('id');
        $tournament_id = $('#tournament_id').html();

        saveMatch($bracket, $winningPlayerID, $losingPlayerID, $winningPlayerBracketPosition, $losingPlayerBracketPosition, '', $tournament_id);

        fillBracketData();

        // $($selectorStringForID).addClass('winner');
        // $('#' + $($selectorStringForID).attr('id') + '-school').addClass('winner');
        // if($winningPath == 'champion') {
        //     $('#champion').addClass('winner');
        // } else if ($winningPath == 'consolation-champion') {
        //     $('#consolation-champion').addClass('winner');
        // } else if ($winningPath == 'third-place') {
        //     $('#third-place').addClass('winner');
        // } else if ($winningPath == 'seventh-place') {
        //     $('#seventh-place').addClass('winner');
        // }
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
            async:false,
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

});

