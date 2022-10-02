@extends('layouts.app')

@section('content')
@include('components.alerte')

<div class="container">
    <div class="row justify-content-left">
        <div class="col-md-8">
            <div class="card">
                <h2 style="align-self: center; "><b>My Files</b></h2>
                <ul class="list-group">
                    @if(count($result) === 0)
                    <p></p>
                    @else
                    <table id="table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>File name</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($result as $file)
                        <tbody>
                            <tr>
                                <td>{{$file->name}}</td>
                                <td>

                                    <a onclick="edit(' {{$file->id}}')" class="btn btn-secondary px-3 me-2" style="background-color: #F0F8FF; color: black" ;>
                                        Edit
                                    </a>
                                    <a class="btn btn-secondary me-1" onclick="printContact(' {{$file->id}}')" style="background-color: #FAEBD7; color: black" ;>
                                        Share
                                    </a>
                                    <a href=" myFiles/download/{{$file->id}}" class=" btn btn-secondary me-1" style="background-color: green;">
                                        Download
                                    </a>
                                    <a href="myFiles/delete/{{$file->id}}" class="btn btn-danger me-1">
                                        Delete
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
        <div id="contactList" class="col-2 row align-items-center" style="visibility: hidden;">

            <form action="{{ route('sendFile') }}" method="post">
                @csrf
                <ul style="list-style: none;">

                    @foreach($contacts as $contact)
                    <li>
                        <input type="radio" value="{{$contact->id}}" name="contactName" class="mt-1" required> {{$contact->name}}
                    </li>

                    @endforeach
                </ul>
                @if(count($contacts) != 0)

                @csrf
                <button type=" submit" name="idFile" value="" class=" btn btn-secondary" style="background-color: green;">Send</button>
                <a onclick="cancelActionShare()" class=" btn btn-secondary" style="background-color: green;">
                    Cancel </a>

                @else
                <p style="text-align: center;"><b>No contact</b></p>
                @endif
            </form>
        </div>

        <!-- edit part-->
        <div id="editPart" class="col-sm row align-items-center" style="visibility: hidden;">

            <form action="/myFiles/edit" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="fileEdit" class="form-control" id="fileInputEdit" accept=".jpg,.png,.pdf,.docx,.txt" required>
                </div>
                <br>
                <div class="form-group">
                    <button type=" submit" name="idFileEdit" value="" class=" btn btn-secondary" style="background-color: green;">Save</button>
                    <a onclick="cancelActionEdit()" class=" btn btn-secondary" style="background-color: green;">
                        Cancel </a>

                </div>

            </form>

        </div>

    </div>
</div>
<br>
<div class="container">
    <div>
        <div class=" col-md-8">
            <div class="card">
                <h2 style="align-self: center; "><b>Adding Files</b></h2>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                </div>

                <form action="/myFiles" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="file" class="form-control" id="fileInput" accept=".jpg,.png,.pdf,.docx,.txt" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <button class=" btn btn-primary" type="submit" onclick="addFile()" class="for-control" style="margin-bottom: 1em;">Add your file
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        maxFileSixe = 10 * 1024 * 1024; //10MB
        $("#fileInput").change(function() {
            fileSize = this.files[0].size;
            if (fileSize > maxFileSixe) {
                this.setCustomValidity("You can upload only files under 10 MB");
                this.reportValidity();
            } else {
                this.setCustomValidity("");

            }

        });
        $("#fileInputEdit").change(function() {
            fileSize = this.files[0].size;
            if (fileSize > maxFileSixe) {
                this.setCustomValidity("You can upload only files under 10 MB");
                this.reportValidity();
            } else {
                this.setCustomValidity("");

            }

        });

    })

    function addFile(id) {
        let tab;
        $.get("./myFiles", function(data, status) {});
    }

    function printContact(idFile) {
        cancelActionEdit();
        $("#contactList").css("visibility", "visible");

        console.log(idFile);
        let elem = $("[name='idFile']");
        $("[name='idFile']").val(idFile);

    }

    function edit(idFile) {
        cancelActionShare();
        $("#editPart").css("visibility", "visible");
        console.log(idFile);
        $("[name='idFileEdit']").val(idFile);
    }

    function cancelActionShare() {

        $("#contactList").css("visibility", "hidden");

    }

    function cancelActionEdit() {

        $("#editPart").css("visibility", "hidden");


    }
</script>


@endsection