<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class FrontendController extends Controller
{
    public function index(): Redirector|RedirectResponse
    {
        return redirect($this->frontendURL);
    }

    public function login(): Redirector | RedirectResponse
    {
        return redirect($this->frontendURL . 'auth/sign-up');
    }
}
