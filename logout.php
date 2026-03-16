<?php
session_start();

// If the user is an admin (any admin session), after logout send them to admin login
$was_admin = isset($_SESSION['admin_username']) || (isset($_SESSION['admin_role']) && !empty($_SESSION['admin_role']));

// clear session
$_SESSION = [];
if (ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params['path'], $params['domain'],
		$params['secure'], $params['httponly']
	);
}
session_destroy();

if ($was_admin) {
	header("Location: login_admin.php");
} else {
	header("Location: index.php");
}
exit();
?>