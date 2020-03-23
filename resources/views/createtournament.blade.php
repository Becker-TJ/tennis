@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Tournament') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">


                        <div class="form-group row">
                            <label for="tournament_name" class="col-md-4 col-form-label text-md-right">{{ __('Tournament Name') }}</label>

                            <div class="col-md-6">
                                <input id="tournament_name" type="text" class="form-control" name="name" required autofocus autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="location_name" class="col-md-4 col-form-label text-md-right">{{ __('Location Name') }}</label>

                            <div class="col-md-6">
                                <input id="location_name" type="text" class="form-control" name="location_name" required autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Location Address') }}</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address" required autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="privacy_setting" class="col-md-4 col-form-label text-md-right">{{ __('Public or Private') }}</label>

                            <div class="btn-group btn-group-toggle col-md-6" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="privacy_setting" id="private" autocomplete="off" value="public"> Public
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="privacy_setting" id="public" autocomplete="off" value="private"> Private
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="team_count" class="col-md-4 col-form-label text-md-right">{{ __('Number of Teams (Max 16)') }}</label>
                            <div class="col-md-6">
                                <select class="form-control" id="team_count" name="team_count">
                                    <?php for($x = 4; $x <= 16; $x++) {?><option><?php echo $x;?></option><?php }?>}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
