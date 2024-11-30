<?php  

require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllBranches($pdo) {
	$sql = "SELECT * FROM `job_hunt`";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllBranchesBySearch($pdo, $search_query) {
	$sql = "SELECT * FROM job_hunt WHERE 
			CONCAT(firstname,lastname,
				specialization,
				date_added,added_by,
				last_updated,
				last_updated_by) 
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$search_query."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getBranchByID($pdo, $job_id) {
	$sql = "SELECT * FROM job_hunt WHERE job_id = ?";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute([$job_id])) {
		return $stmt->fetch();
	}
}

function insertAnActivityLog($pdo, $operation, $branch_id, $address, 
		$head_manager, $contact_number, $username) {

	$sql = "INSERT INTO activity_logs (operation, job_id, firstname, 
		lastname, specialization, username) VALUES(?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$operation, $branch_id, $address, 
		$head_manager, $contact_number, $username]);

	if ($executeQuery) {
		return true;
	}

}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function insertABranch($pdo, $address, $head_manager, $contact_number, $added_by) {
	$response = array();
	$sql = "INSERT INTO job_hunt (firstname, lastname, specialization, added_by) VALUES(?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$insertBranch = $stmt->execute([$address, $head_manager, $contact_number, $added_by]);

	if ($insertBranch) {
		$findInsertedItemSQL = "SELECT * FROM job_hunt ORDER BY date_added DESC LIMIT 1";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute();
		$getBranchID = $stmtfindInsertedItemSQL->fetch();

		$insertAnActivityLog = insertAnActivityLog($pdo, "INSERT", $getBranchID['job_id'], 
			$getBranchID['firstname'], $getBranchID['lastname'], 
			$getBranchID['specialization'], $_SESSION['username']);

		if ($insertAnActivityLog) {
			$response = array(
				"status" =>"200",
				"message"=>"Branch addedd successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
		
	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"Insertion of data failed!"
		);

	}

	return $response;
}

function updateBranch($pdo, $address, $head_manager, $contact_number, 
	$last_updated, $last_updated_by, $job_id) {

	$response = array();
	$sql = "UPDATE job_hunt
			SET firstname = ?,
				lastname = ?,
				specialization = ?, 
				last_updated = ?, 
				last_updated_by = ? 
			WHERE job_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$updateBranch = $stmt->execute([$address, $head_manager, $contact_number, 
	$last_updated, $last_updated_by, $job_id]);

	if ($updateBranch) {

		$findInsertedItemSQL = "SELECT * FROM job_hunt WHERE job_id = ?";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute([$job_id]);
		$getBranchID = $stmtfindInsertedItemSQL->fetch(); 

		$insertAnActivityLog = insertAnActivityLog($pdo, "UPDATE", $getBranchID['job_id'], 
			$getBranchID['firstname'], $getBranchID['lastname'], 
			$getBranchID['specialization'], $_SESSION['username']);

		if ($insertAnActivityLog) {

			$response = array(
				"status" =>"200",
				"message"=>"Updated the job successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}

	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;

}


function deleteABranch($pdo, $branch_id) {
	$response = array();
	$sql = "SELECT * FROM job_hunt WHERE job_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$branch_id]);
	$getBranchByID = $stmt->fetch();

	$insertAnActivityLog = insertAnActivityLog($pdo, "DELETE", $getBranchByID['job_id'],
		$getBranchByID['firstname'], $getBranchByID['lastname'],
		$getBranchByID['specialization'], $_SESSION['username']);

	if ($insertAnActivityLog) {
		$deleteSql = "DELETE FROM job_hunt WHERE job_id = ?";
		$deleteStmt = $pdo->prepare($deleteSql);
		$deleteQuery = $deleteStmt->execute([$branch_id]);

		if ($deleteQuery) {
			$response = array(
				"status" =>"200",
				"message"=>"Deleted the branch successfully!"
			);
		}
		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
	}
	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;
}



?>