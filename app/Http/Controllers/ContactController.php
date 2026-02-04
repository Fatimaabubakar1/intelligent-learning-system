<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Send email
        try {
            // Send to your email (admin email)
            Mail::to('support@lingnaija.com')
                ->send(new ContactFormMail($validated));

            // Optionally, send a confirmation to the user
            if (!empty($validated['email'])) {
                Mail::to($validated['email'])
                    ->send(new \App\Mail\ContactConfirmationMail($validated));
            }

            return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Contact form error: ' . $e->getMessage());

            return back()->with('error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }
}
