<?php
// app/Controllers/StudentController.php

require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/Models/User.php';
require_once ROOT . '/app/Models/Scholarship.php';
require_once ROOT . '/app/Models/Application.php';

class StudentController extends Controller
{
    private User $user;
    private Scholarship $scholarship;
    private Application $application;

    public function __construct()
    {
        $this->user        = new User();
        $this->scholarship = new Scholarship();
        $this->application = new Application();
    }

    public function dashboard(): void
    {
        $this->requireRole('student');
        $auth = $this->auth();

        $myApplications = $this->application->forStudent($auth['id']);
        $stats = [
            'total'    => count($myApplications),
            'pending'  => count(array_filter($myApplications, fn($a) => $a['status'] === 'pending')),
            'approved' => count(array_filter($myApplications, fn($a) => $a['status'] === 'approved')),
            'rejected' => count(array_filter($myApplications, fn($a) => $a['status'] === 'rejected')),
        ];

        $availableScholarships = $this->scholarship->availableForStudent($auth['id']);
        $flash = $this->getFlash();
        $this->view('student.dashboard', compact('auth', 'myApplications', 'stats', 'availableScholarships', 'flash'));
    }

    public function scholarships(): void
    {
        $this->requireRole('student');
        $auth = $this->auth();
        $scholarships = $this->scholarship->availableForStudent($auth['id']);
        $flash = $this->getFlash();
        $this->view('student.scholarships', compact('auth', 'scholarships', 'flash'));
    }

    public function scholarshipDetail(string $id): void
    {
        $this->requireRole('student');
        $auth = $this->auth();
        $scholarship = $this->scholarship->find((int)$id);
        if (!$scholarship) {
            $this->redirect('/scholarships');
        }

        $check = $this->scholarship->isAvailableForStudent((int)$id, $auth['id']);
        $this->view('student.scholarship_detail', compact('auth', 'scholarship', 'check'));
    }

    public function profileForm(): void
    {
        $this->requireRole('student');
        $auth    = $this->auth();
        $profile = $this->user->find($auth['id']);
        $csrf    = $this->generateCsrfToken();
        $flash   = $this->getFlash();
        $this->view('student.profile', compact('auth', 'profile', 'csrf', 'flash'));
    }

    public function updateProfile(): void
    {
        $this->requireRole('student');
        $this->verifyCsrfToken();
        $auth = $this->auth();

        $data = [
            'name'       => $this->input('name'),
            'phone'      => $this->input('phone'),
            'address'    => $this->input('address'),
            'course'     => $this->input('course'),
            'school'     => $this->input('school'),
            'gpa'        => $this->input('gpa') ?: null,
            'year_level' => $this->input('year_level') ?: null,
        ];

        // Handle avatar upload
        $avatar = $this->file('avatar');
        if ($avatar) {
            $path = $this->uploadFile($avatar, 'avatars');
            if ($path) $data['avatar'] = $path;
        }

        $this->user->updateProfile($auth['id'], $data);

        // Update session name
        $_SESSION['user']['name'] = $data['name'];

        $this->setFlash('success', 'Profile updated successfully!');
        $this->redirect('/profile');
    }
}
