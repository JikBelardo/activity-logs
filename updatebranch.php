<?php  
require_once 'E:\XAMPP\htdocs\actlogs\core\models.php'; 
require_once 'E:\XAMPP\htdocs\actlogs\core\handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php include 'navbar.php'; ?>

	<?php $getBranchByID = getBranchByID($pdo, $_GET['job_id']); ?>
	<form action="core/handleForms.php?job_id=<?php echo $_GET['job_id']; ?>" method="POST">
		<p>
			<label for="firstname">First Name</label>
			<input type="text" name="firstname" value="<?php echo $getBranchByID['firstname']; ?>"></p>
		<p>
			<label for="lastname">last Name</label>
			<input type="text" name="lastname" value="<?php echo $getBranchByID['lastname']; ?>">
		</p>
		<p>
			<label for="specialization">Specialization</label>
			<input type="text" name="specialization" value="<?php echo $getBranchByID['specialization']; ?>">
			<input type="submit" name="updateBranchBtn" value="Update">
		</p>
	</form>
</body>
</html>