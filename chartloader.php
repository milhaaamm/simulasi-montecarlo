<?php
include "conn.php";
$mysqli1 = mysqli_query($connection,"SELECT * FROM  bt_data");
$mysqli2 = mysqli_query($connection,"SELECT * FROM  as_data");
$mysqli3 = mysqli_query($connection,"SELECT * FROM  ra_data");
$mysqli4 = mysqli_query($connection,"SELECT * FROM  bt_new");
$mysqli5 = mysqli_query($connection,"SELECT * FROM  as_new");
$mysqli6 = mysqli_query($connection,"SELECT * FROM  ra_new");

$fetch2 = mysqli_fetch_array($mysqli2);
$fetch3 = mysqli_fetch_array($mysqli3);
$fetch5 = mysqli_fetch_array($mysqli5);
$fetch6 = mysqli_fetch_array($mysqli6);
?>
<html>
  <head>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(BT_old);
      google.charts.setOnLoadCallback(BT_new);
      google.charts.setOnLoadCallback(AS_old);
      google.charts.setOnLoadCallback(AS_new);
      google.charts.setOnLoadCallback(RA_old);
      google.charts.setOnLoadCallback(RA_new);
///////////////////////////////////////////////////////////////////
      function BT_old() 
      {
        var data = google.visualization.arrayToDataTable([
          ['Bulan', 'Produksi', 'Permintaan']
			<?php
				while($fetch1 = mysqli_fetch_array($mysqli1))
				{
					echo ",[$fetch1[data_ke],$fetch1[produksi],$fetch1[permintaan]]";
				}
			?>
        ]);
        var options1 = {
          title: 'Betel Terbang sebelum simulasi',
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('btchartold'));
        chart.draw(data, options1);
      }
      function BT_new() 
      {
        var data = google.visualization.arrayToDataTable([
          ['Bulan', 'Produksi', 'Permintaan']
			<?php
				while($fetch4 = mysqli_fetch_array($mysqli4))
				{
					echo ",[$fetch4[data_ke],$fetch4[produksi],$fetch4[permintaan]]";
				}
			?>
        ]);
        var options2 = {
          title: 'Betel Terbang setelah simulasi',
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('btchartnew'));
        chart.draw(data, options2);
      }
///////////////////////////////////////////////////////////////////
      function AS_old() 
      {
        var data = google.visualization.arrayToDataTable([
          ['Bulan', 'Produksi', 'Permintaan']
			<?php
				while($fetch2 = mysqli_fetch_array($mysqli2))
				{
					echo ",[$fetch2[data_ke],$fetch2[produksi],$fetch2[permintaan]]";
				}
			?>
        ]);
        var options3 = {
          title: 'Asultan sebelum simulasi',
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('aschartold'));
        chart.draw(data, options3);
      }
      function AS_new() 
      {
        var data = google.visualization.arrayToDataTable([
          ['Bulan', 'Produksi', 'Permintaan']
			<?php
				while($fetch5 = mysqli_fetch_array($mysqli5))
				{
					echo ",[$fetch5[data_ke],$fetch5[produksi],$fetch5[permintaan]]";
				}
			?>
        ]);
        var options4 = {
          title: 'Asultan setelah simulasi',
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('aschartnew'));
        chart.draw(data, options4);
      }
///////////////////////////////////////////////////////////////////
      function RA_old() 
      {
        var data = google.visualization.arrayToDataTable([
          ['Bulan', 'Produksi', 'Permintaan']
			<?php
				while($fetch3 = mysqli_fetch_array($mysqli3))
				{
					echo ",[$fetch3[data_ke],$fetch3[produksi],$fetch3[permintaan]]";
				}
			?>
        ]);
        var options5 = {
          title: 'Raydan sebelum simulasi',
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('rachartold'));
        chart.draw(data, options5);
      }
      function RA_new() 
      {
        var data = google.visualization.arrayToDataTable([
          ['Bulan', 'Produksi', 'Permintaan']
			<?php
				while($fetch6 = mysqli_fetch_array($mysqli6))
				{
					echo ",[$fetch6[data_ke],$fetch6[produksi],$fetch6[permintaan]]";
				}
			?>
        ]);
        var options6 = {
          title: 'Raydan setelah simulasi',
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('rachartnew'));
        chart.draw(data, options6);
      }
///////////////////////////////////////////////////////////////////
    </script>
  </head>
  <body>
  	<div class="container-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#btchart">Betel Terbang</a></li>
			<li><a data-toggle="tab" href="#aschart">Asultan</a></li>
			<li><a data-toggle="tab" href="#rachart">Raydan</a></li>
		</ul>
		<div class="tab-content">
			<div id="btchart" class="tab-pane fade in active">
				<div class="row">
					<div class="col-sm-6" id="btchartold" style="width: 900px; height: 500px">
					</div>
					<div class="col-sm-6" id="btchartnew" style="width: 900px; height: 500px">
					</div>
				</div>
			</div>
			<div id="aschart" class="tab-pane fade in active">
				<div class="row">
					<div class="col-sm-6" id="aschartold" style="width: 900px; height: 500px">
					</div>
					<div class="col-sm-6" id="aschartnew" style="width: 900px; height: 500px">
					</div>
				</div>
			</div>
			<div id="rachart" class="tab-pane fade in active">
				<div class="row">
					<div class="col-sm-6" id="rachartold" style="width: 900px; height: 500px">
					</div>
					<div class="col-sm-6" id="rachartnew" style="width: 900px; height: 500px">
					</div>
				</div>
			</div>
		</div>
  	</div>
  </body>
</html>