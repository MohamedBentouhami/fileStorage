@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 style="align-self: center; "><b> File Storage</b></h2>

                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div style="text-align: center;">
                        Welcome {{Auth::user()->name}} ! <br>
                    </div>
                    {{ __('You are logged in !') }}


                </div>
            </div>
        </div>
    </div>
</div>

@endsection
