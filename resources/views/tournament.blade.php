@extends('layouts.app')

@section('content')

    <div class="container-fluid">


        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div id="tournament_id" style="display:none">{{$tournament->id}}</div>
                    <div class="card-header">{{$tournament->name}}</div>
{{--                    <button class="btn btn-primary col-md-2 offset-md-5" onclick="location.href='/createtournament/{{$tournament->id}}'" type="button">Edit Tournament</button>--}}

                    <div class="row">
                        <div class="col-lg-6">
                            <table id="example" class="table table-striped" style="width:100%">
                                <tbody>
                                <tr>
                                    <td>{{date('m-d-Y', strtotime($tournament->date))}} ({{date('g:ia', strtotime($tournament->time))}} Start)</td>

                                </tr>
                                <tr>
                                    <td>Location: {{$tournament->location_name}}</td>

                                </tr>
                                <tr>
                                    <td>Address: {{$tournament->address}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table id="example" class="table table-striped" style="width:100%">
                                <tbody>
                                <tr>
                                    <td>Host: <a href="/school/{{$tournament->host_id}}">{{$tournament->getHost()->name}}</a></td>
                                </tr>
                                <tr>
                                    <td>Participants:
                                        <?php
                                            $iteration = 1;
                                            $lastIteration = count($acceptedAttendees);
                                            foreach($acceptedAttendees as $attendee) {
                                                if(is_object($attendee->getSchool())) {
                                                    echo '<a href="' . '/school/' . $attendee->school_id . '">' . $attendee->getSchool()->name;
                                                        if($iteration !== $lastIteration) {
                                                            echo ',  ';
                                                        }
                                                        echo '</a>';
                                                        $iteration++;
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($hostUser)
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary col-md-2 offset-md-4" data-toggle="modal" data-target="#editTournamentModal">Edit Tournament</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal" data-target="#inviteTeamsModal">Invite Teams</button>
                    </div>
                    @endif

                    @if($userHasPendingTournamentInvite)
                    <div class="btn-group">
                        <button type="button" id="decline_tournament_invitation_button" class="btn btn-primary col-md-2 offset-md-4" data-toggle="modal">Decline Tournament Invitation</button>
                        <button type="button" id="accept_tournament_invitation_button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal">Accept Tournament Invitation</button>
                    </div>
                    @endif

                    <br>

                    <div class="btn-group .btn-girls">
                        <button id="girlsOneSingles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2 offset-md-2 selected-button">Girls 1 Singles</button>
                        <button id="girlsTwoSingles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2 offset-md-5" data-toggle="modal">Girls 2 Singles</button>
                        <button id="girlsOneDoubles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2 offset-md-5" data-toggle="modal">Girls 1 Doubles</button>
                        <button id="girlsTwoDoubles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2 offset-md-5" data-toggle="modal">Girls 2 Doubles</button>
                    </div>

                    <div class="btn-group">
                        <button id="boysOneSingles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-2" data-toggle="modal">Boys 1 Singles</button>
                        <button id="boysTwoSingles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-5" data-toggle="modal">Boys 2 Singles</button>
                        <button id="boysOneDoubles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-5" data-toggle="modal">Boys 1 Doubles</button>
                        <button id="boysTwoDoubles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-5" data-toggle="modal">Boys 2 Doubles</button>
                    </div>

                    <br>

                    <div class="btn-group .btn-girls">
                        <button id="showEditRosterTable" type="button" class="btn col-md-2 offset-md-4">Show/Edit Rosters</button>
                        <select id="rosterSelect" name="rosterSelect" type="button" class="btn col-md-2 offset-md-6 form-control">

                            @foreach($acceptedAttendees as $attendee)
                                <option value="{{$attendee->school_id}}">{{$attendee->getSchool()->name}}</option>
                            @endforeach

                            Show/Edit Rosters</select>

                    </div>

                    <br>



                    <table id="editRosterTable" class="display table table-striped">
                        <thead>
                        <tr class="fa fa-sort-name player_row" align="center">
                            <th scope="col">Seq.</th>
                            <th scope="col">id</th>
                            <th scope="col">Position</th>
                            <th scope="col">Name</th>
                            <th scope="col">Grade</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($girlsOneSinglesPlayers as $index => $player)
                            <tr>
                                <td class="table-cell">{{$player->position}}</td>
                                <td class="table-cell">{{$player->id}}</td>
                                <td class="position_name_td">teej</td>
                                <td class="table-cell">{{$player->first_name. ' ' . $player->last_name}}</td>
                                <td class="table-cell">{{$player->class}}</td>
                                <td align="center" class="table-cell"><i class="material-icons" style="color:green">mode_edit</i><i class="material-icons" style="color:red">delete</i></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>



                    <br>

                    <table id="seedsTable" class="display table table-striped">
                        <thead>
                        <tr class="fa fa-sort-name player_row" align="center">
                            <th scope="col">Seq.</th>
                            <th scope="col">id</th>
                            <th scope="col">Reorder</th>
                            <th scope="col">Seed</th>
                            <th scope="col">Name</th>
                            <th scope="col">Grade</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($girlsOneSinglesPlayers as $index => $player)
                            <tr>
                                <td class="table-cell">{{$player->position}}</td>
                                <td class="table-cell">{{$player->id}}</td>
                                <td class="table-cell reorder-cell"><img class="reorder-icon" src="{{URL::to('/')}}/images/reorder-icon.png"></td>
                                <td class="position_name_td">teej</td>
                                <td class="table-cell">{{$player->first_name. ' ' . $player->last_name}}</td>
                                <td class="table-cell">{{$player->class}}</td>
                                <td align="center" class="table-cell"><i class="material-icons" style="color:green">mode_edit</i><i class="material-icons" style="color:red">delete</i></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                    <div class="modal fade" id="editTournamentModal" tabindex="-1" role="dialog" aria-labelledby="editTournamentModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTournamentModal">Edit Tournament</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="tournament_name" class="col-md-4 col-form-label text-md-right">Tournament Name</label>

                                        <div class="col-md-6">
                                            <input id="tournament_name" type="text" class="form-control" name="tournament_name" value="{{$tournament->name ?? ''}}" required autofocus autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="location_name" class="col-md-4 col-form-label text-md-right">Location Name</label>

                                        <div class="col-md-6">
                                            <input id="location_name" type="text" class="form-control" name="location_name" value="{{$tournament->location_name ?? ''}}" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="address" class="col-md-4 col-form-label text-md-right">Location Address</label>

                                        <div class="col-md-6">
                                            <input id="address" type="text" class="form-control" name="address" value="{{$tournament->address ?? ''}}" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="date" class="col-md-4 col-form-label text-md-right">Date</label>
                                        <div class="col-md-6">
                                            <input id="date" type="date" class="form-control" name="date" value="{{$tournament->date ?? ''}}" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="time" class="col-md-4 col-form-label text-md-right">Start Time</label>
                                        <div class="col-md-6">
                                            <input id="time" type="time" class="form-control" name="time" value="{{$tournament->time ?? ''}}" required value="08:00" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="team_count" class="col-md-4 col-form-label text-md-right">Number of Teams</label>
                                        <div class="col-md-6">
                                            <select class="form-control" id="team_count" name="team_count">
                                                <?php for($x = 4; $x <= 16; $x++) {?>
                                                <option <?php if(isset($tournament)) {
                                                    if($x == $tournament->team_count){
                                                        echo "selected='selected'";
                                                    }
                                                }?>
                                                >
                                                    <?php echo $x;?>
                                                </option>
                                                <?php
                                                }?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 text-md-right">Gender</div>

                                        <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                            <label for="boys" class="btn btn-secondary active">
                                                <input class="form-control" type="radio" name="gender" id="boys" autocomplete="off" value="boys" checked> Boys
                                            </label>
                                            <label for="girls" class="btn btn-secondary">
                                                <input class="form-control" type="radio" name="gender" id="girls" autocomplete="off" value="girls"> Girls
                                            </label>
                                            <label for="both" class="btn btn-secondary">
                                                <input class="form-control" type="radio" name="gender" id="both" autocomplete="off" value="both"> Both
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="level" class="col-md-4 col-form-label text-md-right">Level</label>

                                        <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="level" id="level" autocomplete="off" value="varsity" checked> Varsity
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="level" id="level" autocomplete="off" value="jv"> JV
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="level" id="level" autocomplete="off" value="junior high"> Junior High
                                            </label>
                                        </div>
                                    </div>

                                    <div class="alert alert-success col-md-12" role="alert">
                                        <p>Public - Any team can join. First come, first served.</p>
                                        <hr>
                                        <p class="mb-0">Private - Only teams that you invite can join.  This option can be switched to Public at any time to fill teams if needed.</p>
                                    </div>

                                    <div class="form-group row">
                                        <label for="privacy_setting" class="col-md-4 col-form-label text-md-right">Public or Private</label>

                                        <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="privacy_setting" id="privacy_setting" autocomplete="off" value="public"
                                                    checked> Public
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="privacy_setting" id="privacy_setting" autocomplete="off" value="private"> Private
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary col-md-2" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary col-md-2">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="modal fade" id="inviteTeamsModal" tabindex="-1" role="dialog" aria-labelledby="inviteTeamsModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="inviteTeamsModal">Invite Teams</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-success col-md-12" role="alert">
                                        <p>Select the schools you wish to invite from the dropdown below.
                                            <br>
                                            Make sure to save any changes.</p>
                                    </div>
                                    <form>
                                        <div class="form-group">
                                            <label for="schools_to_invite" class="col-md-3 col-form-label text-md-right"></label>
                                            <div class="btn-group" class="col-md-12">
                                                <select style="width:350px" class="select2 form-control" id="schools_to_invite" name="schools_to_invite">
                                                    <option value="" disabled selected>Select School</option>
                                                    @foreach($schools as $key => $school)
                                                        <option value="{{ $school->id }}" data-conference="{{$school->conference}}">
                                                            {{ $school->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
{{--                                        <div class="form-group">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                <label for="list_to_invite" class="col-md-4 col-form-label text-md-right">Invite List</label>--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <ol id="list_to_invite"></ol>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <table id="invitesTable" class="display table table-striped">
                                            <thead>
                                            <tr class="fa fa-sort-name player_row" align="center">
                                                <th scope="col">Seq.</th>
                                                <th scope="col">id</th>
                                                <th scope="col">School Name</th>
                                                <th scope="col">Invite Status</th>
                                                <th scope="col">Class</th>
                                                <th scope="col">Remove</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($acceptedAttendees as $attendee)
                                                <tr class="accepted-invite">
                                                    <td class="table-cell">{{$attendee->updated_at}}</td>
                                                    <td class="table-cell">{{$attendee->school_id}}</td>
                                                    <td class="position_name_td">{{$attendee->school_name}}</td>
                                                    <td class="table-cell">{{ucfirst($attendee->invite_status)}}</td>
                                                    <td class="table-cell">{{$attendee->conference}}</td>
                                                    <td class="table-cell center-align"><span data-id="remove_school_button" aria-hidden="true">&#10060;</span></td>
                                                </tr>
                                            @endforeach
                                            @foreach($pendingAttendees as $attendee)
                                                <tr class="pending-invite">
                                                    <td class="table-cell">{{$attendee->updated_at}}</td>
                                                    <td class="table-cell">{{$attendee->school_id}}</td>
                                                    <td class="position_name_td">{{$attendee->school_name}}</td>
                                                    <td class="table-cell">{{ucfirst($attendee->invite_status)}}</td>
                                                    <td class="table-cell">{{$attendee->conference}}</td>
                                                    <td class="table-cell center-align"><span data-id="remove_school_button" aria-hidden="true">&#10060;</span></td>
                                                </tr>
                                            @endforeach
                                            @foreach($declinedAttendees as $attendee)
                                                <tr class="declined-invite">
                                                    <td class="table-cell">{{$attendee->updated_at}}</td>
                                                    <td class="table-cell">{{$attendee->school_id}}</td>
                                                    <td class="position_name_td">{{$attendee->school_name}}</td>
                                                    <td class="table-cell">{{ucfirst($attendee->invite_status)}}</td>
                                                    <td class="table-cell">{{$attendee->conference}}</td>
                                                    <td class="table-cell center-align"><span data-id="remove_school_button" aria-hidden="true">&#10060;</span></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary col-md-2" data-dismiss="modal">Close</button>
                                    <button id="invite_schools_button" type="button" class="btn btn-primary col-md-2">Send / Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <style>
            #bracket {
                margin-left: auto;
                margin-right: auto;
                font-size: 15px;
                width: 100%;
                table-layout:fixed;
                overflow:scroll;
            }

            #bracket th,
            #bracket td {
                text-align: center;
                padding:0;
                width:200px;
            }

            #bracket tr {
                height:35px;
            }

            .give-top-border {
                border-top: 2px solid black;
            }

            .give-bottom-border {
                border-bottom: 2px solid black;
            }

            .give-right-border {
                border-right: 2px solid black;
            }

            .give-left-border {
                border-left: 2px solid black;
            }
        </style>
        <table id="bracket">
            <tr>
                <th>Consolation Champion</th>
                <th>Consolation Final</th>
                <th>Consolation Semis</th>
                <th>First Round</th>
                <th>Winner's Semis</th>
                <th>Winner's Final</th>
                <th>Champion</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="advanceable" id="1-seed">1 Seed</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td id="1-seed-school" class="give-left-border give-top-border give-right-border advanceable">1 seed school</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td id="first-consolation-round-one-top">First Consolation Round One Top</td>
                <td class="give-left-border give-right-border">
                    <select id="first-first-round-court-select"></select>
                </td>
                <td id="first-winners-round-one-top">First Winners Round One Top</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="first-consolation-round-one-top-score-input"></td>
                <td id="8-seed" class="advanceable give-left-border give-right-border">8 seed</td>
                <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="first-winners-round-one-top-score-input"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td id="8-seed-school" class="give-top-border advanceable">8 seed school</td>
                <td class="give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td id="first-consolation-round-two-top">First Consolation Round Two Top</td>
                <td class="give-left-border"><select id="first-consolation-round-one-court-select"></select></td>
                <td></td>
                <td class="give-right-border"><select id="first-winners-round-one-court-select"></select></td>
                <td id="first-winners-round-two-top">First Winners Round Two Top</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="first-consolation-round-two-top-score-input"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="first-winners-round-two-top-score-input"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="advanceable" id="5-seed">5 seed</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td id="first-consolation-round-one-bottom" class="give-left-border">first consolation round one bottom</td>
                <td id="5-seed-school" class="give-left-border give-right-border give-top-border advanceable">5 seed school</td>
                <td id="first-winners-round-one-bottom" class="give-right-border">first winners round one bottom</td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="first-consolation-round-one-bottom-score-input"></td>
                <td class="give-left-border give-right-border">
                    <select id="second-first-round-court-select"></select>
                </td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="first-winners-round-one-bottom-score-input"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="4-seed" class="advanceable give-left-border give-right-border">4 seed</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="4-seed-school" class="give-top-border advanceable">4 seed school</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td id="consolation-champion">Consolation Champion</td>
                <td class="give-left-border"><select id="first-consolation-round-two-court-select"></select></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border">
                    <select id="first-winners-round-two-court-select"></select>
                </td>
                <td id="champion">Champion</td>
            </tr>
            <tr>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="consolation-champion-score-input"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="champion-score-input"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="advanceable" id="3-seed">3 seed</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="3-seed-school" class="give-left-border give-top-border give-right-border advanceable">3 seed school</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td id="second-consolation-round-one-top">second consolation round one top</td>
                <td class="give-left-border give-right-border">
                    <select id="third-first-round-court-select"></select>
                </td>
                <td id="second-winners-round-one-top">second winners round one top</td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="second-consolation-round-one-top-score-input"></td>
                <td id="6-seed" class="advanceable give-left-border give-right-border">6 seed</td>
                <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="second-winners-round-one-top-score-input"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td id="6-seed-school" class="give-top-border advanceable">6 seed school</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td id="first-consolation-round-two-bottom" class="give-left-border">First Consolation Round Two Bottom</td>
                <td class="give-left-border"><select id="second-consolation-round-one-court-select"></select></td>
                <td></td>
                <td class="give-right-border">
                    <select id="second-winners-round-one-court-select"></select>
                </td>
                <td id="first-winners-round-two-bottom" class="give-right-border">First Winners Round Two Bottom</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="first-consolation-round-two-bottom-score-input"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="first-winners-round-two-bottom-score-input"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td class="advanceable" id="7-seed">7 seed</td>
                <td class="give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td id="second-consolation-round-one-bottom" class="give-left-border">second consolation round one bottom</td>
                <td id="7-seed-school" class="give-top-border give-right-border give-left-border advanceable">7 seed school</td>
                <td id="second-winners-round-one-bottom" class="give-right-border">second winners round one bottom</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="second-consolation-round-one-bottom-score-input"></td>
                <td class="give-left-border give-right-border">
                    <select id="fourth-first-round-court-select"></select>
                </td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="second-winners-round-one-bottom-score-input"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td id="2-seed" class="advanceable give-left-border give-right-border">2 seed</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td id="2-seed-school" class="give-top-border advanceable">2 seed school</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <table id="bracket">
                <tr>
                    <th></th>
                    <th>Seventh Place</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Third Place</th>
                    <th></th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td id="first-consolation-lower-bracket-round-one-top">First Consolation Lower Bracket Round One Top</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="first-winners-lower-bracket-round-one-top">First Winners Lower Bracket Round One Top</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-top-border give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-top-border give-right-border"></td>
                    <td></td>
                </tr>
                <tr>
                    <td id="seventh-place">Seventh Place</td>
                    <td class="give-left-border">
                        <select id="seventh-place-court-select"></select>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-right-border">
                        <select id="third-place-court-select"></select>
                    </td>
                    <td id="third-place">Third Place</td>
                </tr>
                <tr>
                    <td class="give-top-border"><input hidden class="score-input" type="text" id="seventh-place-score-input"></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-right-border"></td>
                    <td class="give-top-border"><input hidden class="score-input" type="text" id="third-place-score-input"></td>
                </tr>
                <tr>
                    <td></td>
                    <td id="first-consolation-lower-bracket-round-one-bottom" class="give-left-border">First Consolation Lower Bracket Round One Bottom</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="first-winners-lower-bracket-round-one-bottom" class="give-right-border">First Winners Lower Bracket Round One Bottom</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-top-border"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-top-border"></td>
                    <td></td>
                </tr>
            </table>
        </table>
    </div>

@endsection
