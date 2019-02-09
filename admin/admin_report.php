<?php 
$PageTitle = 'Visitor Report';
require_once ('top.php');

//Count recent user activity 
$sql_act_count = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE LEFT(signin_date, 10) = '$fptime_00' ORDER BY `signin_tablet`.`signin_date` DESC");
$log_activity = mysqli_num_rows($sql_act_count);

$sql_act_count00 = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE LEFT(signin_date, 10) = '$fptime_00' ORDER BY `signin_tablet`.`signin_date` DESC");
$log_activity00 = mysqli_num_rows($sql_act_count00);

$sql_act_count11 = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE LEFT(signin_date, 10) = '$fptime_11' ORDER BY `signin_tablet`.`signin_date` DESC");
$log_activity11 = mysqli_num_rows($sql_act_count11);

$sql_act_count22 = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE LEFT(signin_date, 10) = '$fptime_22' ORDER BY `signin_tablet`.`signin_date` DESC");
$log_activity22 = mysqli_num_rows($sql_act_count22);

$sql_act_count33 = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE LEFT(signin_date, 10) = '$fptime_33' ORDER BY `signin_tablet`.`signin_date` DESC");
$log_activity33 = mysqli_num_rows($sql_act_count33);

$sql_act_count44 = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE LEFT(signin_date, 10) = '$fptime_44' ORDER BY `signin_tablet`.`signin_date` DESC");
$log_activity44 = mysqli_num_rows($sql_act_count44);

$sql_act_count55 = mysqli_query($con_signin, "SELECT * FROM signin_tablet WHERE LEFT(signin_date, 10) = '$fptime_55' ORDER BY `signin_tablet`.`signin_date` DESC");
$log_activity55 = mysqli_num_rows($sql_act_count55);
?>
<script language="javascript">
function printdiv(printpage)
{
var headstr = "<html><head><title></title></head><body>";
var footstr = "</body>";
var newstr = document.all.item(printpage).innerHTML;
var oldstr = document.body.innerHTML;
document.body.innerHTML = headstr+newstr+footstr;
window.print();
document.body.innerHTML = oldstr;
return false;
}
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
		  <div class="row">
            <div class="col-xs-6">Visitors Report</div>
			<div class="col-xs-6 text-right">
			<button id="printpagebutton" type="button" onClick="printdiv('div_print');" class="btn btn-primary btn" /><i class="fa fa-print"></i> Print Report</button>
			</div>
			</div>
          </h1>
        </section>
        <section class="content">
          <div class="row">
       <div id="div_print" class="col-xs-12">
		<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', '# of Visitors'],
		  ['<?php echo $fptime_55; ?>', <?php echo $log_activity55; ?>],
		  ['<?php echo $fptime_44; ?>', <?php echo $log_activity44; ?>],
		  ['<?php echo $fptime_33; ?>', <?php echo $log_activity33; ?>],
		  ['<?php echo $fptime_22; ?>', <?php echo $log_activity22; ?>],
          ['<?php echo $fptime_11; ?>', <?php echo $log_activity11; ?>],		  
          ['<?php echo $fptime_00; ?>', <?php echo $log_activity00; ?>],

        ]);

        var options = {
          title: 'Visitors Activity',
          hAxis: {title: 'Date',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	<div id="chart_div" style="width: 100%px; height: 500px;"></div>
	</div>
	</div>
        </section>
      </div>
<?php include_once ('bottom.php'); ?>