@extends('layouts.app')
@section('title', 'Add School')
@section('content')
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Which School Do You Coach?</div>
                    <div class="card-body">
                        <form method="POST" action="addschool">
                            @csrf

                            <div class="alert alert-success col-md-12" role="alert">
                                <p>If your school already exists in the database, select it from the list below.
                                    <br>
                                    Otherwise, check "School Not Listed" and add it.</p>
                            </div>

                            <div class="form-group row">
                                <label for="school_id_to_tie" class="col-md-4 col-form-label text-md-right">Existing Schools</label>
                                <div class="col-md-6">
                                    <select class="select2 form-control" id="school_id_to_tie" name="school_id_to_tie">
                                        <option value="" disabled selected>Select School</option>
                                        @foreach($schools as $school)
                                        <option value="{{ $school->id }}">
                                            {{ $school->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="not_listed" class="col-md-4 col-form-label text-md-right">
                                    School Not Listed
                                </label>
                                <div class="col-md-1">
                                    <input type='hidden' value="false" name='not_listed'>
                                    <input style="min-width: 20px" class="form-control" value="true" name="not_listed" type="checkbox" id="not_listed">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="school_name" class="col-md-4 col-form-label text-md-right">School Name</label>
                                <div class="col-md-6">
                                    <input disabled id="school_name" type="text" class="form-control toggle_show" name="school_name" required autofocus autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="school_address" class="col-md-4 col-form-label text-md-right">School Address</label>
                                <div class="col-md-6">
                                    <input disabled id="school_address" type="text" class="form-control toggle_show" name="school_address" required autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="school_class" class="col-md-4 col-form-label text-md-right">Class</label>
                                <div class="col-md-6">
                                    <select disabled class="form-control toggle_show" id="school_class" name="school_class">
                                        <option></option>
                                        <option>2A</option>
                                        <option>3A</option>
                                        <option>4A</option>
                                        <option>5A</option>
                                        <option>6A</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button id="switch_button_name" type="submit" class="btn btn-primary col-md-6 offset-md-3">Tie Existing School</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/addSchool.js') }}"></script>
@endsection
