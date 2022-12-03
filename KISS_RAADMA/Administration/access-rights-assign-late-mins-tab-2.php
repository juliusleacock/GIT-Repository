<?php

	/*access-rights-assign-late-mins-tab-1.php   */

	
	$db = new Database();
	$db_name = RAADMA_DB;
	$conn = $db->connect_to_database($db_name);
	$query_string = "SELECT id, first_name + ' ' + last_name as FULLNAME , email_address, username FROM ".RAADMA_LIVE_USER_ACCOUNTS_TBL." WHERE username != 'MIS'";
	$exec = $db->executeQuery($query_string, $conn);



?>
<br>

<table class="table" id="accounts_list_table">
	<thead>
		<tr>
		<th>Name</th>
		<th>Email Address</th>
		<th>Username</th>
		<th>Access Right For Late Mins</th>
		<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php while($rows =  mssql_fetch_assoc($exec)){ ?>
		<tr>
		<td><?php echo $rows['FULLNAME']; ?></td>
		<td><?php echo $rows['email_address']; ?></td>
		<td><?php echo $rows['username']; ?></td>
		<td><?php echo GetAccessRightForModule($rows['id'], 'HR_LATE_MINS_WEEKLY_PAID_EMPLOYEES');?></td>
		<td><button class="btn btn-danger change_access_right_late_mins" id="<?php echo $rows['id']; ?>" data-toggle="modal" data-target="#change_access_right_late_mins"><span class="glyphicon glyphicon-pencil"></span> Change Access Right</button></td>
		</tr>
		<?php  } ?>
	</tbody>
</table>



