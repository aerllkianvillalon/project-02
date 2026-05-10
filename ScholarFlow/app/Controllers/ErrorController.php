<?php
// app/Controllers/ErrorController.php

require_once ROOT . '/core/Controller.php';

class ErrorController extends Controller
{
    public function notFound(): void
    {
        http_response_code(404);
        $auth = $this->auth();
        $this->view('errors.404', compact('auth'));
    }
}