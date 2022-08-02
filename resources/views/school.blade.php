@extends('layouts.app')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Roster for {{$school->name}}</div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal" data-target="#addNewPlayerModal">Add New Player</button>
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
                                    <td class="table-cell">{{$player->first_name. ' ' . $player->last_name}}</td>
                                    <td class="table-cell">{{$player->grade}}</td>
                                    <td align="center" class="table-cell">
                                        <i class="material-icons edit-pen" data-toggle="modal" data-target="#editPlayerModal" style="color:green">mode_edit</i>
                                        <i class="material-icons delete-trash-can" style="color:red">delete</i>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <table hidden id="girlsSchoolTable" class="display table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name player_row" align="center">
                                <th scope="col">Seq.</th>
                                <th scope="col">id</th>
                                <th scope="col">Reorder</th>
                                <th scope="col">Position</th>
                                <th scope="col">Name</th>
                                <th scope="col">Class</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($girls as $index => $player)
                                <tr>
                                    <td class="table-cell">{{$player->position}}</td>
                                    <td class="table-cell">{{$player->id}}</td>
                                    <td class="table-cell reorder-cell"><img class="reorder-icon" src="{{URL::to('/')}}/images/reorder-icon.png"></td>
                                    <td class="position_name_td">{{$positionNamesOrder[$increment++]}}</td>
                                    <td class="table-cell">{{$player->first_name. ' ' . $player->last_name}}</td>
                                    <td class="table-cell">{{$player->class}}</td>
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
                    <form method="POST" action="editplayer">
                        @csrf
                        <div hidden class="form-group">
                            <label for="school_id" class="col-form-label">School ID</label>
                            <input type="text" name="school_id" class="form-control" id="school_id" value="{{$school->id}}">
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
                            <label style="padding-left:0" for="class" class="col-form-label col-md-12">Grade</label>
                            <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                <label class="btn btn-secondary" id="class-freshman">
                                    <input type="radio" name="edit-class" autocomplete="off" value="Freshman"> Freshman
                                </label>
                                <label class="btn btn-secondary" id="grade-sophomore">
                                    <input type="radio" name="edit-grade" autocomplete="off" value="Sophomore"> Sophomore
                                </label>
                                <label class="btn btn-secondary" id="grade-junior">
                                    <input type="radio" name="edit-grade" autocomplete="off" value="Junior"> Junior
                                </label>
                                <label class="btn btn-secondary" id="grade-senior">
                                    <input type="radio" name="edit-grade" autocomplete="off" value="Senior"> Senior
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="padding-left:0" for="gender" class="col-form-label col-md-12">Gender</label>
                            <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                <label id="gender-male" class="col-md-3 offset-md-3 btn btn-secondary">
                                    <input type="radio" name="gender" autocomplete="off" value="Male" checked>Boy
                                </label>
                                <label id="gender-female" class="col-md-3 btn btn-secondary">
                                    <input type="radio" name="gender" autocomplete="off" value="Female">Girl
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








    <script type="text/javascript" src="{{ URL::asset('js/school.js') }}"></script>

@endsection
