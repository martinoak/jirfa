<?php

namespace App\Http\Controllers;

use App\Services\GitPublisher;
use Illuminate\Http\RedirectResponse;

class PublishController extends Controller
{
    public function __invoke(GitPublisher $publisher): RedirectResponse
    {
        $result = $publisher->publish('Aktualizace obrázků z administrace');

        return back()
            ->with($result['ok'] ? 'success' : 'error', $result['message'])
            ->with('gitOutput', $result['output']);
    }
}
