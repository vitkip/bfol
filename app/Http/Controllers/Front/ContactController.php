<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('front.contact');
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:120',
            'phone'   => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:300',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create([
            ...$data,
            'language'   => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', __('app.contact.sent'));
    }
}
