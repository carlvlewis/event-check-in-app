<?php 
//CopyRight 2016 Ignitepros.com
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();
session_start();

//Check to see if their is a session open with vars below
if(!(isset($_SESSION['login_usr']) && $_SESSION['login_usr'] != '')) {
header("Location: index.php?action=error_session");
exit();
}

//Get session user
$session_login_usr = $_SESSION['login_usr'];
require_once ('../core/config.php');
require_once ('../core/sanitize.inc.php');

//Check for session timeout
if(!isset( $_SESSION['login_usr'] ) || time() - $_SESSION['login_time'] > $lm_timeout) {
header("Location: index.php?action=error_session");
exit();	
}

//Sanitize Login User Data
$session_login_usr = sanitize_sql_string($session_login_usr);
 
//Checking the user login session
$sel_loginuser = "SELECT * FROM users where email = '$session_login_usr'";
$run_lguser = mysqli_query($con_signin, $sel_loginuser);
$check_lguser = mysqli_num_rows($run_lguser);

//check if user is in db
if($check_lguser != '1') {
session_destroy();
echo "<script>window.open('index.php?action=session_error','_self')</script>";
exit();
}

//Grab logged in User info
$result_login = mysqli_query($con_signin,"SELECT * FROM users WHERE email = '$session_login_usr'");
while($row = mysqli_fetch_array($result_login))
  {
  $usr_id = $row['id'];
  $first = $row['firstname'];
  $last = $row['lastname'];
  $email = $row['email'];
  $rank = $row['rank'];
  $is_active = $row['is_active'];
  $last_date = $row['date'];  
  $last_ip = $row['ip']; 
  }
  
//Count Signin 
$sql_signin_count = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE signin_date != '' and signout_date != ''");
$signin_count = mysqli_num_rows($sql_signin_count);

//Count Signout
$sql_signout_count = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE signin_date != '' and signout_date = ''");
$signout_count = mysqli_num_rows($sql_signout_count);

//Visitor Count
$sql_visitor_count = mysqli_query($con_signin, "SELECT * FROM signin_tablet");
$visitors_count = mysqli_num_rows($sql_visitor_count);

//Count Alert Logs 
$sql_log_count = mysqli_query($con_signin, "SELECT * FROM logs WHERE user = '$session_login_usr' and status = '0'");
$log_count = mysqli_num_rows($sql_log_count);

//Search Accounts
$grab_Search_Visitors = isset($_POST['search_visitors']) ? $_POST['search_visitors'] : "";  
$grab_Search_Visitors = stripslashes(htmlspecialchars($grab_Search_Visitors, ENT_QUOTES));
//Sanitize Data
$grab_Search_Visitors = sanitize_sql_string($grab_Search_Visitors);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $SiteTitle; ?> | <?php echo $PageTitle; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">	
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/select2.min.css">	
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<link rel="stylesheet" href="dist/css/buttons.dataTables.min1.css">
	<!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script>
function goBack() {
    window.history.back();
}
</script>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <a href="#" class="logo">
          <span class="logo-mini"></span>
          <span class="logo-lg"><?php echo $SiteTitle; ?></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
			<?php
			//Check for Demo Mode
			if($demo_mode == 1) {
			echo '<div class="label label-warning">Demo Mode some functions are Disabled.</div>';
			} ?>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning"><?php echo $log_count; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php echo $log_count; ?> recent activity</li>
                  <li>
                    <ul class="menu">
<?php
//Get logs for user and display
$list_logs_result = mysqli_query($con_signin,"SELECT * FROM logs WHERE user = '$session_login_usr' and status = '0' ORDER BY `id` DESC LIMIT 10");
$count_logs = mysqli_num_rows($list_logs_result);

