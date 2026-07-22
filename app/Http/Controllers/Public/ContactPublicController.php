<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactPublicController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email',
            'sujet' => 'required|string',
            'message' => 'required|string',
        ]);

        Contact::create($request->only(['nom', 'email', 'sujet', 'message']));

        return response()->json([
            'message' => 'Message envoyé ! Nous vous répondrons dans les plus brefs délais.',
        ], 201);
    }
}
