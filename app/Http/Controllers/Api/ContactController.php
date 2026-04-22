<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                                      ->orWhere('email', 'like', "%$s%")
                                      ->orWhere('subject', 'like', "%$s%"));
        }

        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        $contacts = $query->paginate($request->get('per_page', 20));

        return response()->json($contacts->through(fn($c) => $this->format($c)));
    }

    public function show(ContactMessage $contact)
    {
        return response()->json($this->format($contact));
    }

    public function markRead(ContactMessage $contact)
    {
        $contact->update(['is_read' => true]);

        return response()->json(['message' => 'ໝາຍວ່າອ່ານແລ້ວ']);
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return response()->json(['message' => 'ລຶບສຳເລັດ']);
    }

    private function format(ContactMessage $c): array
    {
        return [
            'id'         => $c->id,
            'name'       => $c->name,
            'email'      => $c->email,
            'phone'      => $c->phone,
            'subject'    => $c->subject,
            'message'    => $c->message,
            'is_read'    => $c->is_read,
            'created_at' => $c->created_at?->format('d/m/Y H:i'),
        ];
    }
}
