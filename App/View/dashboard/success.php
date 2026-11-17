<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>

<div class="alert-success">
    <?= htmlspecialchars($_SESSION['success']); ?>
</div>

<?php if (isset($_SESSION['success'])) unset($_SESSION['success']); ?>
