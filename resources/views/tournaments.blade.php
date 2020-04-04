@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tournaments</div>
                        <div class="card-body">
                            <div class="alert alert-success col-md-12" role="alert">
                                <p style="text-align:center">You can sort tournaments by clicking column headings.  Shift + click a second or even third column heading to further refine your search. For example, if I wanted to see all Varsity tournaments sorted by date, I could click "Level" then shift + click "Date". </p>
                            </div>
                            <table id="tournamentsTable" class="table table-striped">
                                <thead>
                                <tr class="fa fa-sort-name" align="center">
                                    <th scope="col">Name</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Teams</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tournaments as $tournament)
                                <tr>
                                    <td class="table-cell">{{$tournament->name}}</td>
                                    <td class="table-cell">{{$tournament->location_name}}</td>
                                    <td class="table-cell">{{$tournament->date}}</td>
                                    <td class="table-cell">{{$tournament->gender}}</td>
                                    <td align="center" class="table-cell">{{count($schoolAttendants->where('tournament_id', '=', $tournament->id)) . '/' . $tournament->team_count}}</td>
                                    <td align="center" class="table-cell">{{$tournament->level}}</td>
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
