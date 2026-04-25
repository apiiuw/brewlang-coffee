<?php

namespace App\Http\Controllers;

use App\Mail\ContactAcknowledgementMail;
use App\Mail\ContactInquiryMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $adminAddress = config('mail.from.address');

        Mail::to($adminAddress)->send(new ContactInquiryMail($validated));
        Mail::to($validated['email'])->send(new ContactAcknowledgementMail($validated));

        return back()->with('success', 'Your message has been sent. We will get back to you soon.');
    }
}
