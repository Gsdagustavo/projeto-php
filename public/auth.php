<?php
function requireLogin(): void
{
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        exit;
    }
}