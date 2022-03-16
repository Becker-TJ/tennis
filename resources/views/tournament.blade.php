@extends('layouts.app')

@section('content')

    <div class="container-fluid">


        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div id="tournamentID" style="display:none">{{$tournament->id}}</div>
                    <div style="background-color:#0a011f;color:white" class="card-header">{{$tournament->name}}</div>
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
                                            foreach($attendees as $attendee) {
                                                if(is_object($attendee->getSchool())) {
                                                    echo '<a href="' . '/school/' . $attendee->school_id . '">' . $attendee->getSchool()->name . ', </a>';
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary col-md-2 offset-md-4" data-toggle="modal" data-target="#editTournamentModal">Edit Tournament</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal" data-target="#inviteTeamsModal">Invite Teams</button>
                    </div>

                    <br>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary col-md-2 offset-md-2" data-toggle="modal">Boys 1 Singles</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal">Boys 2 Singles</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal">Boys 1 Doubles</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal">Boys 2 Doubles</button>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary col-md-2 offset-md-2" data-toggle="modal">Girls 1 Singles</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal">Girls 2 Singles</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal">Girls 1 Doubles</button>
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal">Girls 2 Doubles</button>
                    </div>

                    <br>

                    <table id="schoolTable" class="display table table-striped">
                        <thead>
                        <tr class="fa fa-sort-name player_row" align="center">
                            <th scope="col">Seq.</th>
                            <th scope="col">id</th>
                            <th scope="col">Position</th>
                            <th scope="col">Name</th>
                            <th scope="col">Class</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($oneSinglesPlayers as $index => $player)
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
                                    <form>
                                        <div class="form-group">
                                            <label for="schools_to_invite" class="col-md-3 col-form-label text-md-right">Schools</label>
                                            <div class="btn-group" class="col-md-12">
                                                <select style="width:350px" class="select2 form-control" id="schools_to_invite" name="schools_to_invite">
                                                    <option value="" disabled selected>Select School</option>
                                                    @foreach($schools as $school)
                                                        <option value="{{ $school->id }}">
                                                            {{ $school->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group row">
                                                <label for="list_to_invite" class="col-md-4 col-form-label text-md-right">Invite List</label>
                                                <div class="col-md-6">
                                                    <ol id="list_to_invite"></ol>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary col-md-2" data-dismiss="modal">Close</button>
                                    <button id="invite_schools_button" type="button" class="btn btn-primary col-md-2">Send Invites</button>
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
                <td>Justine Henin</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-left-border give-top-border give-right-border">Durant</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-left-border give-right-border"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-top-border give-left-border"></td>
                <td class="give-left-border give-right-border">Olivia Sparks</td>
                <td class="give-top-border give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-top-border">Del City</td>
                <td class="give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-top-border give-left-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td>Winnie Du</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="give-left-border give-right-border give-top-border">PCN</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-top-border"></td>
                <td class="give-left-border give-right-border"></td>
                <td class="give-top-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-left-border give-right-border">Lua Huynh</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-top-border">Westmoore</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td class="give-top-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border">Lily Truchet</td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td>Lily Truchet</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-left-border give-top-border give-right-border">Moore</td>
                <td></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-left-border give-right-border"></td>
                <td>Lily Truchet</td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-top-border give-left-border"></td>
                <td class="give-left-border give-right-border">Gracie Graham</td>
                <td class="give-top-border give-right-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td class="give-top-border">Southmoore</td>
                <td class="give-right-border"></td>
                <td class="give-right-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-right-border">Lily Truchet</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="give-top-border"></td>
                <td class="give-left-border"></td>
                <td></td>
                <td class="give-right-border"></td>
                <td class="give-top-border"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td>Ivette Sarabia</td>
                <td class="give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-left-border"></td>
                <td class="give-top-border give-right-border give-left-border">Lawton High</td>
                <td class="give-right-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="give-top-border"></td>
                <td class="give-left-border give-right-border"></td>
                <td class="give-top-border"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-left-border give-right-border">Makensie Butler</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="give-top-border">Choctaw</td>
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
        </table>
    </div>

@endsection
