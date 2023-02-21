@extends('layouts.app')
@section('title', 'Player Rankings')

@section('content')
    <br>
    <div id="playerForStatsModal" data-player-table="playerDisplayTable" data-singles={{$singles}} data-player-id="0" style="display:none"></div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$tableTitle}}</div>
                    <div id="playerSortHeader" class="card-body">
                        <form method="POST" action="players">
                            @csrf
                            <label for="conference_settings" class="format-label col-md-3 col-form-label text-md-right">Class</label>
                            <div id="conference_settings" class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label class="first-button-in-row button-in-row btn btn-secondary @if($radioButtonSettings['conference_setting'] == "all_classes") active @endif" >
                                    <input type="radio" name="conference_setting" id="all_classes" autocomplete="off" value="all_classes"
                                           @if($radioButtonSettings['conference_setting'] == "all_classes") checked @endif> All
                                </label>
                                <label class="button-in-row btn btn-secondary @if($radioButtonSettings['conference_setting'] == "6A") active @endif">
                                    <input type="radio" name="conference_setting" id="6A" autocomplete="off" value="6A"
                                           @if($radioButtonSettings['conference_setting'] == "6A") checked @endif> 6A
                                </label>
                                <label class="last-button-in-row button-in-row btn btn-secondary @if($radioButtonSettings['conference_setting'] == "5A") active @endif">
                                    <input type="radio" name="conference_setting" id="5A" autocomplete="off" value="5A"
                                           @if($radioButtonSettings['conference_setting'] == "5A") checked @endif> 5A
                                </label>
                                <label class="last-button-in-row button-in-row btn btn-secondary @if($radioButtonSettings['conference_setting'] == "4A") active @endif">
                                    <input type="radio" name="conference_setting" id="4A" autocomplete="off" value="4A"
                                    @if($radioButtonSettings['conference_setting'] == "4A") checked @endif> 4A
                                </label>
                            </div>
                            <br>
                            <br>
                            <label for="bracket_rank" class="format-label col-md-3 col-form-label text-md-right">Bracket</label>

                            <div id="bracket_rank" class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label class="button-in-row first-button-in-row btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "boys_one_singles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="one_singles" autocomplete="off" value="boys_one_singles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "boys_one_singles_rank") checked @endif> #1 Singles
                                </label>
                                <label class="button-in-row btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "boys_two_singles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="two_singles" autocomplete="off" value="boys_two_singles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "boys_two_singles_rank") checked @endif> #2 Singles
                                </label>
                                <label class="button-in-row last-button-in-row btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "boys_one_doubles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="one_doubles" autocomplete="off" value="boys_one_doubles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "boys_one_doubles_rank") checked @endif> #1 Doubles
                                </label>
                                <label class="button-in-row last-button-in-row btn btn-secondary @if($radioButtonSettings['bracket_rank'] == "boys_two_doubles_rank") active @endif">
                                    <input type="radio" name="bracket_rank" id="two_doubles" autocomplete="off" value="boys_two_doubles_rank"
                                           @if($radioButtonSettings['bracket_rank'] == "boys_two_doubles_rank") checked @endif> #2 Doubles
                                </label>
                            </div>
                            <br>
                            <br>

                            <label for="genders" class="format-label col-md-3 col-form-label text-md-right">Gender</label>
                            <div id="genders" class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label for="boys" class="button-in-row first-button-in-row btn btn-secondary @if($radioButtonSettings['gender'] == "Male") active @endif">
                                    <input class="form-control" type="radio" name="gender" id="boys" autocomplete="off" value="Male"
                                           @if($radioButtonSettings['gender'] == "Male") checked @endif> Boys
                                </label>
                                <label for="girls" class="button-in-row btn btn-secondary @if($radioButtonSettings['gender'] == "Female") active @endif">
                                    <input class="form-control" type="radio" name="gender" id="girls" autocomplete="off" value="Female"
                                           @if($radioButtonSettings['gender'] == "Female") checked @endif> Girls
                                </label>
                            </div>

                            <hr>
                            <div class="col-md-4 offset-md-4">
                                <button id="updateByPlayerSortSettingsButton" type="submit" class="submit-button button-in-row btn btn-primary col-md-6 offset-md-3">Update</button>
                            </div>
                            <br>

                        </form>

                        <table id="playerDisplayTable" class="table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name" align="center">
                                <th hidden scope="col">Seq</th>
                                <th hidden scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">School</th>
                                <th scope="col">Class</th>
                                <th scope="col">Rank</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($players as $player)
                                <tr>
                                    <td hidden align="center" class="table-cell">{{$player->id}}</td>
                                    <td hidden align="center" class="table-cell">{{$player->id}}</td>
                                    <td class="table-cell"><button class="player button-in-row playerModalToggle">{{$player->first_name. ' ' . $player->last_name}}</button></td>
                                    <td class="table-cell"><a href="/school/{{$player->school_id}}"><button class="button-in-row">{{$player->name}}</button></a></td>
                                    <td align="center" class="table-cell">{{$player->conference}}</td>
                                    <td class="table-cell">{{$player->$bracket_rank}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
                    <button type="button" class="button-in-row cancel-button btn btn-secondary col-md-2" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/players.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/playerstatsmodal.js') }}"></script>
@endsection
