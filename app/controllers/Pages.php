<?php

class Pages extends Controller
{
    public function __construct()
    {
    }

    // default method
    public function index()
    {
        $data = [
            'title' => SITENAME,
            'description' => 'PHP Exam built on the MVC PHP Framework'
        ];

        $this->view('pages/index', $data);
    }

    public function about()
    {

        $data = [
            'title' => 'Abou Us',
            'description' => 'PHP Exam.'
        ];
        $this->view('pages/about', $data);
    }
}
