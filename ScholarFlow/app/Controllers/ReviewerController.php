<?php
// app/Controllers/ReviewerController.php

require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/Models/Application.php';
require_once ROOT . '/app/Models/Document.php';
require_once ROOT . '/app/Models/Scholarship.php';

class ReviewerController extends Controller
{
    private Application $application;
    private Document $document;

    public function __construct()
    {
        $this->application = new Application();
        $this->document    = new Document();
    }

    public function dashboard(): void
    {
        $this->requireRole('reviewer');
        $auth = $this->auth();

        $pending  = $this->application->allPending();
        $stats    = [];
        foreach ($this->application->countByStatus() as $row) {
            $stats[$row['status']] = (int)$row['total'];
        }

        $flash = $this->getFlash();
        $this->view('reviewer.dashboard', compact('auth', 'pending', 'stats', 'flash'));
    }

    public function applications(): void
    {
        $this->requireRole('reviewer');
        $auth   = $this->auth();
        $status = $_GET['status'] ?? '';
        $applications = $this->application->allWithDetails($status);
        $flash = $this->getFlash();
        $this->view('reviewer.applications', compact('auth', 'applications', 'status', 'flash'));
    }

    public function review(string $id): void
    {
        $this->requireRole('reviewer');
        $auth = $this->auth();
        $app  = $this->application->findWithDetails((int)$id);
        if (!$app) $this->redirect('/reviewer/applications');

        $documents = $this->document->forApplication((int)$id);
        $csrf  = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('reviewer.review', compact('auth', 'app', 'documents', 'csrf', 'flash'));
    }

    public function decide(string $id): void
    {
        $this->requireRole('reviewer');
        $this->verifyCsrfToken();
        $auth = $this->auth();

        $status = $this->input('status');
        $notes  = $this->input('review_notes');

        if (!in_array($status, ['approved', 'rejected'])) {
            $this->setFlash('error', 'Invalid decision.');
            $this->redirect('/reviewer/applications/' . $id);
        }

        $this->application->decide((int)$id, $auth['id'], $status, $notes);

        $msg = $status === 'approved'
            ? 'Application approved successfully.'
            : 'Application has been rejected.';

        $this->setFlash('success', $msg);
        $this->redirect('/reviewer/applications');
    }
}
