<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json(
            Contact::orderBy('created_at', 'desc')->get()
        );
    }

    public function marquerLu($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['est_lu' => true]);
        return response()->json(['message' => 'Message marqué comme lu.']);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(['message' => 'Message supprimé.']);
    }
}
