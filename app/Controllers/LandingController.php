<?php

namespace App\Controllers;

use App\Models\Lead;
use ZealPHP\App;
use ZealPHP\G;
use ZealPHP\WebSocket\TaskWebSocketHandler;
use function ZealPHP\elog;

class LandingController
{
    private Lead $leadModel;

    public function __construct()
    {
        elog('LandingController initialized');
        $this->leadModel = new Lead();
    }

    public function index(): void
    {

        elog("Session" . json_encode($_SESSION));
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            elog("Session id from csrf location: " . session_id());
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            TaskWebSocketHandler::writeSession(session_id() ?? '', $_SESSION);
        }
        elog("After -- Session" . json_encode($_SESSION));

        $testimonials = $this->leadModel->getAll();

        App::render('/landing/index', [
            'title' => 'Selfmade Ninja Academy - Master Cyber Security',
            'description' => 'Transform your career with our comprehensive cyber security course. Learn from India\'s top experts.',
            'testimonials' => $testimonials,
            'button' => 'Register Now'
        ]);

        // Clear flash messages
        unset($_SESSION['success_message'], $_SESSION['errors'], $_SESSION['form_data']);
    }

    public function contact(): void
    {
        $g = G::instance();

        if ($g->server['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $errors = [];
        $formData = [
            'name' => trim($g->post['firstName'] ?? '') . ' ' . trim($g->post['lastName'] ?? ''),
            'email' => trim($g->post['email'] ?? ''),
            'phone' => trim($g->post['phone'] ?? ''),
            'message' => trim($g->post['message'] ?? '')
        ];

        // CSRF validation
        elog('Session data: ' . json_encode($_SESSION));
        elog('POST data: ' . json_encode($g->post));
        if (!isset($g->post['csrf_token']) || $g->post['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $errors[] = 'Invalid security token. Please try again.';
        }

        // Validation
        if (empty($formData['name'])) {
            $errors[] = 'Name is required.';
        } elseif (strlen($formData['name']) < 2) {
            $errors[] = 'Name must be at least 2 characters long.';
        }

        if (empty($formData['email'])) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (!empty($formData['phone']) && !preg_match('/^[\+]?[0-9\s\-\(\)]{10,15}$/', $formData['phone'])) {
            $errors[] = 'Please enter a valid phone number.';
        }

        if (!empty($formData['message']) && strlen($formData['message']) > 1000) {
            $errors[] = 'Message must be less than 1000 characters.';
        }

        // Check for duplicate email
        if (empty($errors) && $this->leadModel->emailExists($formData['email'])) {
            $errors[] = 'This email is already registered. We\'ll contact you soon!';
        }

        if (!empty($errors)) {
            elog("Contact form validation errors: " . json_encode($errors), "warning");
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $formData;
            header('Location: /contact/lead-capture');
            return;
        }

        // Save lead
        try {
            $leadId = $this->leadModel->create($formData);

            if ($leadId) {
                $_SESSION['success_message'] = 'Thank you for your interest! We\'ll contact you within 24 hours.';
                elog("New lead created with ID: $leadId for email: {$formData['email']}", "info");
                header('Location: /');
                return;
            } else {
                throw new \Exception('Failed to save lead data');
            }
        } catch (\Exception $e) {
            elog("Error saving lead: " . $e->getMessage(), "error");
            $_SESSION['errors'] = ['Something went wrong. Please try again later.'];
            $_SESSION['form_data'] = $formData;
        }

        header('Location: /contact/lead-capture');
        return;
    }

    public function contactApi(): array
    {
        $g = G::instance();
        elog('Contact Api initiated');
        if ($g->server['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return ['success' => false, 'message' => 'Method not allowed'];
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $g->post;
        }

        $errors = [];
        $formData = [
            'name' => trim($input['firstName'] ?? '') . ' ' . trim($input['lastName'] ?? ''),
            'email' => trim($input['email'] ?? ''),
            'phone' => trim($input['phone'] ?? ''),
            'message' => trim($input['message'] ?? '')
        ];
        // $headers = \getallheaders();
        $clientToken = $input['csrf_token'] ?? null;

        elog('Session :' . json_encode($_SESSION));
        elog("Client token: $clientToken");
        // elog("headers: " . json_encode($headers));

        // Validation
        if (!isset($clientToken) || $clientToken !== ($_SESSION['csrf_token'] ?? '')) {
            $errors[] = 'Invalid security token. Please try again.';
        }

        if (empty($formData['name'])) {
            $errors['name'] = 'Name is required.';
        } elseif (strlen($formData['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters long.';
        }

        if (empty($formData['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (!empty($formData['phone']) && !preg_match('/^[\+]?[0-9\s\-\(\)]{10,15}$/', $formData['phone'])) {
            $errors['phone'] = 'Please enter a valid phone number.';
        }

        if (!empty($formData['message']) && strlen($formData['message']) > 1000) {
            $errors['message'] = 'Message must be less than 1000 characters.';
        }

        if (!empty($errors)) {
            http_response_code(422);
            return ['success' => false, 'errors' => $errors];
        }

        // Check for duplicate email
        if ($this->leadModel->emailExists($formData['email'])) {
            return [
                'success' => true,
                'message' => 'This email is already registered. We\'ll contact you soon!'
            ];
        }

        // Save lead
        try {
            $leadId = $this->leadModel->create($formData);

            if ($leadId) {
                elog("New lead created via API with ID: $leadId for email: {$formData['email']}", "info");
                return [
                    'success' => true,
                    'message' => 'Thank you for your interest! We\'ll contact you within 24 hours.',
                    'lead_id' => $leadId
                ];
            } else {
                throw new \Exception('Failed to save lead data');
            }
        } catch (\Exception $e) {
            elog("Error saving lead via API: " . $e->getMessage(), "error");
            http_response_code(500);
            return ['success' => false, 'message' => 'Something went wrong. Please try again later.'];
        }
    }
}