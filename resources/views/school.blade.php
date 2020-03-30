@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Roster for {{$school->name}}</div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary col-md-2 offset-md-5">Add New Player</button>
                        <hr>
                        <table id="schoolTable" class="display table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name" align="center">
                                <th scope="col">Seq.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Name</th>
                                <th scope="col">Location</th>
                                <th scope="col">Number of Teams</th>
                                <th scope="col">Level</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($players as $player)
                                <tr>
                                    <td>{{$player->id}}</td>
                                    <td class="varsity_order">{{$varsityOrder[$increment++]}}</td>
                                    <td class="table-cell">{{$player->first_name}}</td>
                                    <td class="table-cell">{{$player->last_name}}</td>
                                    <td align="center" class="table-cell">{{$player->school_id}}</td>
                                    <td align="center" class="table-cell">{{$player->id}}</td>
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
