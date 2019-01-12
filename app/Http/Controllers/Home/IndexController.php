<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/12/4
 * Time: 9:06
 */

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('home.index');
    }
}
