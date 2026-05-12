<?php
// app/Controllers/AdminController.php

require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/Models/User.php';
require_once ROOT . '/app/Models/Scholarship.php';
require_once ROOT . '/app/Models/Application.php';
require_once ROOT . '/app/Models/Document.php';

class AdminController extends Controller
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
        $auth = $this->auth();
        if (!in_array($auth['role'] ?? '', ['admin', 'reviewer'], true)) {
            $this->redirect('/');
        }

        // Stats
        $userCounts = [];
        foreach ($this->user->countByRole() as $row) {
            $userCounts[$row['role']] = (int)$row['total'];
        }
        $appCounts = [];
        foreach ($this->application->countByStatus() as $row) {
            $appCounts[$row['status']] = (int)$row['total'];
        }

        $recentApplications = $this->application->allWithDetails();
        $recentApplications = array_slice($recentApplications, 0, 10);
        $scholarships = $this->scholarship->allWithStats();

        $flash = $this->getFlash();
        $this->view('admin.dashboard', compact('auth', 'userCounts', 'appCounts', 'recentApplications', 'scholarships', 'flash'));
    }

    // ── USERS ─────────────────────────────────────────────
    public function users(): void
    {
        $this->requireRole('admin');
        $auth  = $this->auth();

        // Default role filter: All
        // Important: when `role` is not provided, force empty string
        $role = $_GET['role'] ?? '';
        if ($role === null) $role = '';
        if ($role === '') {
            $role = '';
        } elseif (!in_array($role, ['student', 'reviewer', 'admin'], true)) {
            $role = '';
        }

        $q = $_GET['q'] ?? '';

        $users = $this->user->searchWithRole($role, $q);
        $flash = $this->getFlash();
        $csrf  = $this->generateCsrfToken();

        $this->view('admin.users', compact('auth', 'users', 'role', 'q', 'flash', 'csrf'));
    }

    public function createUserForm(): void
    {
        $this->requireRole('admin');
        $auth = $this->auth();
        $csrf = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('admin.user_form', compact('auth', 'csrf', 'flash'));
    }

    public function createUser(): void
    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();

        $name     = $this->input('name');
        $email    = $this->input('email');
        $password = $this->input('password');
        $role     = $this->input('role');
        $errors   = [];

        if (strlen($name) < 2) $errors[] = 'Name is too short.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
        if (strlen($password) < 8) $errors[] = 'Password must be 8+ characters.';
        if (!in_array($role, ['student', 'reviewer', 'admin'])) $errors[] = 'Invalid role.';
        if (!$errors && $this->user->findByEmail($email)) $errors[] = 'Email already exists.';

        if ($errors) {
            $csrf  = $this->generateCsrfToken();
            $flash = ['error' => implode(' ', $errors)];
            $auth  = $this->auth();
            $this->view('admin.user_form', compact('auth', 'csrf', 'flash', 'name', 'email', 'role'));
            return;
        }

        $this->user->insert([
            'name'       => $name,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'role'       => $role,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->setFlash('success', 'User created successfully.');
        $this->redirect('/admin/users');
    }

    public function editUserForm(string $id): void
    {
        $this->requireRole('admin');
        $auth     = $this->auth();
        $editUser = $this->user->find((int)$id);
        if (!$editUser) $this->redirect('/admin/users');

        $csrf  = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('admin.user_form', compact('auth', 'editUser', 'csrf', 'flash'));
    }

    public function updateUser(string $id): void
    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();

        $data = [
            'name'  => $this->input('name'),
            'email' => $this->input('email'),
            'role'  => $this->input('role'),
        ];
        $newPass = $this->input('password');
        if ($newPass) $data['password'] = password_hash($newPass, PASSWORD_BCRYPT);

        $this->user->update((int)$id, $data);
        $this->setFlash('success', 'User updated.');
        $this->redirect('/admin/users');
    }

    public function deleteUser(string $id): void
    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();
        $auth = $this->auth();
        if ((int)$id === $auth['id']) {
            $this->setFlash('error', 'You cannot delete your own account.');
        } else {
            $this->user->delete((int)$id);
            $this->setFlash('success', 'User deleted.');
        }
        $this->redirect('/admin/users');
    }

    // ── SCHOLARSHIPS ──────────────────────────────────────
    public function scholarships(): void
    {
        $this->requireRole('admin');
        $auth = $this->auth();
        // Auto-close any past-deadline scholarships before displaying
        $this->scholarship->closeExpired();
        $scholarships = $this->scholarship->allWithStats();
        $flash = $this->getFlash();
        $this->view('admin.scholarships', compact('auth', 'scholarships', 'flash'));
    }

    public function createScholarshipForm(): void
    {
        $this->requireRole('admin');
        $auth  = $this->auth();
        $csrf  = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('admin.scholarship_form', compact('auth', 'csrf', 'flash'));
    }

    public function createScholarship(): void
    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();

        $errors = $this->validateScholarshipInput();
        if ($errors) {
            $auth  = $this->auth();
            $csrf  = $this->generateCsrfToken();
            $flash = ['error' => implode('<br>', $errors)];
            $input = $_POST;
            $this->view('admin.scholarship_form', compact('auth', 'csrf', 'flash', 'input'));
            return;
        }

        $this->scholarship->insert($this->buildScholarshipData());
        $this->setFlash('success', 'Scholarship created successfully.');
        $this->redirect('/admin/scholarships');
    }

    public function editScholarshipForm(string $id): void
    {
        $this->requireRole('admin');
        $auth        = $this->auth();
        $scholarship = $this->scholarship->find((int)$id);
        if (!$scholarship) $this->redirect('/admin/scholarships');

        $csrf  = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('admin.scholarship_form', compact('auth', 'scholarship', 'csrf', 'flash'));
    }

    public function updateScholarship(string $id): void
    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();

        $errors = $this->validateScholarshipInput();
        if ($errors) {
            $auth        = $this->auth();
            $scholarship = $this->scholarship->find((int)$id);
            $csrf  = $this->generateCsrfToken();
            $flash = ['error' => implode('<br>', $errors)];
            $input = $_POST;
            $this->view('admin.scholarship_form', compact('auth', 'scholarship', 'csrf', 'flash', 'input'));
            return;
        }

        $this->scholarship->update((int)$id, $this->buildScholarshipData());
        $this->setFlash('success', 'Scholarship updated.');
        $this->redirect('/admin/scholarships');
    }

    public function deleteScholarship(string $id): void
    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();
        $this->scholarship->delete((int)$id);
        $this->setFlash('success', 'Scholarship deleted.');
        $this->redirect('/admin/scholarships');
    }

    public function applications(): void
    {
        $this->requireRole('admin');
        $auth  = $this->auth();
        $apps  = $this->application->allWithDetails();
        $flash = $this->getFlash();
        $this->view('admin.applications', compact('auth', 'apps', 'flash'));
    }


    // ── Admin - Applications (Read/Edit/Delete) ─────────────────────────────
    public function applicationShow(string $id): void
    {
        $this->requireRole('admin');
        $auth = $this->auth();

        $app = $this->application->findWithDetails((int)$id);
        if (!$app) {
            $this->setFlash('error', 'Application not found.');
            $this->redirect('/admin/applications');
        }

        $documents = (new Document())->forApplication((int)$id);
        $this->view('student.application_detail', compact('auth', 'app', 'documents'));
    }

    public function applicationEditForm(string $id): void
    {
        if (!in_array(($this->auth()['role'] ?? ''), ['admin', 'reviewer'])) {
            $this->redirect('/');
        }
        $auth = $this->auth();

        $app = $this->application->findWithDetails((int)$id);
        if (!$app) {
            $this->setFlash('error', 'Application not found.');
            $this->redirect('/admin/applications');
        }

        $documents = (new Document())->forApplication((int)$id);
        $csrf = $this->generateCsrfToken();
        $flash = $this->getFlash();

        // Reuse the same view file, but with edit mode
        $isEdit = true;
        $this->view('student.application_detail', compact('auth', 'app', 'documents', 'csrf', 'flash', 'isEdit'));
    }

    public function applicationUpdateEdit(string $id): void
    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();

        $auth = $this->auth();

        $appId = (int)$id;
        $app = $this->application->findWithDetails($appId);
        if (!$app) {
            $this->setFlash('error', 'Application not found.');
            $this->redirect('/admin/applications');
        }

        $status = $this->input('status');
        $notes  = trim((string)$this->input('review_notes'));

        if (!in_array($status, ['approved', 'rejected'], true)) {
            $this->setFlash('error', 'Invalid status.');
            $this->redirect('/admin/applications/' . $id . '/edit');
        }

        // Store admin decision + notes (Reviewed By / Reviewed On)
        $this->application->decide($appId, (int)$auth['id'], $status, $notes);

        $this->setFlash('success', 'Application updated successfully.');
        $this->redirect('/admin/applications/' . $id);
    }

    public function deleteApplication(string $id): void

    {
        $this->requireRole('admin');
        $this->verifyCsrfToken();

        $appId = (int)$id;
        $app   = $this->application->find($appId);

        if (!$app) {
            $this->setFlash('error', 'Application not found.');
            $this->redirect('/admin/applications');
        }

        $this->application->delete($appId);
        $this->setFlash('success', 'Application deleted successfully.');
        $this->redirect('/admin/applications');
    }

    private function validateScholarshipInput(): array
    {
        $errors = [];
        if (strlen($this->input('name')) < 3) $errors[] = 'Name must be at least 3 characters.';
        if (strlen($this->input('description')) < 10) $errors[] = 'Description is too short.';
        if (!is_numeric($this->input('amount')) || (float)$this->input('amount') <= 0) $errors[] = 'Amount must be a positive number.';
        if (empty($this->input('deadline'))) $errors[] = 'Deadline is required.';
        return $errors;
    }

    private function buildScholarshipData(): array
    {
        return [
            'name'             => $this->input('name'),
            'description'      => $this->input('description'),
            'amount'           => (float)$this->input('amount'),
            'deadline'         => $this->input('deadline'),
            'requirements'     => $this->input('requirements'),
            'allows_multiple'  => $this->input('allows_multiple') ? 1 : 0,
            'status'           => $this->input('status', 'active'),
            'slots'            => (int)$this->input('slots') ?: null,
            'updated_at'       => date('Y-m-d H:i:s'),
        ];
    }
}
