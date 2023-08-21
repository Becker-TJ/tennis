
@extends('layouts.app')
@section('title', $tournament->name)
@section('content')
    <br>

    <div class="container-fluid content-fit">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div id="tournament_id" style="display:none">{{$tournament->id}}</div>
                    <div id="selectingDoublesPartner" data-player-name="empty" data-player-id="0" data-value="false" style="display:none"></div>
                    <div id="attendeeStatus" data-status="
                    @if($hostUser){{"host"}}
                    @elseif($userInviteStatus === "accepted")
                    {{"attendee"}}
                    @else
                    {{"none"}}
                    @endif
                    " data-school="
                    @if($userInviteStatus === "accepted")
                        {{$user->school_id}}
                    @else
                        {{"0"}}
                    @endif
                    " data-school-name="
                    @if($userInviteStatus === "accepted"){{$user->getSchool()->name}}@else{{"none"}}@endif" style="display:none"></div>
                    <div class="card-header">{{$tournament->name}}</div>
                    {{--                    <button class="btn btn-primary col-md-2 offset-md-5" onclick="location.href='/createtournament/{{$tournament->id}}'" type="button">Edit Tournament</button>--}}

                    <div class="row">
                        <div class="col-lg-6">
                            <table id="tournament-headings" class="table" style="width:100%">
                                <tbody>
                                <tr>
                                    <td><span class="format-label tournament-sub-title">Date: </span>{{date('m-d-Y', strtotime($tournament->date))}} ({{date('g:ia', strtotime($tournament->time))}} Start)</td>

                                </tr>
                                <tr>
                                    <td><span class="format-label tournament-sub-title">Location: </span>{{$tournament->location_name}}</td>

                                </tr>
                                <tr>
                                    <td><span class="format-label tournament-sub-title">Address: </span>{{$tournament->address}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table id="tournament-headings-second-column" class="table" style="width:100%">
                                <tbody>
                                <tr>
                                    <td><span class="format-label tournament-sub-title">Host: </span><a href="/school/{{$tournament->host_id}}">{{$tournament->getHost()->name}}</a></td>
                                </tr>
                                <tr>
                                    <td><span class="format-label tournament-sub-title">Participants: </span>
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
                            <button type="button" class="btn button-in-row btn-primary col-md-2 offset-md-4" data-toggle="modal" data-target="#editTournamentModal">Edit Tournament</button>
                            <button type="button" class="btn last-button-in-row button-in-row btn-primary col-md-2 offset-md-5" data-toggle="modal" data-target="#inviteTeamsModal">Invite Teams</button>
                        </div>
                    @endif

                    @if($userInviteStatus === 'pending' || ($tournament->privacy_setting === "Public" && Auth::check()))
                        @if($userInviteStatus != "declined" && !$hostUser)
                            <br>
                        @endif
                        <div class="btn-group">
                            @if($userInviteStatus === 'pending' || $userInviteStatus === 'accepted' && !$hostUser)
                                <button type="button" id="decline_tournament_invitation_button" class="btn button-in-row btn-primary col-md-2 {{$userInviteStatus === 'pending' ? 'offset-md-4' : 'offset-md-5'}}" data-toggle="modal">
                                    {{$userInviteStatus === 'pending' ? 'Decline Invitation' : 'Leave Tournament'}}
                                </button>
                            @endif
                            @if(($userInviteStatus === "No Invite" && $tournament->privacy_setting === "Public") || $userInviteStatus === "pending")
                                <button type="button" id="accept_tournament_invitation_button" class="btn last-button-in-row button-in-row btn-primary col-md-2 offset-md-5" data-toggle="modal">
                                    {{$tournament->privacy_setting === "Public" && $userInviteStatus === "No Invite" ? 'Join Tournament' : 'Accept Tournament Invitation'}}
                                </button>
                            @endif
                        </div>
                    @endif

                    <br>

                    @if($tournament->gender === "Boys" || $tournament->gender === "Both")
                        <div class="boys-buttons btn-group">
                            <button id="boysOneSingles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-2 {{$tournament->gender === "Boys" || $tournament->gender === "Both" ? 'selected-button' : ''}}" data-toggle="modal">Boys 1 Singles</button>
                            <button id="boysTwoSingles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-5" data-toggle="modal">Boys 2 Singles</button>
                            <button id="boysOneDoubles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-5" data-toggle="modal">Boys 1 Doubles</button>
                            <button id="boysTwoDoubles" data-gender="Male" type="button" class="bracket-button btn btn-boys col-md-2 offset-md-5" data-toggle="modal">Boys 2 Doubles</button>
                        </div>
                    @endif

                    @if($tournament->gender === "Girls" || $tournament->gender === "Both")
                        <div class="girls-buttons btn-group .btn-girls">
                            <button id="girlsOneSingles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2
                            @if($tournament->gender === "Girls" || $tournament->gender === "Girls"){{"give-top-border"}}@endif offset-md-2 {{$tournament->gender === "Girls" ? 'selected-button' : ''}}">Girls 1 Singles</button>
                            <button id="girlsTwoSingles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2
                            @if($tournament->gender === "Girls" || $tournament->gender === "Girls"){{"give-top-border"}}@endif
                            offset-md-5" data-toggle="modal">Girls 2 Singles</button>
                            <button id="girlsOneDoubles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2
                            @if($tournament->gender === "Girls" || $tournament->gender === "Girls"){{"give-top-border"}}@endif offset-md-5" data-toggle="modal">Girls 1 Doubles</button>
                            <button id="girlsTwoDoubles" data-gender="Female" type="button" class="bracket-button btn btn-girls col-md-2
                            @if($tournament->gender === "Girls" || $tournament->gender === "Girls"){{"give-top-border"}}@endif offset-md-5" data-toggle="modal">Girls 2 Doubles</button>
                        </div>
                    @endif

                    <br>

                    <div class="btn-group .btn-girls">
                        <button id="showEditRosterTable" type="button" class="col-md-3 offset-md-3 button-in-row">Show Roster</button>
                        <select id="rosterSelect" name="rosterSelect" type="button" class="btn col-md-3 offset-md-7 form-control">

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
                            <th scope="col">Player</th>
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

                    <table id="seedsTable" class="display table table-striped">
                        <thead>
                        <tr class="fa fa-sort-name player_row" align="center">
                            <th scope="col">Seq.</th>
                            <th scope="col">id</th>
                            <th scope="col">Reorder</th>
                            <th scope="col">Seed</th>
                            <th class="left-align" scope="col">School</th>
                            <th class="left-align" scope="col">Player</th>
                            <th scope="col">Class</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td class="table-cell"></td>
                            <td class="table-cell"></td>
                            <td class="table-cell reorder-cell"></td>
                            <td class="position_name_td"></td>
                            <td class="table-cell"></td>
                            <td class="table-cell"></td>
                            <td class="table-cell"></td>
                            <td align="center" class="table-cell"></td>
                        </tr>

                        </tbody>
                    </table>


                    <div class="modal fade" id="editTournamentModal" tabindex="-1" role="dialog" aria-labelledby="editTournamentModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <form method="POST" action="/edittournament">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editTournamentModal">Edit Tournament</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" value="{{$tournament->id}}" name="tournament_id">

                                        <div class="form-group row">
                                            <label for="tournament_name" class="format-label col-md-4 col-form-label text-md-right">Tournament Name</label>

                                            <div class="col-md-6">
                                                <input id="tournament_name" type="text" class="form-control" name="tournament_name" value="{{$tournament->name ?? ''}}" required autofocus autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="location_name" class="format-label col-md-4 col-form-label text-md-right">Location Name</label>

                                            <div class="col-md-6">
                                                <input id="location_name" type="text" class="form-control" name="location_name" value="{{$tournament->location_name ?? ''}}" required autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="address" class="format-label col-md-4 col-form-label text-md-right">Location Address</label>

                                            <div class="col-md-6">
                                                <input id="address" type="text" class="form-control" name="address" value="{{$tournament->address ?? ''}}" required autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="date" class="format-label col-md-4 col-form-label text-md-right">Date</label>
                                            <div class="col-md-6">
                                                <input id="date" type="date" class="form-control" name="date" value="{{$tournament->date ?? ''}}" required autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="time" class="format-label col-md-4 col-form-label text-md-right">Start Time</label>
                                            <div class="col-md-6">
                                                <input id="time" type="time" class="form-control" name="time" value="{{$tournament->time ?? ''}}" required value="08:00" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="team_count" class="format-label col-md-4 col-form-label text-md-right">Number of Teams</label>
                                            <div class="col-md-6">
                                                <select class="form-control" id="team_count" name="team_count">
                                                    <?php for($x = 8; $x == 8; $x++) {?>
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
                                            <label for="courts" class="format-label col-md-4 col-form-label text-md-right">Number Of Courts At Venue</label>
                                            <div class="col-md-6">
                                                <input class="form-control" type="number" id="quantity" value="{{$tournament->courts ?? ''}}" name="courts" min="1" max="50">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="gender" class="format-label col-md-4 col-form-label text-md-right">Gender</label>

                                            <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                                <label for="boys" class="first-button-in-row button-in-row btn btn-secondary {{$tournament->gender === "Boys" ? "active" : ""}}">
                                                    <input class="form-control" type="radio" name="gender" id="boys" autocomplete="off" value="boys" {{$tournament->gender === "Boys" ? "checked" : ""}}> Boys
                                                </label>
                                                <label for="girls" class="button-in-row btn btn-secondary {{$tournament->gender === "Girls" ? "active" : ""}}">
                                                    <input class="form-control" type="radio" name="gender" id="girls" autocomplete="off" value="girls" {{$tournament->gender === "Girls" ? "checked" : ""}}> Girls
                                                </label>
                                                <label for="both" class="button-in-row last-button-in-row btn btn-secondary {{$tournament->gender === "Both" ? "active" : ""}}">
                                                    <input class="form-control" type="radio" name="gender" id="both" autocomplete="off" value="both" {{$tournament->gender === "Both" ? "checked" : ""}}> Both
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="level" class="format-label col-md-4 col-form-label text-md-right">Level</label>

                                            <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                                <label class="button-in-row first-button-in-row btn btn-secondary {{$tournament->level === "Varsity" ? "active" : ""}}">
                                                    <input type="radio" name="level" id="level" autocomplete="off" value="varsity" {{$tournament->level === "Varsity" ? "checked" : ""}}> Varsity
                                                </label>
                                                <label class="button-in-row btn btn-secondary {{$tournament->level === "Junior Varsity" ? "active" : ""}}">
                                                    <input type="radio" name="level" id="level" autocomplete="off" value="junior varsity" {{$tournament->level === "Junior Varsity" ? "checked" : ""}}> JV
                                                </label>
                                                <label class="last-button-in-row button-in-row btn btn-secondary {{$tournament->level === "Junior High" ? "active" : ""}}">
                                                    <input type="radio" name="level" id="level" autocomplete="off" value="junior high" {{$tournament->level === "Junior High" ? "checked" : ""}}> Junior High
                                                </label>
                                            </div>
                                        </div>

                                        <div class="alert alert-success col-md-12" role="alert">
                                            <p>Public - Any team can join. First come, first served.</p>
                                            <hr>
                                            <p class="mb-0">Private - Only teams that you invite can join.  This option can be switched to Public at any time to fill teams if needed.</p>
                                        </div>

                                        <div class="form-group row">
                                            <label for="privacy_setting" class="format-label col-md-4 col-form-label text-md-right">Public or Private</label>

                                            <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                                <label class="button-in-row first-button-in-row btn btn-secondary {{$tournament->privacy_setting === "Public" ? "active" : ""}}">
                                                    <input type="radio" name="privacy_setting" id="privacy_setting" autocomplete="off" value="public" {{$tournament->privacy_setting === "Public" ? "checked" : ""}}> Public
                                                </label>
                                                <label class="button-in-row btn btn-secondary {{$tournament->privacy_setting === "Private" ? "active" : ""}}">
                                                    <input type="radio" name="privacy_setting" id="privacy_setting" autocomplete="off" value="private" {{$tournament->privacy_setting === "Private" ? "checked" : ""}}> Private
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="button-in-row cancel-button btn btn-secondary col-md-3" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="submit-button button-in-row btn btn-primary col-md-3">Save Changes</button>
                                    </div>
                                </div>
                            </form>
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
                                                    <td class="table-cell center-align"><span data-id="remove-school-button" aria-hidden="true">&#10060;</span></td>
                                                </tr>
                                            @endforeach
                                            @foreach($pendingAttendees as $attendee)
                                                <tr class="pending-invite">
                                                    <td class="table-cell">{{$attendee->updated_at}}</td>
                                                    <td class="table-cell">{{$attendee->school_id}}</td>
                                                    <td class="position_name_td">{{$attendee->school_name}}</td>
                                                    <td class="table-cell">{{ucfirst($attendee->invite_status)}}</td>
                                                    <td class="table-cell">{{$attendee->conference}}</td>
                                                    <td class="table-cell center-align"><span data-id="remove-school-button" aria-hidden="true">&#10060;</span></td>
                                                </tr>
                                            @endforeach
                                            @foreach($declinedAttendees as $attendee)
                                                <tr class="declined-invite">
                                                    <td class="table-cell">{{$attendee->updated_at}}</td>
                                                    <td class="table-cell">{{$attendee->school_id}}</td>
                                                    <td class="position_name_td">{{$attendee->school_name}}</td>
                                                    <td class="table-cell">{{ucfirst($attendee->invite_status)}}</td>
                                                    <td class="table-cell">{{$attendee->conference}}</td>
                                                    <td class="table-cell center-align"><span data-id="remove-school-button" aria-hidden="true">&#10060;</span></td>
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
                border-top: 4px solid #333 !important;
            }

            .give-bottom-border {
                border-bottom: 4px solid #333;
            }

            .give-right-border {
                border-right: 4px solid #333;
            }

            .give-left-border {
                border-left: 4px solid #333;
            }
        </style>
        <table id="bracket">
            <tr>
                <th>New</th>
                <th>New</th>
                <th>Consolation Champion</th>
                <th>Consolation Final</th>
                <th>Consolation Semis</th>
                <th>First Round</th>
                <th>Winner's Semis</th>
                <th>Winner's Final</th>
                <th>Champion</th>
                <th>SIXTEEN TEAMP CHAMPION</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
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
                <td></td>
                <td></td>
                <td id="first-consolation-round-one-top">First Consolation Round One Top</td>
                <td class="give-left-border give-right-border">
                    <select id="first-first-round-court-select">
                    </select>
                </td>
                <td id="first-winners-round-one-top">First Winners Round One Top</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="first-consolation-round-one-top-score-input"></td>
                <td id="16-seed" class="advanceable give-left-border give-right-border">16 seed</td>
                <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="first-winners-round-one-top-score-input"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td id="16-seed-school" class="give-top-border advanceable">16 seed school</td>
                <td class="give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
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
                <td></td>
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
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="advanceable" id="9-seed">9 seed</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td id="first-consolation-round-three-top" class="give-bottom-border">First consolation round three top</td>
                <td class="give-left-border"><select id="first-consolation-round-two-court-select"></select></td>
                <td id="first-consolation-round-one-bottom" class="give-left-border">first consolation round one bottom</td>
                <td id="9-seed-school" class="give-left-border give-right-border give-top-border advanceable">9 seed school</td>
                <td id="first-winners-round-one-bottom" class="give-right-border">first winners round one bottom</td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
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
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="8-seed" class="advanceable give-left-border give-right-border">8 seed</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="8-seed-school" class="give-top-border advanceable">8 seed school</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td id="first-consolation-round-two-bottom" class="give-left-border">First Consolation Round Two Bottom</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border">
                    <select id="first-winners-round-two-court-select"></select>
                </td>
                <td id="first-winners-round-three-top">First Winners Round Three Top</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"><input hidden class="score-input" type="text" id="consolation-champion-score-input"></td>
                <td class="give-top-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="champion-score-input"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td></td>
                <td class="advanceable" id="5-seed">5 seed</td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td id="first-consolation-round-four-top">First Consolation Round Four Top</td>
                <td class="give-left-border"><select id="first-consolation-round-three-court-select"></select></td>
                <td></td>
                <td></td>
                <td id="5-seed-school" class="give-left-border give-top-border give-right-border advanceable">5 seed school</td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-top-border give-left-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="second-consolation-round-one-top">second consolation round one top</td>
                <td class="give-left-border give-right-border">
                    <select id="third-first-round-court-select"></select>
                </td>
                <td id="second-winners-round-one-top">second winners round one top</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="second-consolation-round-one-top-score-input"></td>
                <td id="12-seed" class="advanceable give-left-border give-right-border">12 seed</td>
                <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="second-winners-round-one-top-score-input"></td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-left-border"></td>
                <td id="12-seed-school" class="give-top-border advanceable">12 seed school</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td id="second-consolation-round-two-top">Second Consolation Round Two Top</td>
                <td class="give-left-border"><select id="second-consolation-round-one-court-select"></select></td>
                <td></td>
                <td class="give-right-border">
                    <select id="second-winners-round-one-court-select"></select>
                </td>
                <td id="first-winners-round-two-bottom" class="give-right-border">First Winners Round Two Bottom</td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="first-consolation-round-two-bottom-score-input"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="first-winners-round-two-bottom-score-input"></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="advanceable" id="13-seed">13 seed</td>
                <td class="give-right-border"></td>
                <td></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td id="first-consolation-round-three-bottom" class="give-left-border give-bottom-border">First consolation round three bottom</td>
                <td class="give-left-border"><select id="second-consolation-round-two-court-select"></select></td>
                <td id="second-consolation-round-one-bottom" class="give-left-border">second consolation round one bottom</td>
                <td id="13-seed-school" class="give-top-border give-right-border give-left-border advanceable">13 seed school</td>
                <td id="second-winners-round-one-bottom" class="give-right-border">second winners round one bottom</td>
                <td></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="second-consolation-round-one-bottom-score-input"></td>
                <td class="give-left-border give-right-border">
                    <select id="fourth-first-round-court-select"></select>
                </td>
                <td class="give-top-border"><input hidden class="score-input" type="text" id="second-winners-round-one-bottom-score-input"></td>
                <td></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="4-seed" class="advanceable give-left-border give-right-border">4 seed</td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="4-seed-school" class="give-top-border advanceable">4 seed school</td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td id="second-consolation-round-two-bottom" class="give-left-border give-bottom-border">Second Consolation Round Two Bottom</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border"><select id="first-winners-round-three-court-select"></select></td>
                <td id="champion">champion</td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border"></td>
            </tr>
{{--            NEW BRACKET--}}
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="advanceable" id="3-seed">3 Seed</td>
                    <td></td>
                    <td></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="3-seed-school" class="give-left-border give-top-border give-right-border advanceable">3 seed school</td>
                    <td></td>
                    <td></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td id="fifth-place">Fifth Place</td>
                    <td class="give-left-border"><select id="first-consolation-round-four-court-select"></select></td>
                    <td></td>
                    <td></td>
                    <td id="third-consolation-round-one-top">Third Consolation Round One Top</td>
                    <td class="give-left-border give-right-border">
                        <select id="fifth-first-round-court-select">
                        </select>
                    </td>
                    <td id="third-winners-round-one-top">Third Winners Round One Top</td>
                    <td></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td class="give-top-border"></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="first-consolation-round-one-top-score-input"></td>
                    <td id="14-seed" class="advanceable give-left-border give-right-border">14 seed</td>
                    <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="first-winners-round-one-top-score-input"></td>
                    <td></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td id="14-seed-school" class="give-top-border advanceable">14 seed school</td>
                    <td class="give-right-border"></td>
                    <td></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td id="third-consolation-round-two-top">Third Consolation Round Two Top</td>
                    <td class="give-left-border"><select id="third-consolation-round-one-court-select"></select></td>
                    <td></td>
                    <td class="give-right-border"><select id="third-winners-round-one-court-select"></select></td>
                    <td id="second-winners-round-two-top">Second Winners Round Two Top</td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="first-consolation-round-two-top-score-input"></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td class="give-right-border"></td>
                    <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="first-winners-round-two-top-score-input"></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td class="advanceable" id="11-seed">11 seed</td>
                    <td class="give-right-border"></td>
                    <td class="give-right-border"></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td id="second-consolation-round-three-top" class="give-bottom-border">Second Consolation Round Three Top</td>
                    <td class="give-left-border"><select id="third-consolation-round-two-court-select"></select></td>
                    <td id="third-consolation-round-one-bottom" class="give-left-border">Third consolation round one bottom</td>
                    <td id="11-seed-school" class="give-left-border give-right-border give-top-border advanceable">11 seed school</td>
                    <td id="third-winners-round-one-bottom" class="give-right-border">Third winners round one bottom</td>
                    <td class="give-right-border"></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td class="give-top-border"><input hidden class="score-input" type="text" id="first-consolation-round-one-bottom-score-input"></td>
                    <td class="give-left-border give-right-border">
                        <select id="sixth-first-round-court-select"></select>
                    </td>
                    <td class="give-top-border"><input hidden class="score-input" type="text" id="first-winners-round-one-bottom-score-input"></td>
                    <td class="give-right-border"></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td id="6-seed" class="advanceable give-left-border give-right-border">6 seed</td>
                    <td></td>
                    <td class="give-right-border"></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td id="6-seed-school" class="give-top-border advanceable">6 seed school</td>
                    <td></td>
                    <td class="give-right-border"></td>
                    <td class="give-right-border"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border" id="consolation-champion"></td>
                    <td id="third-consolation-round-two-bottom" class="give-left-border give-bottom-border">Third Consolation Round Two Bottom</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-right-border">
                        <select id="second-winners-round-two-court-select"></select>
                    </td>
                    <td id="first-winners-round-three-bottom" class="give-right-border">First Winners Round Three Bottom</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"><input hidden class="score-input" type="text" id="consolation-champion-score-input"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-right-border"></td>
                    <td class="give-top-border"><input hidden class="score-input" type="text" id="champion-score-input"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td class="advanceable" id="7-seed">7 seed</td>
                    <td></td>
                    <td class="give-right-border"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td></td>
                    <td id="7-seed-school" class="give-left-border give-top-border give-right-border advanceable">7 seed school</td>
                    <td></td>
                    <td class="give-right-border"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td id="first-consolation-round-four-bottom" class="give-left-border give-bottom-border">First Consolation Round Four Bottom</td>
                    <td class="give-left-border"><select id="second-consolation-round-three-court-select"></select></td>
                    <td></td>
                    <td id="fourth-consolation-round-one-top">Fourth consolation round one top</td>
                    <td class="give-left-border give-right-border">
                        <select id="seventh-first-round-court-select"></select>
                    </td>
                    <td id="fourth-winners-round-one-top">Fourth winners round one top</td>
                    <td class="give-right-border"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="second-consolation-round-one-top-score-input"></td>
                    <td id="10-seed" class="advanceable give-left-border give-right-border">10 seed</td>
                    <td class="give-top-border give-right-border"><input hidden class="score-input" type="text" id="second-winners-round-one-top-score-input"></td>
                    <td class="give-right-border"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td id="10-seed-school" class="give-top-border advanceable">10 seed school</td>
                    <td class="give-right-border"></td>
                    <td class="give-right-border"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td id="fourth-consolation-round-two-top">Fourth Consolation Round Two Top</td>
                    <td class="give-left-border"><select id="fourth-consolation-round-one-court-select"></select></td>
                    <td></td>
                    <td class="give-right-border">
                        <select id="fourth-winners-round-one-court-select"></select>
                    </td>
                    <td id="second-winners-round-two-bottom" class="give-right-border">Second Winners Round Two Bottom</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-top-border give-left-border"><input hidden class="score-input" type="text" id="first-consolation-round-two-bottom-score-input"></td>
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
                    <td class="give-left-border"></td>
                    <td class="give-left-border"></td>
                    <td class="advanceable" id="15-seed">15 seed</td>
                    <td class="give-right-border"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td id="second-consolation-round-three-bottom" class="give-left-border give-bottom-border">Second Consolation Round Three Bottom</td>
                    <td class="give-left-border"><select id="fourth-consolation-round-two-court-select"></select></td>
                    <td id="fourth-consolation-round-one-bottom" class="give-left-border">Fourth consolation round one bottom</td>
                    <td id="15-seed-school" class="give-top-border give-right-border give-left-border advanceable">15 seed school</td>
                    <td id="fourth-winners-round-one-bottom" class="give-right-border">Fourth winners round one bottom</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-left-border"></td>
                    <td class="give-top-border"><input hidden class="score-input" type="text" id="second-consolation-round-one-bottom-score-input"></td>
                    <td class="give-left-border give-right-border">
                        <select id="eighth-first-round-court-select"></select>
                    </td>
                    <td class="give-top-border"><input hidden class="score-input" type="text" id="second-winners-round-one-bottom-score-input"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="give-left-border"></td>
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
                    <td class="give-left-border"></td>
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
                    <td id="fourth-consolation-round-two-bottom" class="give-left-border give-bottom-border">Fourth Consolation Round Two Bottom</td>
                    <td></td>
                    <td></td>
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
                    <td></td>
                    <td></td>
                </tr>

            </table>
    </div>
            <table id="bracket">
                <tr>
                    <th></th>
                    <th></th>
                    <th>Playoff for 13th</th>
                    <th></th>
                    <th>Playoff for 9th</th>
                    <th></th>
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


@endsection
@section('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/tournament.js') }}"></script>
@endsection