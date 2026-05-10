<?php
// routes/web.php — All Application Routes

// ── Public / Auth ─────────────────────────────────────────────
$router->get('/',              'AuthController@index');
$router->get('/login',         'AuthController@loginForm');
$router->post('/login',        'AuthController@authenticate');
$router->get('/register',      'AuthController@registerForm');
$router->post('/register',     'AuthController@register');
$router->get('/logout',        'AuthController@logout');

// ── Student ───────────────────────────────────────────────────
$router->get('/dashboard',              'StudentController@dashboard');
$router->get('/profile',                'StudentController@profileForm');
$router->post('/profile',               'StudentController@updateProfile');
$router->get('/scholarships',           'StudentController@scholarships');
$router->get('/scholarships/:id',       'StudentController@scholarshipDetail');

// Applications
$router->get('/apply/:scholarshipId',   'ApplicationController@form');
$router->post('/apply/:scholarshipId',  'ApplicationController@submit');
$router->get('/applications',           'ApplicationController@myApplications');
$router->get('/applications/:id',       'ApplicationController@show');

// ── Reviewer ──────────────────────────────────────────────────
$router->get('/reviewer',               'ReviewerController@dashboard');
$router->get('/reviewer/applications',  'ReviewerController@applications');
$router->get('/reviewer/applications/:id', 'ReviewerController@review');
$router->post('/reviewer/applications/:id', 'ReviewerController@decide');

// ── Admin ─────────────────────────────────────────────────────
$router->get('/admin',                  'AdminController@dashboard');

// Admin - Users
$router->get('/admin/users',            'AdminController@users');
$router->get('/admin/users/create',     'AdminController@createUserForm');
$router->post('/admin/users/create',    'AdminController@createUser');
$router->get('/admin/users/:id/edit',   'AdminController@editUserForm');
$router->post('/admin/users/:id/edit',  'AdminController@updateUser');
$router->post('/admin/users/:id/delete','AdminController@deleteUser');

// Admin - Scholarships
$router->get('/admin/scholarships',             'AdminController@scholarships');
$router->get('/admin/scholarships/create',      'AdminController@createScholarshipForm');
$router->post('/admin/scholarships/create',     'AdminController@createScholarship');
$router->get('/admin/scholarships/:id/edit',    'AdminController@editScholarshipForm');
$router->post('/admin/scholarships/:id/edit',   'AdminController@updateScholarship');
$router->post('/admin/scholarships/:id/delete', 'AdminController@deleteScholarship');

// Admin - Applications
$router->get('/admin/applications',    'AdminController@applications');
