<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string',
        ]);

        $contact = ContactMessage::create($validated);

        $toAddress = config('mail.contact_to.address') ?: config('mail.from.address');
        $toName = config('mail.contact_to.name') ?: null;

        try {
            Mail::to($toAddress, $toName)
                ->send(new ContactMessageReceived($contact));
        } catch (Throwable $e) {
            Log::error('Failed to send contact email', [
                'contactMessageId' => $contact->id,
                'to' => $toAddress,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Message received, but we could not send an email notification right now. Please try again later.',
                'email_sent' => false,
                'data' => $contact,
            ], 500);
        }

        return response()->json([
            'message' => 'Message received! I will get back to you soon.',
            'email_sent' => true,
            'data'    => $contact,
        ], 201);
    }
}
