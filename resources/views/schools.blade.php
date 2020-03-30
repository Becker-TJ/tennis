@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Schools</div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name" align="center">
                                <th>Seq.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Location</th>
                                <th scope="col">Number of Teams</th>
                                <th scope="col">Level</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($schools as $school)
                                <tr>
                                    <td>{{$school->id}}</td>
                                    <td class="table-cell">{{$school->name}}</td>
                                    <td class="table-cell">{{$school->address}}</td>
                                    <td align="center" class="table-cell">{{$school->class}}</td>
                                    <td align="center" class="table-cell">{{$school->id}}</td>
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
