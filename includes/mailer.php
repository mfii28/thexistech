<?php
/**
 * includes/mailer.php
 * Robust email delivery engine supporting native cPanel mail() and pure PHP SMTP.
 */

require_once __DIR__ . '/config.php';

/**
 * Send an email notification for a new contact form submission.
 *
 * @param array $payload ['name' => ..., 'email' => ..., 'subject' => ..., 'message' => ...]
 * @return array ['success' => bool, 'message' => string]
 */
function send_contact_email(array $payload): array {
    $name    = trim($payload['name'] ?? '');
    $email   = trim($payload['email'] ?? '');
    $subject = trim($payload['subject'] ?? 'New Message from Website');
    $message = trim($payload['message'] ?? '');

    $to        = MAIL_RECIPIENT;
    $from_mail = MAIL_SENDER;
    $from_name = MAIL_SENDER_NAME;
    $mail_sub  = "[" . SITE_NAME . "] " . $subject;

    $timestamp = date('r');
    $user_ip   = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

    // ── Build Plaintext Body ──────────────────────────────────────────────────
    $plain_body  = "You have received a new contact inquiry from " . SITE_NAME . ".\n\n";
    $plain_body .= "Name:    {$name}\n";
    $plain_body .= "Email:   {$email}\n";
    $plain_body .= "Subject: {$subject}\n";
    $plain_body .= "Date:    {$timestamp}\n";
    $plain_body .= "Sender IP: {$user_ip}\n\n";
    $plain_body .= "--------------------------------------------------------\n";
    $plain_body .= "Message:\n{$message}\n";
    $plain_body .= "--------------------------------------------------------\n";

    // ── Build HTML Body ───────────────────────────────────────────────────────
    $safe_name    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safe_email   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $safe_subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
    $safe_message = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    $html_body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{$safe_subject}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI',Helvetica,Arial,sans-serif; color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding:30px 10px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.06); border:1px solid #e5e7eb;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#6C63FF; padding:24px 30px; text-align:left;">
                            <h1 style="margin:0; color:#ffffff; font-size:22px; font-weight:600; letter-spacing:0.5px;">Thexis Tech</h1>
                            <p style="margin:4px 0 0 0; color:#e0e7ff; font-size:13px;">New Contact Form Submission</p>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding:30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px; font-size:14px;">
                                <tr>
                                    <td style="padding:8px 0; color:#6b7280; width:110px;"><strong>Full Name:</strong></td>
                                    <td style="padding:8px 0; color:#111827;"><strong>{$safe_name}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#6b7280;"><strong>Email Address:</strong></td>
                                    <td style="padding:8px 0;"><a href="mailto:{$safe_email}" style="color:#6C63FF; text-decoration:none;">{$safe_email}</a></td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#6b7280;"><strong>Subject:</strong></td>
                                    <td style="padding:8px 0; color:#111827;">{$safe_subject}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#6b7280;"><strong>Date:</strong></td>
                                    <td style="padding:8px 0; color:#6b7280; font-size:12px;">{$timestamp}</td>
                                </tr>
                            </table>

                            <div style="background-color:#f9fafb; border-left:4px solid #6C63FF; padding:18px; border-radius:4px; margin-top:15px;">
                                <h3 style="margin:0 0 8px 0; font-size:14px; color:#4b5563; text-transform:uppercase; letter-spacing:0.5px;">Message:</h3>
                                <p style="margin:0; font-size:15px; line-height:1.6; color:#1f2937;">{$safe_message}</p>
                            </div>

                            <div style="margin-top:30px; text-align:center;">
                                <a href="mailto:{$safe_email}?subject=Re:%20{$safe_subject}" style="display:inline-block; background-color:#6C63FF; color:#ffffff; padding:12px 24px; text-decoration:none; border-radius:6px; font-weight:600; font-size:14px;">Reply to {$safe_name}</a>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f9fafb; padding:16px 30px; border-top:1px solid #e5e7eb; font-size:12px; color:#9ca3af; text-align:center;">
                            Sent automatically from your website form at <a href="https://' . SITE_DOMAIN . '" style="color:#6C63FF;">' . SITE_DOMAIN . '</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;

    // ── Dispatch via configured driver ────────────────────────────────────────
    if (MAIL_DRIVER === 'smtp' && !empty(SMTP_HOST) && !empty(SMTP_PASS)) {
        return send_via_smtp($to, $mail_sub, $plain_body, $html_body, $from_mail, $from_name, $email, $name);
    }

    return send_via_php_mail($to, $mail_sub, $plain_body, $html_body, $from_mail, $from_name, $email, $name);
}

