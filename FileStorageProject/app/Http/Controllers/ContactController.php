<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contacts = auth()->user()->contacts()->wherePivot('state', Contact::CONFIRM)->get();
        $contactsRequest = auth()->user()->contactsRequest()->wherePivot('state', Contact::PENDING)->get();


        return view('contact', ['contacts' => $contacts, 'contactsRequest' => $contactsRequest]);
    }

    public function addContact(AddContactRequest $request)
    {
        $contact = User::where('email', $request->email)->firstOrFail();
        auth()->user()->contacts()->attach($contact->id);
        return back()->with(['success' => "Friend request has been sent"]);
    }

    public function acceptContact($id)
    {
        $contactsRequest = auth()->user()->contactsRequest()->wherePivot('state', Contact::PENDING)
            ->wherePivot('id', $id)->firstOrFail();

        auth()->user()->contactsRequest()->updateExistingPivot($contactsRequest->id, ['state' => Contact::CONFIRM]);
        auth()->user()->contacts()->attach($contactsRequest->id, ['state' => Contact::CONFIRM]);

        return back()->with(['success' => "Request accept"]);
    }

    public function declineContact($id)
    {
        $contactsRequest = auth()->user()->contactsRequest()->wherePivot('state', Contact::PENDING)
            ->wherePivot('id', $id)->firstOrFail();
        auth()->user()->contactsRequest()->detach($contactsRequest->id);


        return back()->with(['warning' => "Request decline"]);
    }

    public function deleteContact($id)
    {

        Contact::deleteContact($id);
        return back()->with(['warning' => "Contact deleted"]);
    }
}
