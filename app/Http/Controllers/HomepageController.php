<?php

namespace App\Http\Controllers;

use App\Mail\ContactForm;
use App\Mail\ContactFormConfirm;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function index(): View
    {
        return view('homepage');
    }

    public function email(Request $request): RedirectResponse
    {
        $validator = Validator::validate($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'tel' => 'required|min:9',
            'message' => 'required'
        ], [
            'name.required' => 'Pole se jménem je povinné',
            'email.required' => 'Pole s emailem je povinné',
            'email.email' => 'Email není ve správném formátu',
            'tel.required' => 'Pole s telefonem je povinné',
            'tel.min' => 'Telefoní číslo musí mít alespoň 9 znaků',
            'message.required' => 'Pole se zprávou je povinné'
        ]);

        Mail::to(config('mail.from.address'))->send(new ContactForm($request->all()));
        Mail::to($request->input('email'))->send(new ContactFormConfirm($request->input('message')));

        Customer::create($request->except('_token'));

        return to_route('homepage');
    }
}
