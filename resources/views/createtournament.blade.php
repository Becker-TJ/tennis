@extends('layouts.app')
@section('title', 'Create Tournament')
@section('content')

    <br>

    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Create Tournament</div>

                <div class="card-body">
                    <form method="POST" action="createtournament">
                        @csrf

                        <div class="form-group row">
                            <label for="tournament_name" class="format-label col-md-4 col-form-label text-md-right">Tournament Name</label>

                            <div class="col-md-6">
                                <input id="tournament_name" type="text" class="form-control" name="tournament_name" required autofocus autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="location_name" class="format-label col-md-4 col-form-label text-md-right">Location Name</label>

                            <div class="col-md-6">
                                <input id="location_name" type="text" class="form-control" name="location_name" required autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="format-label col-md-4 col-form-label text-md-right">Location Address</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address" required autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="format-label col-md-4 col-form-label text-md-right">Date</label>
                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control" name="date" required autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="time" class="format-label col-md-4 col-form-label text-md-right">Start Time</label>
                            <div class="col-md-6">
                                <input id="time" type="time" class="form-control" name="time" required value="08:00" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="team_count" class="format-label col-md-4 col-form-label text-md-right">Number Of Teams</label>
                            <div class="col-md-6">
                                <select class="form-control" id="team_count" name="team_count">
                                    <option selected="selected">8</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="courts" class="format-label col-md-4 col-form-label text-md-right">Number Of Courts At Venue</label>
                            <div class="col-md-6">
                                <input class="form-control" type="number" id="quantity" name="courts" min="1" max="50">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="format-label col-md-4 col-form-label text-md-right">Gender</label>
                            <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label for="boys" class="btn btn-secondary active button-in-row first-button-in-row">
                                    <input class="form-control" type="radio" name="gender" id="boys" autocomplete="off" value="Boys" checked> Boys
                                </label>
                                <label for="girls" class="btn btn-secondary button-in-row">
                                    <input class="form-control" type="radio" name="gender" id="girls" autocomplete="off" value="Girls"> Girls
                                </label>
                                <label for="both" class="btn btn-secondary button-in-row last-button-in-row">
                                    <input class="form-control" type="radio" name="gender" id="both" autocomplete="off" value="Both"> Both
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="level" class="format-label col-md-4 col-form-label text-md-right">Level</label>

                            <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label class="button-in-row first-button-in-row btn btn-secondary active">
                                    <input type="radio" name="level" id="level" autocomplete="off" value="Varsity" checked> Varsity
                                </label>
                                <label class="button-in-row btn btn-secondary">
                                    <input type="radio" name="level" id="level" autocomplete="off" value="JV"> JV
                                </label>
                                <label class="button-in-row last-button-in-row btn btn-secondary">
                                    <input type="radio" name="level" id="level" autocomplete="off" value="Junior High"> Junior High
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-success col-md-12" role="alert">
                            <p>Open - Any team can join. First come, first served.</p>
                            <hr>
                            <p class="mb-0">Private - Only teams that you invite can join.  This option can be switched to Public at any time to fill teams if needed.</p>
                        </div>

                        <div class="form-group row">
                            <label for="privacy_setting" class="format-label col-md-4 col-form-label text-md-right">Open or Private</label>

                            <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label class="btn btn-secondary active button-in-row first-button-in-row">
                                    <input type="radio" name="privacy_setting" id="privacy_setting" autocomplete="off" value="public"
                                           checked> Open
                                </label>
                                <label class="btn btn-secondary button-in-row">
                                    <input type="radio" name="privacy_setting" id="privacy_setting" autocomplete="off" value="private"> Private
                                </label>
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="submit-button btn btn-primary col-md-12 button-in-row">Create Tournament</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
