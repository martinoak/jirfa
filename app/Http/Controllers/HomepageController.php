<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewDemandRequest;
use App\Mail\ContactForm;
use App\Mail\ContactFormConfirm;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function index(): View
    {
        return view('homepage');
    }

    public function email(NewDemandRequest $request): RedirectResponse
    {
        $secretKey = env('GOOGLE_RECAPTCHA_SECRET_KEY');
        $responseKey = $request->get('recaptcha_response');
        $ip = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$ip";
        $response = file_get_contents($url);
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            Log::info('Recaptcha failed', ['data' => $request->all()]);
            abort(403, 'Recaptcha failed');
        }

        Customer::create($request->except('_token'));

        Mail::to(config('mail.from.address'))->send(new ContactForm($request->all()));
        Mail::to($request->input('email'))->send(new ContactFormConfirm($request->input('message')));

        return to_route('homepage');
    }
}
