@extends('layouts.app')
@section('title', 'Tournaments')
@section('content')
    <br>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div id="THECARD" class="card">
                    <div class="card-header">Tournaments</div>
                        <div class="card-body">
                            <div class="alert alert-success col-md-12" role="alert">
                                <p style="text-align:center">You can sort tournaments by clicking column headings.  Shift + click a second or even third column heading to further refine your search. For example, if you wanted to see all Varsity tournaments sorted by date, you could click "Level" then shift + click "Date". </p>
                            </div>
                            <table id="tournamentsTable" class="table">
                                <thead>
                                <tr class="fa fa-sort-name" align="center">
                                    <th scope="col">Name</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Teams</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tournaments as $tournament)
                                <tr
                                    <?php
                                        if($tournament->acceptedInvite) {
                                            echo 'class="accepted-invite"';
                                        }
                                        if($tournament->pendingInvite) {
                                            echo 'class="pending-invite"';
                                        }
                                        if($tournament->completed) {
                                            echo 'class="completed-tournament"';
                                        }
                                    ?>
                                >
                                    <td class="table-cell">
                                        <?php
                                            echo '<a href="' . '/tournament/' . $tournament->id . '">' . $tournament->name . '</a>';
                                        ?>
                                    </td>
                                    <td class="table-cell">{{$tournament->location_name}}</td>
                                    <td class="table-cell">{{$tournament->date}}</td>
                                    <td align="center" class="table-cell">{{$tournament->level}}</td>
                                    <td class="table-cell">{{$tournament->gender}}</td>
                                    <td align="center" class="table-cell">
                                        @if($tournament->privacy_setting === "Public")
                                            @if(count($schoolAttendees->where('tournament_id', '=', $tournament->id)) === $tournament->team_count)
                                                {{'Full'}}
                                            @else
                                                {{count($schoolAttendees->where('tournament_id', '=', $tournament->id)) . '/' . $tournament->team_count}}
                                            @endif
                                        @endif
                                        @if($tournament->privacy_setting === "Private")
                                            {{'Private'}}
                                        @endif
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
@endsection
@section('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/tournaments.js') }}"></script>
@endsection
