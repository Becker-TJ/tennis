@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tournaments</div>
                        <div class="card-body">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                <tr class="fa fa-sort-name" align="center">
                                    <th scope="col">Name</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Number of Teams</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tournaments as $tournament)
                                <tr>
                                    <td class="table-cell">{{$tournament->name}}</td>
                                    <td class="table-cell">{{$tournament->location_name}}</td>
                                    <td align="center" class="table-cell">{{$tournament->team_count}}</td>
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
