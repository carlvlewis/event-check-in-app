<?php 
$PageTitle = 'Visitors';
require_once ('top.php');
?>
<script type='text/javascript' language='javascript'>	
	function signbage(hashid){
		windowObjectReference = window.open(
    "print_visitor_badge.php?cid="+ hashid +"&action=allow_usr&decode=6767676gh5662684de61a08888",
    "DescriptiveWindowName",
    "width=350,height=300,resizable,scrollbars,status"
  );
	}

	function viewsignin(hashid){
		windowObjectReference = window.open(
    "view_visitor_badge.php?cid="+ hashid +"&action=allow_usr&decode=6767676gh5662684de61a08888",
    "DescriptiveWindowName",
    "width=650,height=660,resizable,scrollbars,status"
  );
	}	
</script>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
		  <div class="row">
            <div class="col-xs-12">Visitors List
			<form action="signout_checkedVisitors.php?usr=<?php echo $session_login_usr; ?>&authsession=<?php echo $salt_key; ?>" method="post" id="form_signout">
				<button id="getChkBoxValues" name="visitorssignout" type="submit" class="btn btn-warning btn-sm pull-right"><i class="fa fa-sign-out"></i> Sign out Checked Visitors</button>
			  </form>
			</div></div>
          </form>
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
						<th>Controls</th>
						<th>Visitor ID</th>
                        <th>Signin Date</th>
                        <th>Signout Date</th>
                        <th>Full Name</th>
						<th>Company</th>
						<th>Visiting</th>
						<?php if ($show_veh_reg == 1) { ?>
						<th>Vehicle Registration</th>
						<?php } ?>
						<th>Image</th>
                      </tr>
                    </thead>
                    <tbody>
<?php	
//Grab Search
if(!empty($grab_Search_Visitors)) {
$list_visitors_result = mysqli_query($con_signin,"SELECT * FROM signin_tablet WHERE id LIKE '%$grab_Search_Visitors%' or full_name LIKE '%$grab_Search_Visitors%' or company LIKE '%$grab_Search_Visitors%'");		
} else {
$list_visitors_result = mysqli_query($con_signin,"SELECT * FROM signin_tablet ORDER BY id DESC");
}

while($row = mysqli_fetch_array($list_visitors_result)){

$id = $row['id'];
$signin_date = $row['signin_date'];
$signout_date = $row['signout_date'];
$full_name = $row['full_name'];
$company = $row['company'];
$veh_reg = $row['veh_reg'];
$contact_list = $row['contact_list'];
$signature_in = $row['signature_in'];
$signature_out = $row['signature_out'];
$profile_in = $row['profile_in'];
$profile_out = $row['profile_out'];
$ip = $row['ip'];

//Grab Contact Name		
$result5 = mysqli_query($con_signin,"SELECT * FROM visitor_contacts WHERE id = '$contact_list'");
$row_cnt = mysqli_num_rows($result5);
if($row_cnt <= 0) {

$contact_list_show = 'None';

} else { 
	
while($row = mysqli_fetch_array($result5))
  {
  $con_name = $row['con_name'];
  $con_email = $row['con_email'];
  $contact_list_show = $con_name;
  } 
}



//Show image if none is in db  
if($profile_in == '') {
$profile_in = $visitor_profile_none;	
} 

$force_signinout = '';

if($signout_date == '') {
  $signout_date = '<input type="checkbox" id="isVisitorSelected" name="isVisitorSelected[]" value='.$id.' class="chkbox" /> <a href="force_visitor_signout.php?cid='.$id.'" role="button" title="Force Sign Out" class="btn btn-warning btn-xs"><i class="fa fa-sign-out"></i> Sign out </a>'; 
  $force_signinout = "<a href='force_visitor_signout.php?cid=$id' role='button' title='Force Sign Out' class='btn btn-warning btn-sm'><i class='fa fa-sign-out'></i></a>"; 
  }

if($show_veh_reg == 1) {
	$showor_veh_reg = "<td>$veh_reg</td>";
} else {
	$showor_veh_reg = "";
}
					
				echo "<tr>
						<td>
						  <a href='#' onClick='signbage(".$id.")' role='button' title='Print Badge' class='btn btn-primary btn-sm'><i class='fa fa-print'></i></a>
						  <a href='#' onClick='viewsignin(".$id.")' role='button' title='View Visitor Signin' class='btn btn-info btn-sm'><i class='fa fa-eye'></i></a>	
						  <a href='delete_visitor.php?cid=$id' role='button' title='Delete Visitor' class='btn btn-danger btn-sm'><i class='fa fa-trash-o'></i></a>	
						  $force_signinout
                        </td>
						<td>$id</td>
                        <td>$signin_date</td>
                        <td>$signout_date</td>                       
						<td>$full_name</td>
						<td>$company</td>
						<td>$contact_list_show</td>
						$veh_reg
						<td><a href='#' onClick='viewsignin(".$id.")'><img src='data:image/gif;base64,$profile_in' width='80px' style='border-radius: 6px;' alt='Visitor Image'></a></td>
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
<script>
$(document).ready(function(e) {
    $('#getChkBoxValues').click(function(){
        var chkBoxArray = [];
	$('.chkbox:checked').each(function() {
            chkBoxArray.push($(this).val());
        });
	if(chkBoxArray == 0){
		alert('Please select at least one checkbox to sign out Visitor.');
		return false;
	} else {
		if(!confirm("Do you really want to do this? This can't be undone.")){
		return false;
		}
	}	
			$('<input />').attr('type', 'hidden')
          .attr('name', "visitor_id")
          .attr('value', '' + chkBoxArray)
          .appendTo('#form_signout');
	});
});
</script>	  
<?php include_once ('bottom.php'); ?>