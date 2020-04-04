@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$tableTitle}}</div>
                    <div id="playerSortHeader" class="card-body">
                        <form method="POST" action="players">
                            @csrf
                            <label for="conference_settings" class="col-md-3 col-form-label text-md-right">Class</label>
                            <div id="conference_settings" class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label class="btn btn-secondary @if($radioButtonSettings['conference_setting'] == "all_classes") active @endif" >
                                    <input type="radio" name="conference_setting" id="all_classes" autocomplete="off" value="all_classes"
                                           @if($radioButtonSettings['conference_setting'] == "all_classes") checked @endif> All
                                </label>
                                <label class="btn btn-secondary @if($radioButtonSettings['conference_setting'] == "6A") active @endif">
                                    <input type="radio" name="conference_setting" id="6A" autocomplete="off" value="6A"
                                           @if($radioButtonSettings['conference_setting'] == "6A") checked @endif> 6A
                                </label>
                                <label class="btn btn-secondary @if($radioButtonSettings['conference_setting'] == "5A") active @endif">
                                    <input type="radio" name="conference_setting" id="5A" autocomplete="off" value="5A"
                                           @if($radioButtonSettings['conference_setting'] == "5A") checked @endif> 5A
                                </label>
                                <label class="btn btn-secondary @if($radioButtonSettings['conference_setting'] == "4A") active @endif">
                                    <input type="radio" name="conference_setting" id="4A" autocomplete="off" value="4A"
                                    @if($radioButtonSettings['conference_setting'] == "4A") checked @endif> 4A
                                </label>
                                <label class="btn btn-secondary @if($radioButtonSettings['conference_setting'] == "3A") active @endif">
                                    <input type="radio" name="conference_setting" id="3A" autocomplete="off" value="3A"
                                           @if($radioButtonSettings['conference_setting'] == "3A") checked @endif> 3A
                                </label>
                            </div>

                            <label for="genders" class="col-md-3 col-form-label text-md-right">Gender</label>
                            <div id="genders" class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label for="boys" class="btn btn-secondary @if($radioButtonSettings['gender'] == "Male") active @endif">
                                    <input class="form-control" type="radio" name="gender" id="boys" autocomplete="off" value="Male"
                                           @if($radioButtonSettings['gender'] == "Male") checked @endif> Boys
                                </label>
                                <label for="girls" class="btn btn-secondary @if($radioButtonSettings['gender'] == "Female") active @endif">
                                    <input class="form-control" type="radio" name="gender" id="girls" autocomplete="off" value="Female"
                                           @if($radioButtonSettings['gender'] == "Female") checked @endif> Girls
                                </label>
                            </div>

                            <label for="bracket_rank" class="col-md-3 col-form-label text-md-right">Bracket</label>

                            <div id="bracket_rank" class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label class="btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "one_singles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="one_singles" autocomplete="off" value="one_singles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "one_singles_rank") checked @endif> 1 Singles
                                </label>
                                <label class="btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "two_singles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="two_singles" autocomplete="off" value="two_singles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "two_singles_rank") checked @endif> 2 Singles
                                </label>
                                <label class="btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "one_doubles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="one_doubles" autocomplete="off" value="one_doubles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "one_doubles_rank") checked @endif> 1 Doubles
                                </label>
                                <label class="btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "two_doubles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="two_doubles" autocomplete="off" value="two_doubles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "two_doubles_rank") checked @endif> 2 Doubles
                                </label>
                            </div>

                            <hr>
                            <div class="col-md-4 offset-md-4">
                                <button id="updateByPlayerSortSettingsButton" type="submit" class="btn btn-primary col-md-6 offset-md-3">Update</button>
                            </div>

                        </form>

                        <table id="myTable" class="table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name" align="center">
                                <th scope="col">Rank</th>
                                <th scope="col">Name</th>
                                <th scope="col">School</th>
                                <th scope="col">Class</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($players as $player)
                                <tr>
                                    <td class="table-cell">{{$player->one_singles_rank}}</td>
                                    <td class="table-cell">{{$player->first_name . ' ' . $player->last_name}}</td>
                                    <td class="table-cell">{{$player->name}}</td>
                                    <td align="center" class="table-cell">{{$player->conference}}</td>
                                    <td align="center" class="table-cell"><i class="material-icons" style="color:green">mode_edit</i><i class="material-icons" style="color:red">delete</i></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
