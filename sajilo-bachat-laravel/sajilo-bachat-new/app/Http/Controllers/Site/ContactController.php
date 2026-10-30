<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('site.contact');
    }

    public function store(ContactMessageRequest $request)
    {
        $message = ContactMessage::create($request->safe()->except('website') + ['ip_address' => $request->ip()]);

        if ($to = setting('contact_recipient')) {
            try {
                Mail::raw(
                    "From: {$message->name} <{$message->email}> {$message->phone}\n\n{$message->message}",
                    fn ($m) => $m->to($to)->subject('[Website] '.$message->subject)->replyTo($message->email, $message->name)
                );
            } catch (\Throwable $e) {
                // The message is already stored; a mail failure must not lose it or break the page.
                Log::warning('Contact notification mail failed: '.$e->getMessage());
            }
        }

        return redirect()->route('contact')->with('success', ui('contact.sent'));
    }
}
