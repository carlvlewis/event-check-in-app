<?php 
$PageTitle = 'Dashboard';
require_once ('top.php'); ?>
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
            Dashboard
          </h1>
        </section>
        <section class="content">
          <div class="row">
            <div class="col-lg-4 col-xs-4">
              <div class="small-box bg-blue-gradient">
                <div class="inner">
                  <h3><?php echo $visitors_count; ?></h3>
                  <p>Total Visitors</p>
                </div>
                <div class="icon">
                  <i class="fa fa-group"></i>
                </div>
                <a href="visitors.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
			<div class="col-lg-4 col-xs-4">
              <div class="small-box bg-green-gradient">
                <div class="inner">
                  <h3><?php echo $signin_count; ?></h3>
                  <p>Signin Visitors that Signed out</p>
                </div>
                <div class="icon">
                  <i class="fa fa-group"></i>
                </div>
                <a href="visitors.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-4 col-xs-4">
              <div class="small-box bg-red-gradient">
                <div class="inner">
                  <h3><?php echo $signout_count; ?></h3>
                  <p>Visitors that have not signed out</p>
                </div>
                <div class="icon">
                  <i class="fa fa-group"></i>
                </div>
                <a href="visitors.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
			</div>
			   <div class="row">
            <div class="col-md-6">
			  <div class="box box-success with-border">
                <div class="box-header with-border bg-green-gradient">
                  <h3 class="box-title">Last Visitors Signin</h3>
                </div>
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
				  <?php
//Get Recent signin visitors				  
$list_visitors_result = mysqli_query($con_signin,"SELECT * FROM signin_tablet ORDER BY `signin_tablet`.`id` DESC LIMIT 8");
$count_visitors = mysqli_num_rows($list_visitors_result);

if($count_visitors != 0) {
while($row = mysqli_fetch_array($list_visitors_result)){
$id = $row['id'];
$signin_date = $row['signin_date'];
$signout_date = $row['signout_date'];
$full_name = $row['full_name'];
$profile_in = $row['profile_in'];

//Show image if none is in db  
if($profile_in == '') {
$profile_in = $visitor_profile_none;	
} 
?>	
                    <li>
                     <a href='#' onClick='viewsignin("<?php echo $id; ?>")'><img src="data:image/gif;base64,<?php echo $profile_in; ?>" width="80px" alt="User Image"></a><br />
					 <a href='#' onClick='signbage("<?php echo $id; ?>")' title="View Visitor" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
					 <a href='#' onClick='viewsignin("<?php echo $id; ?>")' title="Edit Lunch" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></a>
                     <a class="users-list-name" href='#' onClick='viewsignin("<?php echo $id; ?>")'><?php echo $full_name; ?></a>
                      <span class="users-list-date"><?php echo $signin_date; ?></span>
                    </li>                 
<?php } } else { ?>		
<div class="box-body"><ul class="products-list product-list-in-box"><li class="item"><strong>No Recent Visitors.</strong></li></ul></div>
<?php } ?></ul>
                </div>
                <div class="box-footer text-center">
                  <a href="visitors.php" class="uppercase">View All Visitors</a>
                </div>
              </div>
			  </div>
			   <div class="col-md-6">
              <div class="box box-danger">
                <div class="box-header with-border bg-red-gradient">
                  <h3 class="box-title">Visitors have not Signed Out</h3>
               </div>
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
				  <?php
//Get visitors that have not signed out yet				  
$list_visitors_result = mysqli_query($con_signin,"SELECT * FROM signin_tablet WHERE signout_date = '' ORDER BY `signin_tablet`.`id` DESC LIMIT 8");
$count_visitors = mysqli_num_rows($list_visitors_result);

if($count_visitors != 0) {
while($row = mysqli_fetch_array($list_visitors_result)){
$id = $row['id'];
$signin_date = $row['signin_date'];
$signout_date = $row['signout_date'];
$full_name = $row['full_name'];
$profile_in = $row['profile_in'];

//Show image if none is in db  
if($profile_in == '') {
$profile_in = $visitor_profile_none;
}
?>	
                    <li>
                     <a href='#' onClick='viewsignin("<?php echo $id; ?>")'><img src="data:image/gif;base64,<?php echo $profile_in; ?>" width="80px" alt="User Image"></a><br />
					 <a href='#' onClick='signbage("<?php echo $id; ?>")' title="View Visitor" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
					 <a href='#' onClick='viewsignin("<?php echo $id; ?>")' title="Edit Lunch" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></a>
                      <a class="users-list-name" href='#' onClick='viewsignin("<?php echo $id; ?>")'><?php echo $full_name; ?></a>
                      <span class="users-list-date"><?php echo $signin_date; ?></span>
                    </li>                 
<?php } } else { ?>		
<div class="box-body"><ul class="products-list product-list-in-box"><li class="item"><strong>No Recent Visitors.</strong></li></ul></div>
<?php } ?></ul>
                </div>
                <div class="box-footer text-center">
                  <a href="visitors.php" class="uppercase">View All Visitors</a>
                </div>
              </div>
            </div>
          </div>
			</div>			
        </section>
      </div>	  
<?php include_once ('bottom.php'); ?>