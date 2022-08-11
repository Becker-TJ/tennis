@extends('layouts.app')
@section('title', 'Schools')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Schools</div>
                    <div class="card-body">
                        <table id="allSchoolsTable" class="table table-striped">
                            <thead>
                            <tr class="fa fa-sort-name" align="center">
                                <th scope="col">School</th>
                                <th scope="col">Location</th>
                                <th scope="col">Class</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($schools as $school)
                                <tr>
                                    <td class="table-cell"><a href="/school/{{$school->id}}">{{$school->name}}</a></td>
                                    <td class="table-cell">{{$school->address}}</td>
                                    <td align="center" class="table-cell">{{$school->conference}}</td>
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
