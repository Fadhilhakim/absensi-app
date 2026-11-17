<?php
namespace App\Controller;

use App\Model\AdminModel;

class AdminController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    public function index() {
        // Check if user is sAdmin
        if ($_SESSION['user_role'] !== 'sAdmin') {
            header('Location: dashboard.php?page=dashboard');
            exit;
        }

        $admins = [];
        $message = '';
        $messageType = '';

        // Handle POST requests (CRUD operations)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;

            if ($action === 'create') {
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $role = $_POST['role'] ?? 'Admin';

                if (!$username || !$password) {
                    $message = 'Username dan password harus diisi';
                    $messageType = 'error';
                } else {
                    $result = $this->adminModel->create($username, $password, $role);
                    $message = $result['message'];
                    $messageType = $result['success'] ? 'success' : 'error';
                }
            } elseif ($action === 'update') {
                $id = (int)($_POST['id'] ?? 0);
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $role = $_POST['role'] ?? 'Admin';

                if (!$username) {
                    $message = 'Username harus diisi';
                    $messageType = 'error';
                } else {
                    $result = $this->adminModel->update($id, $username, $role, $password ?: null);
                    $message = $result['message'];
                    $messageType = $result['success'] ? 'success' : 'error';
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                $result = $this->adminModel->delete($id);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
            }
        }

        // Get all admins
        $admins = $this->adminModel->getAll();

        $data = [
            'admins' => $admins,
            'message' => $message,
            'messageType' => $messageType,
        ];

        ob_start();
        require_once 'App/View/dashboard/admin_management.php';
        $content = ob_get_clean();
        require_once 'App/View/dashboard.php';
    }
}
?>
