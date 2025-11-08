<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected string $base_main_view = '';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view($this->base_main_view);
    }

}