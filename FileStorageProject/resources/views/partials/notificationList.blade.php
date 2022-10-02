@if($contactsRequest->count())

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h2 style="align-self: center; "><b>Contact Requests List</b></h2>


                <p>Number of contacts : {{$contactsRequest->count()}}</p>
                <table id="table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Contact name</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach($contactsRequest as $contact)
                    <tbody>
                        <tr>
                            <td>{{$contact->name}}</a></td>
                            <td class="text-right">
                                <a href="{{ route('contact.accept',$contact->pivot->id) }}" class="btn btn-success">
                                    Accept
                                </a>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('contact.decline',$contact->pivot->id) }}" class="btn btn-danger">
                                    Decline
                                </a>

                            </td>

                        </tr>

                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endif