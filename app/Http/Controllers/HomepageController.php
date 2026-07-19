<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewDemandRequest;
use App\Mail\ContactForm;
use App\Mail\ContactFormConfirm;
use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Reference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function index(): View
    {
        return view('homepage', [
            'certificates' => Certificate::orderBy('sort_order')->orderBy('id')->get(),
            'references' => Reference::with('images')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function email(NewDemandRequest $request): RedirectResponse
    {
        if (! $this->recaptchaPassed($request->input('recaptcha_response'))) {
            Log::info('Recaptcha failed', ['data' => $request->except('recaptcha_response')]);

            return back()
                ->withInput()
                ->with('error', 'Nepodařilo se ověřit, že nejste robot. Zkuste to prosím znovu.');
        }

        $data = $request->validated();

        Customer::create($data);

        Mail::to(config('mail.from.address'))->send(new ContactForm($data));
        Mail::to($data['email'])->send(new ContactFormConfirm($data['message']));

        return to_route('homepage')
            ->with('success', 'Děkujeme, zpráva byla odeslána. Brzy se Vám ozveme.');
    }

    /**
     * Ověření reCAPTCHA v3. Když ověřovací službu nelze zavolat, formulář
     * se neodešle -- ale návštěvník dostane srozumitelnou hlášku místo
     * chybové stránky.
     */
    protected function recaptchaPassed(?string $token): bool
    {
        if (! $token) {
            return false;
        }

        $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify?'.http_build_query([
            'secret' => config('services.recaptcha.secret'),
            'response' => $token,
            'remoteip' => request()->ip(),
        ]));

        if ($response === false) {
            Log::warning('Recaptcha: ověřovací službu se nepodařilo zavolat.');

            return false;
        }

        return (bool) (json_decode($response, true)['success'] ?? false);
    }
}
