@extends('layouts.app')
@section('title', $school->name)

@section('content')
    <br>
    <div id="playerForStatsModal" data-player-table="schoolTable" data-player-id="0" style="display:none"></div>
    <div id="coach" data-coach="{{$isCoach ? 'true' : 'false'}}" style="display:none"></div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Roster for {{$school->name}}</div>
                    <div class="card-body">
                        @if ($isCoach)
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal" data-target="#addNewPlayerModal">Add New Player</button>
                        @endif
                        <hr>
                        <div id="button-crew">
                            <button type="button" id="boys-roster" class="selected-roster-button btn btn-primary col-md-2 offset-md-4">Boys</button>
                            <button type="button" id="girls-roster" class="btn btn-primary col-md-2">Girls</button>
                        </div>
                        <hr>


                        <table id="schoolTable" class="display table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name player_row" align="center">
                                <th scope="col">Seq.</th>
                                <th scope="col">id</th>
                                <th scope="col">Reorder</th>
                                <th scope="col">Position</th>
                                <th scope="col">Name</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($boys as $index => $player)
                                <tr>
                                    <td class="table-cell">{{$player->position}}</td>
                                    <td class="table-cell">{{$player->id}}</td>
                                    <td class="table-cell reorder-cell"><img class="reorder-icon" src="{{URL::to('/')}}/images/reorder-icon.png"></td>
                                    <td class="position_name_td position-title-td-highlight">{{$positionNamesOrder[$increment++]}}</td>
                                    <td class="table-cell"><a class="player playerModalToggle">{{$player->first_name. ' ' . $player->last_name}}</a></td>
                                    <td class="table-cell">{{$player->grade}}</td>
                                    <td align="center" class="table-cell">
                                        <i class="material-icons edit-pen" data-toggle="modal" data-target="#editPlayerModal" style="color:green">mode_edit</i>
                                        <i class="material-icons delete-trash-can" style="color:red">delete</i>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <table id="girlsSchoolTable" class="display table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name player_row" align="center">
                                <th scope="col">Seq.</th>
                                <th scope="col">id</th>
                                <th scope="col">Reorder</th>
                                <th scope="col">Position</th>
                                <th scope="col">Name</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($girls as $index => $player)
                                <tr>
                                    <td class="table-cell">{{$player->position}}</td>
                                    <td class="table-cell">{{$player->id}}</td>
                                    <td class="table-cell reorder-cell"><img class="reorder-icon" src="{{URL::to('/')}}/images/reorder-icon.png"></td>
                                    <td class="position_name_td position-title-td-highlight">{{$positionNamesOrder[$increment++]}}</td>
                                    <td class="table-cell"><a class="player playerModalToggle">{{$player->first_name. ' ' . $player->last_name}}</a></td>
                                    <td class="table-cell">{{$player->grade}}</td>
                                    <td align="center" class="table-cell">
                                        <i class="material-icons edit-pen" data-toggle="modal" data-target="#editPlayerModal" style="color:green">mode_edit</i>
                                        <i class="material-icons delete-trash-can" style="color:red">delete</i>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addNewPlayerModal" tabindex="-1" role="dialog" aria-labelledby="addNewPlayerModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewPlayerModal">Add New Player</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="addnewplayer">
                        @csrf
                        <div hidden class="form-group">
                            <label for="school_id" class="col-form-label">School ID</label>
                            <input type="text" name="school_id" class="form-control" id="school_id" value="{{$school->id}}">
                        </div>
                        <div class="form-group">
                            <label for="new_player_first_name" class="col-form-label">First Name</label>
                            <input type="text" name="new_player_first_name" class="form-control" id="new_player_first_name">
                        </div>
                        <div class="form-group">
                            <label for="new_player_last_name" class="col-form-label">Last Name</label>
                            <input type="text" name="new_player_last_name" class="form-control" id="new_player_last_name">
                        </div>
                        <div class="form-group">
                            <label style="padding-left:0" for="class" class="col-form-label col-md-12">Grade</label>
                            <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="grade" id="grade" autocomplete="off" value="Freshman" checked> Freshman
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="grade" id="grade" autocomplete="off" value="Sophomore"> Sophomore
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="grade" id="grade" autocomplete="off" value="Junior"> Junior
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="grade" id="grade" autocomplete="off" value="Senior"> Senior
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="padding-left:0" for="gender" class="col-form-label col-md-12">Gender</label>
                            <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                <label class="col-md-3 offset-md-3 btn btn-secondary active">
                                    <input type="radio" name="gender" id="gender" autocomplete="off" value="Male" checked>Boy
                                </label>
                                <label class="col-md-3 btn btn-secondary">
                                    <input type="radio" name="gender" id="gender" autocomplete="off" value="Female">Girl
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary col-md-2" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary col-md-2" id="addNewPlayerButton">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editPlayerModal" tabindex="-1" role="dialog" aria-labelledby="editPlayerModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlayerModal">Edit Player</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/editplayer">
                        @csrf
                        <div hidden class="form-group">
                            <label for="school_id" class="col-form-label">School ID</label>
                            <input type="text" name="school_id" class="form-control" id="school_id" value="{{$school->id}}">
                        </div>
                        <div hidden class="form-group">
                            <label for="edit_player_id" class="col-form-label">School ID</label>
                            <input type="text" name="edit_player_id" class="form-control" id="edit_player_id" value="0">
                        </div>
                        <div class="form-group">
                            <label for="edit_player_first_name" class="col-form-label">First Name</label>
                            <input type="text" name="edit_player_first_name" class="form-control" id="edit_player_first_name">
                        </div>
                        <div class="form-group">
                            <label for="edit_player_last_name" class="col-form-label">Last Name</label>
                            <input type="text" name="edit_player_last_name" class="form-control" id="edit_player_last_name">
                        </div>
                        <div class="form-group">
                            <label style="padding-left:0" for="edit_grade" class="col-form-label col-md-12">Grade</label>
                            <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                <label class="btn btn-secondary" id="edit_grade_freshman">
                                    <input type="radio" name="edit_grade" id="edit_grade_freshman_input" autocomplete="off" value="Freshman"> Freshman
                                </label>
                                <label class="btn btn-secondary" id="edit_grade_sophomore">
                                    <input type="radio" name="edit_grade" id="edit_grade_sophomore_input" autocomplete="off" value="Sophomore"> Sophomore
                                </label>
                                <label class="btn btn-secondary" id="edit_grade_junior">
                                    <input type="radio" name="edit_grade" id="edit_grade_junior_input" autocomplete="off" value="Junior"> Junior
                                </label>
                                <label class="btn btn-secondary" id="edit_grade_senior">
                                    <input type="radio" name="edit_grade" id="edit_grade_senior_input" autocomplete="off" value="Senior"> Senior
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="padding-left:0" for="edit-gender" class="col-form-label col-md-12">Gender</label>
                            <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                <label id="edit_gender_male" class="col-md-3 offset-md-3 btn btn-secondary">
                                    <input type="radio" name="edit_gender" id="edit_gender_male_input" autocomplete="off" value="Male" checked>Boy
                                </label>
                                <label id="edit_gender_female" class="col-md-3 btn btn-secondary">
                                    <input type="radio" name="edit_gender" id="edit_gender_female_input" autocomplete="off" value="Female">Girl
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary col-md-2" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary col-md-2" id="addNewPlayerButton">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="playerStatsModal" tabindex="-1" role="dialog" aria-labelledby="playerStatsModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="playerStatsModalTitle">Invite Teams</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <table id="playerStatsTable" class="display table table-striped">
                        <thead>
                        <tr class="fa fa-sort-name player_row" align="center">
                            <th scope="col">Seq.</th>
                            <th scope="col">id</th>
                            <th scope="col">Bracket</th>
                            <th scope="col">Name</th>
                            <th scope="col">Opponent</th>
                            <th scope="col">Opponent School</th>
                            <th scope="col">Score</th>
                            <th scope="col">Win/Loss</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary col-md-2" data-dismiss="modal">Close</button>
                    <button id="invite_schools_button" type="button" class="btn btn-primary col-md-2">Send / Save</button>
                </div>
            </div>
        </div>
    </div>










@endsection
@section('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/school.js') }}"></script>
@endsection
