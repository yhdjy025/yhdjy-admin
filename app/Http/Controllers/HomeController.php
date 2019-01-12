<?php

namespace App\Http\Controllers;

use App\Service\Common\ResponseService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function err()
    {
        $code = \request('code');
        $message = ResponseService::getErrorMessage($code);
        return view('error', ['message' => $message ? $message : '页面错误！']);
    }
}
