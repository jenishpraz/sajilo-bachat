<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if (($term = trim((string) $request->input('q'))) !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($w) => $w->where('name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('subject', 'like', $like)
                ->orWhere('message', 'like', $like));
        }
        if (in_array($request->input('read'), ['0', '1'], true)) {
            $query->where('is_read', $request->input('read') === '1');
        }

        return view('admin.messages.index', ['messages' => $query->paginate(15)->withQueryString()]);
    }

    public function show(ContactMessage $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function toggleRead(ContactMessage $message)
    {
        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('success', $message->is_read ? 'Marked as read.' : 'Marked as unread.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