/**
 * Native cPanel mail() dispatcher with proper MIME boundary and headers.
 */
function send_via_php_mail(
    string $to,
    string $subject,
    string $plain,
    string $html,
    string $from_mail,
    string $from_name,
    string $reply_mail,
    string $reply_name
): array {
    $boundary = "==MULTIPART_BOUNDARY_" . md5(uniqid((string)time(), true));

    $headers   = [];
    $headers[] = "From: =?UTF-8?B?" . base64_encode($from_name) . "?= <{$from_mail}>";
    $headers[] = "Reply-To: =?UTF-8?B?" . base64_encode($reply_name) . "?= <{$reply_mail}>";
    $headers[] = "Return-Path: <{$from_mail}>";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Date: " . date('r');
    $headers[] = "Message-ID: <" . md5(uniqid((string)time(), true)) . "@" . (parse_url('http://' . SITE_DOMAIN, PHP_URL_HOST) ?? 'localhost') . ">";
    $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";

    $message  = "--{$boundary}\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $message .= $plain . "\r\n\r\n";

    $message .= "--{$boundary}\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $message .= $html . "\r\n\r\n";

    $message .= "--{$boundary}--";

    $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    $header_string   = implode("\r\n", $headers);

    // -f flag sets envelope sender (vital for cPanel SPF/DKIM verification)
    $params = "-f" . escapeshellarg($from_mail);

    $sent = @mail($to, $encoded_subject, $message, $header_string, $params);
    if (!$sent) {
        // Fallback without -f in case mail server restricts parameters
        $sent = @mail($to, $encoded_subject, $message, $header_string);
    }

    if ($sent) {
        return ['success' => true, 'message' => 'Thank you! Your message has been sent successfully. We will get back to you shortly.'];
    }

    return ['success' => false, 'message' => 'Server was unable to send email. Please reach out to ' . MAIL_RECIPIENT . ' directly.'];
}

/**
 * Pure PHP lightweight socket SMTP client (zero dependencies).
 */
function send_via_smtp(
    string $to,
    string $subject,
    string $plain,
    string $html,
    string $from_mail,
    string $from_name,
    string $reply_mail,
    string $reply_name
): array {
    $host = (SMTP_SECURE === 'ssl' ? 'ssl://' : '') . SMTP_HOST;
    $port = SMTP_PORT;

    $socket = @fsockopen($host, $port, $errno, $errstr, 15);
    if (!$socket) {
        // Fall back to mail()
        return send_via_php_mail($to, $subject, $plain, $html, $from_mail, $from_name, $reply_mail, $reply_name);
    }

    $read = function() use ($socket) {
        $res = '';
        while ($str = fgets($socket, 515)) {
            $res .= $str;
            if (substr($str, 3, 1) === ' ') break;
        }
        return $res;
    };

    $send = function($cmd) use ($socket, $read) {
        fputs($socket, $cmd . "\r\n");
        return $read();
    };

    $read();
    $send("EHLO " . (parse_url('http://' . SITE_DOMAIN, PHP_URL_HOST) ?? 'localhost'));

    if (SMTP_SECURE === 'tls') {
        $send("STARTTLS");
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        $send("EHLO " . (parse_url('http://' . SITE_DOMAIN, PHP_URL_HOST) ?? 'localhost'));
    }

    $send("AUTH LOGIN");
    $send(base64_encode(SMTP_USER));
    $send(base64_encode(SMTP_PASS));

    $send("MAIL FROM: <{$from_mail}>");
    $send("RCPT TO: <{$to}>");
    $send("DATA");

    $boundary = "==MULTIPART_BOUNDARY_" . md5(uniqid((string)time(), true));

    $headers  = "From: =?UTF-8?B?" . base64_encode($from_name) . "?= <{$from_mail}>\r\n";
    $headers .= "To: <{$to}>\r\n";
    $headers .= "Reply-To: =?UTF-8?B?" . base64_encode($reply_name) . "?= <{$reply_mail}>\r\n";
    $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
    $headers .= "Date: " . date('r') . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";

    $content  = "--{$boundary}\r\n";
    $content .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $content .= $plain . "\r\n\r\n";
    $content .= "--{$boundary}\r\n";
    $content .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
    $content .= $html . "\r\n\r\n";
    $content .= "--{$boundary}--\r\n.";

    $res = $send($headers . $content);
    $send("QUIT");
    fclose($socket);

    if (strpos($res, '250') !== false) {
        return ['success' => true, 'message' => 'Thank you! Your message has been sent successfully.'];
    }

    return send_via_php_mail($to, $subject, $plain, $html, $from_mail, $from_name, $reply_mail, $reply_name);
}
