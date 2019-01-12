<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-12-16
 * Time: 16:12
 */

namespace App\Http\Controllers\System;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $path = request()->file('upfile')->storePublicly('public/company');
        return $this->success('success', [
            'path' => $path,
            'url' => Storage::url($path)
        ]);
    }
}