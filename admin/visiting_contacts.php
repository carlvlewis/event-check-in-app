<?php 
$PageTitle = 'Visiting Contacts';
require_once ('top.php'); 
?>
<script type='text/javascript' language='javascript'>	
	function view_visitors(hashid){
		windowObjectReference = window.open(
    "view_visitor_visitors.php?cid="+ hashid +"&action=allow_usr&decode=6767676gh5662684de61a08888",
    "DescriptiveWindowName",
    "width=900,height=720,resizable,scrollbars,status"
  );
	}
</script>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Employee Contacts 
			<div class="btn-group pull-right">
<a href='new_con_account.php' class='btn btn-success'><i class="fa fa-plus"></i> Create New Contact</a> 
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class="fa fa-upload"></i> Import Contact (CSV)</button> 
<button type="submit" form="myform" name="export_csv_contacts" class="btn btn-primary"><i class="fa fa-download"></i> Export Contacts (CSV)</button>
</div></h1>			  
        </section>
        <section class="content">
          <div class="row">
		  <br /><br />		  
            <div class="col-xs-12">
<?php
//Import to CSV
if (isset($_POST['import_csv'])){
	
//Upload File
if($_FILES['csv_import']['name']){
   $arrFileName = explode('.',$_FILES['csv_import']['name']);
   if($arrFileName[1] == 'csv'){
   $handle = fopen($_FILES['csv_import']['tmp_name'], "r");
   while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	   
//Generate Account ID
//Grab Last ID in table and then +1
	$last_id = mysqli_query($con_signin,"SELECT MAX(id) FROM visitor_contacts");
	$row = mysqli_fetch_row($last_id);	   
	$get_lastID = $row[0];
	if($get_lastID == '') { $get_lastID = '1'; }
	$add_lastid = $get_lastID + 1;
	$account_id_gen = sanitize_int($add_lastid);	   
	   	
   $item0 = mysqli_real_escape_string($con_signin,$data[0]);
   $item1 = mysqli_real_escape_string($con_signin,$data[1]);
  
   $import="INSERT into visitor_contacts values('$account_id_gen','$item0','$item1','$session_login_usr','$fptime')";
   mysqli_query($con_signin,$import);

   
  }
   fclose($handle);
   echo '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    CSV File Import Complete
                  </div>';
  }	else { 
  echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    Only CSV Files Allowed (.csv)
                  </div>';
  }			   
}
}
?>			
              <div class="box box-primary">			  
                <div class="box-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
						<th>Controls</th>
						<th>Full Name</th>
                        <th>Email</th>
						<th>How Many Visitor's</th>
						<th>Created by</th>
						<th>Created on</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
//Get user list and display in table
$list_users_result = mysqli_query($con_signin,"SELECT * FROM visitor_contacts ORDER BY `con_name` ASC");
while($row = mysqli_fetch_array($list_users_result)){

$id = $row['id'];
$con_name = $row['con_name'];
$con_email = $row['con_email'];
$created_by = $row['created_by'];
$created_on = $row['created_on'];

//Grab Contact Name		
$result_con_count = mysqli_query($con_signin,"SELECT * FROM signin_tablet WHERE contact_list = '$id'");
$row_cnt_visitors = mysqli_num_rows($result_con_count);

					
				echo "<tr>
						<td>
						<a href='edit_con_account.php?cid=$id' role='button'  title='Edit Contact' class='btn btn-warning btn-sm'><i class='fa fa-edit'></i></a> 
						<a href='#' onClick='view_visitors(".$id.")' role='button' title='View who visited Contact' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>
						<a href='delete_con_account.php?cid=$id' role='button' title='Delete Contact' class='btn btn-danger btn-sm'><i class='fa fa-trash-o'></i></a>
						</td>
						<td>$con_name</td>
                        <td><a href='mailto:$con_email'>$con_email</a></td>
						<td>$row_cnt_visitors</td>
						<td>$created_by</td>
						<td>$created_on</td>
                      </tr>";
}
?>					  
                  </table>
                </div>
              </div>
            </div>
          </div>
		  <form id="myform" action="export_vss_contacts.php?usr=<?php echo $session_login_usr; ?>&authsession=<?php echo $salt_key; ?>" method="post">
		  </form>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import/Export Accounts</h4>
      </div>
      <div class="modal-body">
				 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				 Example: <a href="upload_MD5IMG/import_sample.csv" target="_blank">Import CSV Template</a><br /><br />
				 <label for="exampleInputFile">Upload CSV File into Database</label>
				 <input type="file" class="btn btn-primary" name="csv_import"><br />
					
      <div class="modal-footer">
	  <button type="submit" name="import_csv" class="btn btn-success"><i class="fa fa-database"></i> Import Contacts (CSV)</button>
				</form>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
      </div>
    </div>

  </div>
</div>
</section>
</div>
<?php include_once ('bottom.php'); ?>