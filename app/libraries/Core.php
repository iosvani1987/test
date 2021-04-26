<?php

/**
 * App Core Class
 * Create URL & Loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getURL();
        //print_r($url);
        // Look in controllers for first value
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) { // No found open default page Pages.php
            # if exists, set as controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 index
            unset($url[0]);
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                # code...
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            } else {
                redirect('pages/index');
            }
        };

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getURL()
    {
        if (isset($_GET['url'])) { // Verified if URL is not Null
            $url = rtrim($_GET['url'], '/'); // Delete Characters
            $url = filter_var($url, FILTER_SANITIZE_URL); // Valudate URL
            $url = explode('/', $url); //Split URL
            return $url;
        }
    }
}
