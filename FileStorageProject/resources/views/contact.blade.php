@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <x-alerte />
        <div class="col-md-8">
            <div class="card">
                <h2 style="align-self: center; "><b>Contact List</b></h2>
                <ul class="list-group">

                    <p>Number of contacts : {{$contacts->count()}}</p>
                    @if($contacts->count())
                    <table id="table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Contact name</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($contacts as $contact)
                        <tbody>
                            <tr>
                                <td>{{$contact->name}}</a></td>
                                <td class="text-right">
                                    <form action="{{ route('contact.delete',$contact->id) }}" method="post">
                                        @csrf
                                        <button class=" btn btn-danger">
                                            Delete
                                        </button>
                                    </form>

                                </td>

                            </tr>

                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h2 style="align-self: center; "><b>Add a new Contact</b></h2>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form action="{{ route('contact.add') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">

                            <input type="email" name="email" size="30" placeholder="Type the mail of your new contact..." class="form-control" required>
                            @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="form-group">
                            <button class=" btn btn-primary" type="submit" class="for-control" style="margin-bottom: 1em;">Add a new contact
                        </div>

                    </form>
                </div>


            </div>
        </div>
    </div>
</div>

@include('partials.notificationList')



@endsection
