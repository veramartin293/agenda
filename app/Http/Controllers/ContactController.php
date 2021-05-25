<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Get contacts for the authenticated user
        $contacts = Contact::where('user_id', $user->id)->get();

        return response($contacts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate data
        $validatedData = $request->validate([
            'name' => 'bail|required',
            'phone' => 'bail|required',
            'email' => 'bail|required',
            'favorite' => 'bail|required'
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Create the new Contact
        $contact = new Contact;
        $contact->name = $validatedData['name'];
        $contact->phone = $validatedData['phone'];
        $contact->email = $validatedData['email'];
        $contact->favorite = $validatedData['favorite'];
        $contact->user_id = $user->id;

        // Store contact in database
        $contact->save();

        // Return the response
        return response($contact, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Get the contact
        $contact = Contact::
                   where('user_id', $user->id)->
                   where('id', $id)->
                   first();
        
        return response($contact, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate data
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'favorite' => 'required'
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Get the contact
        $contact = Contact::
                   where('user_id', $user->id)->
                   where('id', $id)->
                   first();

        // Store the changes
        // $contact->update($request->all());
        $contact->name = $validatedData['name'];
        $contact->phone = $validatedData['phone'];
        $contact->email = $validatedData['email'];
        $contact->favorite = $validatedData['favorite'];
        $contact->save();

        return response($contact, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $contact = Contact::
                   where('user_id', $user->id)->
                   where('id', $id)->
                   first();
              
        return response($contact->delete(), 200);
    }

    /**
     * Search for a name.
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        // Get the authenticated user
        $user = auth()->user();

        return Contact::
        where('name', 'like', '%'.$name.'%')->
        where('user_id', $user->id)->
        get();
    }
}
