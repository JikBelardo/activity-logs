<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}


if (isset($_POST['insertNewBranchBtn'])) {
	$address = trim($_POST['firstname']);
	$head_manager = trim($_POST['lastname']);
	$contact_number = trim($_POST['specialization']);

	if (!empty($address) && !empty($head_manager) && !empty($contact_number)) {
		$insertABranch = insertABranch($pdo, $address, $head_manager, 
			$contact_number, $_SESSION['username']);
		$_SESSION['status'] =  $insertABranch['status']; 
		$_SESSION['message'] =  $insertABranch['message']; 
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../index.php");
	}

}

if (isset($_POST['updateBranchBtn'])) {

	$address = $_POST['firstname'];
	$head_manager = $_POST['lastname'];
	$contact_number = $_POST['specialization'];
	$date = date('Y-m-d H:i:s');

	if (!empty($address) && !empty($head_manager) && !empty($contact_number)) {

		$updateBranch = updateBranch($pdo, $address, $head_manager, $contact_number, 
			$date, $_SESSION['username'], $_GET['job_id']);

		$_SESSION['message'] = $updateBranch['message'];
		$_SESSION['status'] = $updateBranch['status'];
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_POST['deleteBranchBtn'])) {
	$branch_id = $_GET['job_id'];

	if (!empty($branch_id)) {
		$deleteBranch = deleteABranch($pdo, $branch_id);
		$_SESSION['message'] = $deleteBranch['message'];
		$_SESSION['status'] = $deleteBranch['status'];
		header("Location: ../index.php");
	}
}

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['username']);
	header("Location: ../login.php");
}

?>