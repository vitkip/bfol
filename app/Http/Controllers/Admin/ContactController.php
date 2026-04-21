<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private const LANGUAGES = [
        'lo' => ['label' => 'ລາວ', 'class' => 'bg-amber-100 text-amber-700'],
        'en' => ['label' => 'EN',   'class' => 'bg-blue-100 text-blue-700'],
        'zh' => ['label' => 'ZH',   'class' => 'bg-green-100 text-green-700'],
    ];

    public function index(Request $request)
    {
        $query = ContactMessage::latest('created_at');

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',    'like', "%{$s}%")
                  ->orWhere('email',   'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%");
            });
        }

        if ($request->language) {
            $query->where('language', $request->language);
        }

        if ($request->read !== null && $request->read !== '') {
            $query->where('is_read', (bool) $request->read);
        }

        $messages   = $query->paginate(20)->withQueryString();
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.contacts.index', [
            'messages'    => $messages,
            'unreadCount' => $unreadCount,
            'languages'   => self::LANGUAGES,
        ]);
    }

    public function show(ContactMessage $contact)
    {
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', [
            'contact'   => $contact,
            'languages' => self::LANGUAGES,
        ]);
    }

    public function markRead(ContactMessage $contact)
    {
        $contact->update(['is_read' => !$contact->is_read]);

        return back()->with('success', $contact->is_read ? 'ໝາຍວ່າອ່ານແລ້ວ' : 'ໝາຍວ່າຍັງບໍ່ໄດ້ອ່ານ');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
                         ->with('success', 'ລຶບຂໍ້ຄວາມສຳເລັດ');
    }
}
