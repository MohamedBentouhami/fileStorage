@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-left">
        <x-alerte />
        <div class="col-md-8">
            <div class="card">
                <h2 style="align-self: center; "><b>Shared Files</b></h2>
                <ul class="list-group">
                    @if(count($result) === 0)
                    <p></p>
                    @else
                    <table id="table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>File name</th>
                                <th>Owner</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($result as $file)
                        <tbody>
                            <tr>
                                <td>{{$file->name}}</td>
                                <td>{{$file->contactName}}</td>
                                <td>
                                    <a href=" sharedFile/download/{{$file->id}}" class=" btn btn-secondary" style="background-color: green;">
                                        Download
                                    </a>

                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
