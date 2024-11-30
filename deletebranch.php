<?php 
require_once 'core/models.php'; 
require_once 'core/dbConfig.php';
 
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
	<style>
		body {
			font-family: "Arial";
		}
		input {
			font-size: 1.5em;
			height: 50px;
			width: 200px;
		}
		table, th, td {
			border:1px solid black;
		}
	</style>
</head>
<body>
	<h1>Are you sure you want to delete this job?</h1>
	<?php $getBranchByID = getBranchByID($pdo, $_GET['job_id']); ?>
	<div class="container" style="border-style: solid; border-color: red; background-color: #ffcbd1;height: 500px;">
		<h2>first name: <?php echo $getBranchByID['firstname']; ?></h2>
		<h2>last name: <?php echo $getBranchByID['lastname']; ?></h2>
		<h2>specialization: <?php echo $getBranchByID['specialization']; ?></h2>

		<div class="deleteBtn" style="float: right; margin-right: 10px;">
			<form action="core/handleForms.php?job_id=<?php echo $_GET['job_id']; ?>" method="POST">
				<input type="submit" name="deleteBranchBtn" value="Delete" style="background-color: #f69697; border-style: solid;">
			</form>			
		</div>	

	</div>
</body>
</html>