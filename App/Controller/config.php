<?php

use App\Model\AdminModel;


function verify_user_credentials(string $username, string $password): bool {
	try {
		$adminModel = new AdminModel();
		$result = $adminModel->verifyLogin($username, $password);
		return $result['success'];
	} catch (\Throwable $e) {
		return false;
	}
}

function current_user() {
	return $_SESSION['user'] ?? null;   
}

function current_user_role() {
	return $_SESSION['user_role'] ?? 'Admin';
}
