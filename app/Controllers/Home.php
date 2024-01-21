<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('firm-panel/static/content');
    }
}
