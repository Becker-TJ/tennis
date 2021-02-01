@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$tournament->name}}</div>
                    <div class="card-header">{{date('m-d-Y', strtotime($tournament->date))}} ({{date('g:ia', strtotime($tournament->time))}} Start)</div>
                    <div class="card-header">Location: {{$tournament->location_name}}</div>
                    <div class="card-header">Address: {{$tournament->address}}</div>
                    <button class="btn btn-primary col-md-2 offset-md-5" onclick="location.href='/createtournament/{{$tournament->id}}'" type="button">Edit Tournament</button>
                    <button type="button" class="btn btn-primary col-md-2 offset-md-5" data-toggle="modal" data-target="#inviteTeamsModal">Invite Teams</button>
                    <div class="card-body">
                        <div class="modal fade" id="inviteTeamsModal" tabindex="-1" role="dialog" aria-labelledby="inviteTeamsModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
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
                                                <label for="new_player_first_name" class="col-form-label">First Name</label>
                                                <input type="text" class="form-control" id="new_player_first_name">
                                            </div>
                                            <div class="form-group">
                                                <label for="new_player_last_name" class="col-form-label">Last Name</label>
                                                <input type="text" class="form-control" id="new_player_last_name">
                                            </div>
                                            <div class="form-group">
                                                <label style="padding-left:0" for="class" class="col-form-label col-md-12">Class</label>
                                                <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                                    <label class="btn btn-secondary active">
                                                        <input type="radio" name="class" id="class" autocomplete="off" value="9" checked> Freshman
                                                    </label>
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="class" id="class" autocomplete="off" value="10"> Sophomore
                                                    </label>
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="class" id="class" autocomplete="off" value="11"> Junior
                                                    </label>
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="class" id="class" autocomplete="off" value="12"> Senior
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label style="padding-left:0" for="gender" class="col-form-label col-md-12">Gender</label>
                                                <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                                                    <label class="col-md-3 offset-md-3 btn btn-secondary active">
                                                        <input type="radio" name="gender" id="gender" autocomplete="off" value="varsity" checked>Boy
                                                    </label>
                                                    <label class="col-md-3 btn btn-secondary">
                                                        <input type="radio" name="gender" id="gender" autocomplete="off" value="jv">Girl
                                                    </label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary col-md-2" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary col-md-2">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <table id="schoolTable" class="display table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name" align="center">
                                <th scope="col">Seq.</th>
                                <th scope="col">Position</th>
                                <th scope="col">Name</th>
                                <th scope="col">Location</th>
                                <th scope="col">Number of Teams</th>
                                <th scope="col">Level</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                               @foreach($attendees as $attendee)
                                <tr>
                                    <td class="table-cell">{{$attendee->id}}</td>
                                    <td class="table-cell">{{$attendee->id}}</td>
                                    <td class="table-cell">hi</td>
                                    <td class="table-cell">hi</td>
                                    <td align="center" class="table-cell">hi</td>
                                    <td align="center" class="table-cell">hi</td>
                                    <td align="center" class="table-cell"><i class="material-icons" style="color:green">mode_edit</i><i class="material-icons" style="color:red">delete</i></td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                        <br>
                        <button id="savePlayerPositionsButton" class="btn btn-primary col-md-2 offset-md-5">Save Roster Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
