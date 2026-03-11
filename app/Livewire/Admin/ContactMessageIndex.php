<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ContactMessageIndex extends Component
{
    use WithPagination;

    public ?int $expandedId = null;

    public function toggleExpand(int $id): void
    {
        $this->expandedId = $this->expandedId === $id ? null : $id;
    }

    public function toggleRead(int $id): void
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => ! $message->is_read]);
    }

    public function deleteMessage(int $id): void
    {
        ContactMessage::findOrFail($id)->delete();
    }

    public function render(): View
    {
        return view('livewire.admin.contact-message-index', [
            'messages' => ContactMessage::orderByDesc('created_at')->paginate(20),
            'unreadCount' => ContactMessage::where('is_read', false)->count(),
        ])->layout('layouts.app.sidebar');
    }
}