if($count_logs != 0) {
while($row = mysqli_fetch_array($list_logs_result)){

$id = $row['id'];
$alert = $row['alert'];
$date = $row['date'];
$ip = $row['ip'];
?>					
                      <li>
                        <a href="my_profile.php">
                          <i class="fa fa-warning text-yellow"></i> <?php echo $alert; ?>
                        </a>
                      </li> 
<?php } } else { ?>			
<li>No Updates/Alerts</li>
<?php } ?>		  
                    </ul>
                  </li>
                  <li class="footer"><a href="my_profile.php">View all</a></li>
                </ul>
              </li>
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="dist/img/avatar_profile.png" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $first ." ". $last; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header">
                    <img src="dist/img/avatar_profile.png" class="img-circle" alt="User Image">
                    <p>
                      <?php echo $first ." ". $last; ?>
                      <small>Date: <?php echo $last_date ."<br />IP:". $last_ip; ?></small>
                    </p>
                  </li>
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="my_profile.php" class="btn btn-default btn-flat">Profile</a>
					  <a href="change_password.php" class="btn btn-default btn-flat">Password</a>
					  <a href="logout.php" class="btn btn-default btn-flat">Logout</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <aside class="main-sidebar">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/avatar_profile.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><a href="my_profile.php"><?php echo $first ." ". $last; ?></a></p>
			  Welcome Back!
            </div>
          </div>
          <form action="visitors.php" method="post" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="search_visitors" class="form-control" value="<?php echo $grab_Search_Visitors; ?>" placeholder="Search visitors">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <ul class="sidebar-menu">
            <li class="<?php echo ($PageTitle=='Dashboard') ? 'active' : ''; ?> treeview">
              <a href="dashboard.php">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <li class="<?php foreach($check_accounts_page as $value){
  if($value == $PageTitle){
    echo 'active';
				} } ?> treeview">
              <a href="#">
                <i class="fa fa-sitemap"></i>
                <span>Visitors</span>
                &nbsp;<span class="label label-primary"><?php echo $visitors_count; ?></span> <span class="label label-danger"><?php echo $signout_count; ?></span>
				<i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			    <li <?php echo ($PageTitle=='Visitors') ? ' class="active"' : ''; ?>><a href="visitors.php"><i class="fa fa-users"></i> Visitors List</a></li>
				<li <?php echo ($PageTitle=='Create Visitor') ? ' class="active"' : ''; ?>><a href="../" target="_blank"><i class="fa fa-user-plus"></i> Signin New Visitor</a></li>
				<li <?php echo ($PageTitle=='Visiting Contacts') ? ' class="active"' : ''; ?>><a href="visiting_contacts.php"><i class="fa fa-university"></i> Employees Contacts</a></li>	
				<li <?php echo ($PageTitle=='Visitor Report') ? ' class="active"' : ''; ?>><a href="admin_report.php"><i class="fa fa-area-chart"></i> Visitors Report</a></li>
              </ul>
            </li>		
									
			<?php if($rank == 1) { ?>
			<li class="<?php foreach($check_admin_page as $value){
			if($value == $PageTitle){
			echo 'active';
				} } ?> treeview">
              <a href="#">
                <i class="fa fa-gears"></i>
                <span>Admin Settings</span>
				<i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
				<li <?php echo ($PageTitle=='Users List') ? ' class="active"' : ''; ?>><a href="users_list.php"><i class="fa fa-users"></i> Users List</a></li>
                <li <?php echo ($PageTitle=='New User') ? ' class="active"' : ''; ?>><a href="new_usr_account.php"><i class="fa fa-user-plus"></i> Create User</a></li>
                <li <?php echo ($PageTitle=='Panel Logs') ? ' class="active"' : ''; ?>><a href="panel_logs.php"><i class="fa fa-book"></i> Logs</a></li>
			    <li <?php echo ($PageTitle=='Panel Settings') ? ' class="active"' : ''; ?>><a href="panel_settings.php"><i class="fa fa-gear"></i> Site Settings</a></li>
			  </ul>
            </li>
			<?php } ?>
			<li class="treeview">	
				<a href="export_vss_in.php?usr=<?php echo $session_login_usr; ?>&authsession=<?php echo $salt_key; ?>&export_type=csv" class="alert-danger">
                <i class="fa fa-support"></i> <span> Emergency Export</span>
              </a>	
            </li>
			
			<li class="<?php echo ($PageTitle=='My Profile') ? 'active' : ''; ?> treeview">
              <a href="my_profile.php">
                <i class="fa fa-user"></i> <span>My Profile</span>
              </a>
            </li>
			
             <li class="<?php echo ($PageTitle=='Logout') ? 'active' : ''; ?> treeview">
              <a href="logout.php">
                <i class="fa fa-power-off"></i> <span>Logout</span>
              </a>
            </li>
			<?php 
			//Check for Demo Mode
			if($demo_mode == 1) {
			?>
			<li class="treeview">
              <a href="http://visitorsignin.ignitepros.com/documentation" target="_blank">
                <i class="fa fa-book"></i> <span>Documentation</span>
              </a>
            </li>
			<?php } ?>
          </ul>
        </section>
      </aside>