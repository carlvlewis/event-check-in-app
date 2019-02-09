<html>
<head> 
    <link rel="stylesheet" href="admin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="admin/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="admin/plugins/iCheck/square/blue.css">
</head>
  <body class="hold-transition login-page">
    <div class="login-box">
	      <div class="login-logo">
        <a href="#">Visitor Signin System</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
 
<?php
require_once ('core/sanitize.inc.php');
//Get Data
$action = (isset($_GET['action']) ? $_GET['action'] : ""); 
//Sanitize Data
$action = sanitize_sql_string($action);

if($action == '') {
?>
 <p class="login-box-msg">Please Click Install Below to Begin Install</p>
 <a href="install.php?action=step1" class="btn btn-primary btn-block btn-flat">Start Install</a>
<?php } else if($action == 'step1') { ?>
 <p class="login-box-msg">Enter your Database Details Below</p>
<form action="install.php?action=step2" name="login_submit" method="post" onSubmit="return Form1_Validator(this)" language="JavaScript">
<label>Hostname:</label> <br /><input type='text' name='db_hostname' class='form-control' value='localhost'>
<label>Database Name:</label> <br /><input type='text' name='db_name' class='form-control'>
<label>Database Username:</label> <br /><input type='text' name='db_username' class='form-control'>
<label>Database Password:</label> <br /><input type='text' name='db_password' class='form-control'>
<br />
<button type="submit" name="login_submit" class="btn btn-success btn-block btn-flat">Install Visitor Signin System</button>
</form>
<?php } else if($action == 'step2') { 
echo '<p class="login-box-msg">Installing...</p>';
//Grab and clean data
$db_hostname = stripslashes(htmlspecialchars($_POST['db_hostname'], ENT_QUOTES));
$db_name = stripslashes(htmlspecialchars($_POST['db_name'], ENT_QUOTES));
$db_username = stripslashes(htmlspecialchars($_POST['db_username'], ENT_QUOTES));
$db_password = stripslashes(htmlspecialchars($_POST['db_password'], ENT_QUOTES));

//Sanitize Data
$db_hostname = sanitize_sql_string($db_hostname);
$db_name = sanitize_sql_string($db_name);
$db_username = sanitize_sql_string($db_username);
$db_password = sanitize_sql_string($db_password);


		$db_connection = 1;
		$connection = mysqli_connect("$db_hostname","$db_username","$db_password","$db_name") or $db_connection = 2;
		if($db_connection == 2){
			echo  '<center><font color="red"><b>Connection Failed, please retry</b></font>';
			echo '<br /><br /><a href="install.php" class="btn btn-danger">Back and Try Again...</a></center>';
			exit();
		} else {


$file_towirte = 'core/config.php';
$info_towrite = <<<PHP
<?php
//Database Connection Info
\$con_signin = mysqli_connect('$db_hostname','$db_username','$db_password','$db_name');
\$table = "signin_tablet";
PHP;

$sql_install_db = "CREATE TABLE IF NOT EXISTS `logs` (
`id` int(11) NOT NULL,
  `alert` varchar(300) NOT NULL,
  `user` varchar(40) NOT NULL,
  `date` varchar(40) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";


$sql_install_db2 = "CREATE TABLE IF NOT EXISTS `settings` (
`id` int(11) NOT NULL,
  `website_url` varchar(120) NOT NULL,
  `website_title` varchar(250) NOT NULL,
  `company_title` varchar(900) NOT NULL,
  `company_logo` varchar(2000) NOT NULL,
  `company_agreement` longtext NOT NULL,
  `set_emailbody` longtext NOT NULL,
  `contact_email` varchar(120) NOT NULL,
  `vss_mang_version` varchar(11) NOT NULL,
  `salt_key` varchar(200) NOT NULL,
  `vss_timeout` varchar(12) NOT NULL,
  `take_image` int(1) NOT NULL,
  `show_veh_reg` int(1) NOT NULL,
  `show_signature` int(1) NOT NULL,  
  `set_visitoremail` int(1) NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;";


$sql_install_db3 = "CREATE TABLE IF NOT EXISTS `signin_tablet` (
`id` int(11) NOT NULL,
  `signin_date` varchar(30) NOT NULL,
  `signout_date` varchar(30) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `full_name` varchar(80) NOT NULL,
  `company` varchar(80) NOT NULL,
  `veh_reg` varchar(200) NOT NULL,
  `contact_list` varchar(200) NOT NULL,  
  `signature_in` longtext NOT NULL,
  `signature_out` longtext NOT NULL,
  `profile_in` longtext NOT NULL,
  `profile_out` longtext NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21250;";


$sql_install_db4 = "CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(35) NOT NULL,
  `password` varchar(200) NOT NULL,
  `ip` varchar(90) NOT NULL,
  `date` varchar(90) NOT NULL,
  `is_active` int(1) NOT NULL,
  `rank` int(1) NOT NULL,
  `img_profile` varchar(200) NOT NULL DEFAULT 'uploads_md5/avatar_profile.png',
  `img_background` varchar(200) NOT NULL,
  `profile_description` longtext NOT NULL,
  `last_update` varchar(200) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=204;";

$sql_install_db7 = "CREATE TABLE IF NOT EXISTS `visitor_contacts` (
  `id` int(11) NOT NULL,
  `con_name` varchar(200) NOT NULL,
  `con_email` varchar(200) NOT NULL,
  `created_by` varchar(60) NOT NULL,
  `created_on` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sql_install_db5 = "INSERT INTO `settings` (`id`, `website_url`, `website_title`, `company_title`, `company_logo`, `company_agreement`, `set_emailbody`, `contact_email`, `vss_mang_version`, `salt_key`, `vss_timeout`, `take_image`, `show_veh_reg`, `show_signature`, `set_visitoremail`, `active`) VALUES
(1, 'http://visitorsignin.ignitepros.com', 'Visitor Signin System', 'Signin System', '', 'Welcome to the Signin system for your company name. During your visit, Please contact any employee for assistance.',  'Your guest {GuestName} from {CompanyName} is waiting for you in reception. Powered by IgnitePros.com Signin System.', 'support@ignitepros.com', '1.0.3', 'salt_keyvisitorsignin_sys0x34985498', '86400', 0, 0, 1, 0, 0);
";


$sql_install_db6 = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `ip`, `date`, `is_active`, `rank`, `img_profile`, `img_background`, `profile_description`, `last_update`) VALUES
(199, 'admin', '', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '12/13/2015 03:07 AM', 1, 1, 'uploads_md5/avatar_profile.png', '', '', '');";



$sql_install_db8 = "ALTER TABLE `logs` ADD PRIMARY KEY (`id`);";
$sql_install_db9 = "ALTER TABLE `settings` ADD PRIMARY KEY (`id`);";
$sql_install_db10 = "ALTER TABLE `signin_tablet` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id_3` (`id`), ADD KEY `id` (`id`), ADD KEY `id_2` (`id`);";
$sql_install_db11 = "ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`email`);";
$sql_install_db12 = "ALTER TABLE `logs`MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
$sql_install_db13 = "ALTER TABLE `settings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;";
$sql_install_db14 = "ALTER TABLE `signin_tablet` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21250;";
$sql_install_db15 = "ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=300;";
$sql_install_db16 = "ALTER TABLE `visitor_contacts` ADD PRIMARY KEY (`id`);";
$sql_install_db17 = "ALTER TABLE `visitor_contacts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
		
	
//Insert new Tables into DB	
mysqli_query($connection, $sql_install_db);	
mysqli_query($connection, $sql_install_db2);	
mysqli_query($connection, $sql_install_db3);	
mysqli_query($connection, $sql_install_db4);	
mysqli_query($connection, $sql_install_db5);	
mysqli_query($connection, $sql_install_db6);
mysqli_query($connection, $sql_install_db7);		
//Alter Tables
mysqli_query($connection, $sql_install_db8);
mysqli_query($connection, $sql_install_db9);
mysqli_query($connection, $sql_install_db10);
mysqli_query($connection, $sql_install_db11);
mysqli_query($connection, $sql_install_db12);
mysqli_query($connection, $sql_install_db13);
mysqli_query($connection, $sql_install_db14);
mysqli_query($connection, $sql_install_db15);
mysqli_query($connection, $sql_install_db16);
mysqli_query($connection, $sql_install_db17);	
	

//Write to Config File
$old_content = file_get_contents($file_towirte);
		$command = fopen($file_towirte, 'w');
		$writefile_check = fwrite($command, $info_towrite."\n\n\n".$old_content);
		if($writefile_check) {
			echo '<center><font color="red"><b>Write to config file Successfully</font></center>';
		} else {
			echo '<center><font color="red"><b>Failed to write to config file Please edit file core/config.php and add at the very top info below.</font></center>';
			echo "<br /><br /><textarea cols='45' rows='6' class='form-control'>$info_towrite</textarea><br /><br />";
		}
		
		fclose($command);	

echo '<center><img src="admin/dist/img/loading.gif" /></center><br /><font color=\'green\'>The mysql database configuration successfully Imported!</font>
		<br />Please delete this file now install.php, for security reasons!<br />
		<br />
		<br />Login with admin right with the next account<br />
		<b>Username:</b> admin<br />
		<b>Password:</b> admin<br /><br />
		<center><strong>Install Complete. You may now login.</strong></center>
		<br />
		 <a href="admin/index.php" class="btn btn-success btn-block btn-flat">Login</a>';		
		}
}
?>
 <center><br>Powered by <a href="https://www.ignitepros.com" target="_blank">Ignitepros.com</a></center>
</div>
</div> 
</body>
</html> 