<?php 
$PageTitle = 'Users List';
require_once ('top.php'); 
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Users
          </h1>
        </section>
        <section class="content">
          <div class="row">
<?php
//Check Admin Rank
if($rank != 1) {
	echo "<div class='col-xs-12'>Error: You do not have permissions.</div></div></section></div>";
	include_once ('bottom.php');
	exit();
}
?>		  
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
						<th>Controls</th>
						<th>Firstname</th>
                        <th>Lastname</th>
                        <th>Email</th>
                        <th>Last Login IP</th>
                        <th>Last Login Date</th>
                        <th>Status</th>
						<th>Rank</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
//Get user list and display in table
$list_users_result = mysqli_query($con_signin,"SELECT * FROM users ORDER BY `rank` ASC");
while($row = mysqli_fetch_array($list_users_result)){

$id = $row['id'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$email = $row['email'];
$ip = $row['ip'];
$date = $row['date'];
$is_active = $row['is_active'];
$rank = $row['rank'];


if($rank == '1') {
$rank = '<span class="label label-success">Admin</span>'; 
} else if($rank == '3'){
$rank = '<span class="label label-primary">User</span>';
} else {
$rank = '<span class="label label-warning">Unknown</span>';	
}

if($is_active == '1') {
$is_active = '<span class="label label-success">Active</span>'; 
} else if($is_active == '0'){
$is_active = '<span class="label label-primary">Not Active</span>';
} else {
$rank = '<span class="label label-warning">Unknown</span>';	
}
					
				echo "<tr>
						<td><a href='edit_usr_account.php?cid=$id' role='button' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a> <a href='delete_usr_account.php?cid=$id' role='button' class='btn btn-danger btn-sm'><i class='fa fa-trash-o'></i></a></td>
						<td>$firstname</td>
                        <td>$lastname</td>
                        <td><a href='mailto:$email'>$email</a></td>
						<td>$ip</td>
						<td>$date</td>
						<td>$is_active</td>
						<td>$rank</td>
                      </tr>";
}
?>					  
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>