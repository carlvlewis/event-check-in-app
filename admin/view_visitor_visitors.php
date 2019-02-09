<?php
//CopyRight 2015 Ignitepros.com
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();
session_start();

//Check Session Auth
if(!(isset($_SESSION['login_usr']) && $_SESSION['login_usr'] != '')) {
header("Location: index.php?action=error_session");
exit();
}

require_once ('../core/sanitize.inc.php');
//Get Data
$check_hash_id = isset($_GET['cid']) ? $_GET['cid'] : "";
$check_usr = isset($_GET['action']) ? $_GET['action'] : "";
$check_decode = isset($_GET['decode']) ? $_GET['decode'] : "";
//Sanitize Data
$check_hash_id = sanitize_sql_string($check_hash_id);
$check_usr = sanitize_sql_string($check_usr);
$check_decode = sanitize_sql_string($check_decode);

if($check_usr == '') {
echo "Error: No Access.";
exit();
}
if($check_decode == '') {
echo "Error: No Access.";
exit();
}
if($check_hash_id == '') {
echo "Error: Wrong ID.";
exit();
}
require_once ('../core/config.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $SiteTitle; ?></title>
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
	<link rel="stylesheet" href="dist/css/buttons.dataTables.min1.css">	
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
</head>
<body>
                                
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Contact Visitor #<?php echo $check_hash_id; ?> - History</h4>
                                        </div>
                                        <div class="modal-body">
										<hr>
										 <table id="example1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
					    <th>ID</th>
						<th>Full Name</th>
						<th>Company</th>
						<th>Signin Date</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php
					$result_dbview_more = mysqli_query($con_signin,"SELECT * FROM signin_tablet WHERE contact_list = '$check_hash_id'");
$row_cnt = mysqli_num_rows($result_dbview_more);
if($row_cnt <= 0) {
echo 
		"<tr>
						<td colspan='3'>No Visitor's Found</td>
					</td>";


} else { 					
					while($row = mysqli_fetch_array($result_dbview_more))
{
  $sign_id_more = $row['id'];
  $signin_date_more = $row['signin_date'];
  $signout_date_more = $row['signout_date'];
  $full_name = $row['full_name'];
  $company = $row['company'];
  
  echo 
		"<tr>
						<td>$sign_id_more</td>
						<td>$full_name</td>
						<td>$company</td>
						<td>$signin_date_more</td>
					</td>";
  
  
  
  
} }
  ?>
					
					</tbody>
					</table>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="printpagebutton2" type="button" class="btn btn-default" data-dismiss="modal" onClick="window.close();"><i class='fa fa-close'></i> Close</button>
                                        </div>
                                    </div>
    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
	<!-- DataTables -->
	<script src="plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>	
	<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>	
	<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>	
	<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>	
	<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/input-mask/jquery.inputmask.js"></script>
    <script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
    <!-- bootstrap time picker -->
    <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Page script -->
    <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate: moment()
            },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        );

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
      });
    </script>
<script>
  $(function () {
    $("#example1").DataTable(
	{
		"aaSorting": [0]
	});
    $('#example2').DataTable({
	  dom: 'Bfrtip',
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
	  "aaSorting": [],
	  responsive: true,
	  "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
	  "iDisplayLength": 50,
        buttons: [
            'pageLength','copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
	$("#example3").DataTable({
	 dom: 'Bfrtip',
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
	  "aaSorting": [],
	  responsive: true,
	  "lengthMenu": [[5, 10, 50, 100, -1], [5, 10, 50, 100, "All"]],
	  "iDisplayLength": 5,
        buttons: [
            'pageLength'
        ]
    });
  });
</script>	
  </body>
</html>         