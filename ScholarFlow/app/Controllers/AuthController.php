<?php
// app/Controllers/AuthController.php

require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/Models/User.php';

class AuthController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index(): void
    {
        if ($this->auth()) {
            $this->redirectToDashboard();
        }
        $this->redirect('/login');
    }

    public function loginForm(): void
    {
        if ($this->auth()) $this->redirectToDashboard();
        $csrf  = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('auth.login', compact('csrf', 'flash'));
    }

    public function authenticate(): void
    {
        $this->verifyCsrfToken();

        $email    = $this->input('email');
        $password = $this->input('password');
        $errors   = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (empty($password)) {
            $errors[] = 'Password is required.';
        }

        if (!$errors) {
            $user = $this->user->findByEmail($email);
            if (!$user || !$this->user->verifyPassword($password, $user['password'])) {
                $errors[] = 'Invalid email or password.';
            }
        }

        if ($errors) {
            $csrf  = $this->generateCsrfToken();
            $flash = ['error' => implode(' ', $errors)];
            $this->view('auth.login', compact('csrf', 'flash', 'email'));
            return;
        }

        // Set session
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];
        session_regenerate_id(true);

        $this->setFlash('success', 'Welcome back, ' . $user['name'] . '!');
        $this->redirectToDashboard();
    }

    public function registerForm(): void
    {
        if ($this->auth()) $this->redirectToDashboard();
        $csrf  = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('auth.register', compact('csrf', 'flash'));
    }

    public function register(): void
    {
        $this->verifyCsrfToken();

        $name     = $this->input('name');
        $email    = $this->input('email');
        $password = $this->input('password');
        $confirm  = $this->input('password_confirmation');
        $errors   = [];

        if (strlen($name) < 2) $errors[] = 'Name must be at least 2 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';

        if (!$errors && $this->user->findByEmail($email)) {
            $errors[] = 'An account with this email already exists.';
        }

        if ($errors) {
            $csrf  = $this->generateCsrfToken();
            $flash = ['error' => implode(' ', $errors)];
            $this->view('auth.register', compact('csrf', 'flash', 'name', 'email'));
            return;
        }

        $id = $this->user->createStudent(compact('name', 'email', 'password'));
        $_SESSION['user'] = [
            'id'    => $id,
            'name'  => $name,
            'email' => $email,
            'role'  => 'student',
        ];
        session_regenerate_id(true);

        $this->setFlash('success', 'Account created! Welcome to ScholarFlow.');
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }

    private function redirectToDashboard(): void
    {
        $role = $this->auth()['role'] ?? 'student';
        match($role) {
            'admin'    => $this->redirect('/admin'),
            'reviewer' => $this->redirect('/reviewer'),
            default    => $this->redirect('/dashboard'),
        };
    }
}
