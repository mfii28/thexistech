<?php
/**
 * contact-handler.php
 * Handles contact form POST submissions via AJAX or standard HTTP POST.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/mailer.php';

// Detect if this is an AJAX/fetch request
$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
           || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($is_ajax) {
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
        exit;
    }
    header('Location: index.php');
    exit;
}

// ── Honeypot Anti-Spam Check ────────────────────────────────────────────────
// Real users won't fill this field because it is hidden with CSS and tabindex="-1"
if (!empty($_POST['_gotcha'])) {
    // Silently succeed to fool bots without sending anything
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Thank you for your message!']);
        exit;
    }
    header('Location: index.php?sent=1#contact');
    exit;
}

// ── Sanitize & Validate Inputs ──────────────────────────────────────────────
$name    = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')), ENT_QUOTES, 'UTF-8');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars(strip_tags(trim($_POST['subject'] ?? '')), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')), ENT_QUOTES, 'UTF-8');

$errors = [];

if (empty($name) || mb_strlen($name) < 2) {
    $errors[] = 'Please provide your full name (at least 2 characters).';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if (empty($subject) || mb_strlen($subject) < 3) {
    $errors[] = 'Please specify a subject (at least 3 characters).';
}
if (empty($message) || mb_strlen($message) < 10) {
    $errors[] = 'Your message must be at least 10 characters long.';
}

if (!empty($errors)) {
    if ($is_ajax) {
        http_response_code(422);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        exit;
    }
    header('Location: index.php?error=1&msg=' . urlencode(implode(' ', $errors)) . '#contact');
    exit;
}

// ── Send Email ──────────────────────────────────────────────────────────────
$result = send_contact_email([
    'name'    => $name,
    'email'   => $email,
    'subject' => $subject,
    'message' => $message
]);

if ($is_ajax) {
    header('Content-Type: application/json');
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(500);
    }
    echo json_encode($result);
    exit;
}

// Non-AJAX fallback redirect
if ($result['success']) {
    header('Location: index.php?sent=1#contact');
} else {
    header('Location: index.php?error=1#contact');
}
exit;
