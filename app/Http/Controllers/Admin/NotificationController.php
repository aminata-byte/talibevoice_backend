<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('admin')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'type' => 'required|in:don_valide,redistribution,insertion_talibe,besoin_urgent,offre_validee',
            'destinataire_type' => 'required|in:agent,partenaire',
            'destinataire_id' => 'required|integer',
        ]);

        $notification = Notification::create([
            'admin_id' => $request->user()->id,
            'message' => $request->message,
            'type' => $request->type,
            'destinataire_type' => $request->destinataire_type,
            'destinataire_id' => $request->destinataire_id,
            'est_lue' => false,
            'date_envoi' => now(),
        ]);

        return response()->json($notification, 201);
    }
}
