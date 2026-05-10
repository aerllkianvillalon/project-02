<?php
// app/Controllers/ApplicationController.php

require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/Models/Scholarship.php';
require_once ROOT . '/app/Models/Application.php';
require_once ROOT . '/app/Models/Document.php';

class ApplicationController extends Controller
{
    private Scholarship $scholarship;
    private Application $application;
    private Document $document;

    public function __construct()
    {
        $this->scholarship = new Scholarship();
        $this->application = new Application();
        $this->document    = new Document();
    }

    public function form(string $scholarshipId): void
    {
        $this->requireRole('student');
        $auth   = $this->auth();
        $sId    = (int)$scholarshipId;

        $check = $this->scholarship->isAvailableForStudent($sId, $auth['id']);
        if (!$check['available']) {
            $this->setFlash('error', $check['reason']);
            $this->redirect('/scholarships');
        }

        $scholarship = $check['scholarship'];
        $csrf  = $this->generateCsrfToken();
        $flash = $this->getFlash();
        $this->view('student.apply', compact('auth', 'scholarship', 'csrf', 'flash'));
    }

    public function submit(string $scholarshipId): void
    {
        $this->requireRole('student');
        $this->verifyCsrfToken();
        $auth = $this->auth();
        $sId  = (int)$scholarshipId;

        $check = $this->scholarship->isAvailableForStudent($sId, $auth['id']);
        if (!$check['available']) {
            $this->setFlash('error', $check['reason']);
            $this->redirect('/scholarships');
        }

        $errors = [];
        $essay  = $this->input('essay');
        if (strlen($essay) < 50) {
            $errors[] = 'Personal statement must be at least 50 characters.';
        }

        // Validate required documents
        $requiredDocs = ['transcript', 'id_document'];
        foreach ($requiredDocs as $docType) {
            if (empty($_FILES[$docType]['name'])) {
                $errors[] = ucwords(str_replace('_', ' ', $docType)) . ' is required.';
            }
        }

        if ($errors) {
            $scholarship = $check['scholarship'];
            $csrf  = $this->generateCsrfToken();
            $flash = ['error' => implode('<br>', $errors)];
            $this->view('student.apply', compact('auth', 'scholarship', 'csrf', 'flash', 'essay'));
            return;
        }

        // Create application
        $appId = $this->application->insert([
            'user_id'        => $auth['id'],
            'scholarship_id' => $sId,
            'essay'          => $essay,
            'status'         => 'pending',
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        // Upload documents
        $docTypes = ['transcript', 'id_document', 'recommendation', 'other'];
        foreach ($docTypes as $docType) {
            $file = $this->file($docType);
            if (!$file) continue;
            $path = $this->uploadFile($file, 'documents');
            if ($path) {
                $this->document->addDocument($appId, $docType, $path, $file['name']);
            }
        }

        $this->setFlash('success', 'Application submitted successfully! We will review it shortly.');
        $this->redirect('/applications');
    }

    public function myApplications(): void
    {
        $this->requireRole('student');
        $auth = $this->auth();
        $applications = $this->application->forStudent($auth['id']);
        $flash = $this->getFlash();
        $this->view('student.applications', compact('auth', 'applications', 'flash'));
    }

    public function show(string $id): void
    {
        $this->requireRole('student', 'reviewer', 'admin');
        $auth = $this->auth();
        $app  = $this->application->findWithDetails((int)$id);

        if (!$app) $this->redirect('/applications');

        // Students can only see their own
        if ($auth['role'] === 'student' && $app['user_id'] != $auth['id']) {
            $this->redirect('/applications');
        }

        $documents = $this->document->forApplication((int)$id);
        $this->view('student.application_detail', compact('auth', 'app', 'documents'));
    }
}
