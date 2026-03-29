<?php

namespace App\Http\Controllers;

use App\Models\MailboxMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailboxController extends Controller
{
    public function index(): View
    {
        $messages = MailboxMessage::where('user_id', auth()->id())
            ->orderByRaw('read_at IS NULL DESC')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('mailbox.index', compact('messages'));
    }

    public function markRead(MailboxMessage $message): RedirectResponse
    {
        abort_if($message->user_id !== auth()->id(), 403);
        $message->markAsRead();
        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        MailboxMessage::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Tous les messages ont été marqués comme lus.');
    }

    public function destroy(MailboxMessage $message): RedirectResponse
    {
        abort_if($message->user_id !== auth()->id(), 403);
        $message->delete();
        return back();
    }
}
