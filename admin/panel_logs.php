<?php 
$PageTitle = 'Panel Logs';
require_once ('top.php'); 

//Check Admin Rank
if($rank != 1) {
	echo "<div class='col-xs-12'>Error: You do not have permissions.</div></div></section></div>";
	include_once ('bottom.php');
	exit();
}
?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Panel Logs
          </h1>
        </section>
        <section class="content">	
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-body">
				  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
					    <th>Event ID</th>
						<th>Alert</th>
						<th>By</th>
                        <th>Date</th>
						<th>IP</th>
                      </tr>
                    </thead>
                    <tbody>				
<?php
//Get all panel logs
$logs_results = mysqli_query($con_signin,"SELECT * FROM logs ORDER BY `logs`.`id` DESC");
while($row = mysqli_fetch_array($logs_results)){
	
$id = $row['id'];
$user_log = $row['user'];
$alert = $row['alert'];
$date = $row['date'];
$ip = $row['ip'];
	
	  echo "<tr>
			<td>$id</td>
			<td>$alert</td>
			<td>$user_log</td>
			<td>$date</td>
			<td>$ip</td>
			</tr>";
} 
?>
</table>
          </div></div>       
          </div>
          </div></section></div>
<?php include_once ('bottom.php'); ?>