<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>

<div class="alert-error">
    <?= htmlspecialchars($_SESSION['error']); ?>
</div>

<?php if (isset($_SESSION['error'])) unset($_SESSION['error']); ?>