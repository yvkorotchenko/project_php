<?php

namespace App\Http\Controllers;

/**
 * Class MtSportsController
 * @package App\Http\Controllers
 */

class MtSportsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('mtsport');
    }
}
