<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

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

	<form action="core/handleForms.php" method="POST">
		<p>
			<label for="address">first name</label>
			<input type="text" name="firstname"></p>
		<p>
			<label for="address">last name</label>
			<input type="text" name="lastname">
		</p>
		<p>
			<label for="address">specialization</label>
			<input type="text" name="specialization">
			<input type="submit" name="insertNewBranchBtn" value="Create">
		</p>
	</form>

	<div class="tableClass">
		<table style="width: 100%;" cellpadding="20">
			<tr>
				<th>first name</th>
				<th>last name</th>
				<th>specialization</th>
				<th>Date Added</th>
				<th>Added By</th>
				<th>Last Updated</th>
				<th>Last Updated By</th>
				<th>Action</th>
			</tr>
			<?php if (!isset($_GET['searchBtn'])) { ?>
				<?php $getAllBranches = getAllBranches($pdo); ?>
				<?php foreach ($getAllBranches as $row) { ?>
				<tr>
					<td><?php echo $row['firstname']; ?></td>
					<td><?php echo $row['lastname']; ?></td>
					<td><?php echo $row['specialization']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="updatebranch.php?job_id=<?php echo $row['job_id']; ?>">Update</a>
					</td>
				</tr>
				<?php } ?>
			<?php } else { ?>
				<?php $getAllBranchesBySearch = getAllBranchesBySearch($pdo, $_GET['searchQuery']); ?>
				<?php foreach ($getAllBranchesBySearch as $row) { ?>
				<tr>
					<td><?php echo $row['firstname']; ?></td>
					<td><?php echo $row['lastname']; ?></td>
					<td><?php echo $row['specialization']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="updatebranch.php?job_id=<?php echo $row['job_id']; ?>">Update</a>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
		</table>
	</div>

</body>
</html>