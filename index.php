<?php
include "conn.php";
$query = "SELECT * FROM parameter WHERE name='banyakdata'";
$fetch = mysqli_fetch_array(mysqli_query($connection,$query));
$banyakdata = $fetch["value"];
$querydelete = "TRUNCATE bt_data;TRUNCATE as_data;TRUNCATE ra_data;TRUNCATE bt_new;TRUNCATE as_new;TRUNCATE ra_new;TRUNCATE distribusi_frekuensi;";
if(!mysqli_multi_query($connection,$querydelete))
	echo '<script>alert("'.mysqli_error($connection).'");</script>';
else


?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Program Simulasi</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="bootstrap.min.css">
		<!-- jQuery library -->
		<script src="jquery.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="bootstrap.min.js"></script>
		<script src="customjs.js"></script>
	 <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="page-header">
				<h1>Simulasi Produksi dan Distribusi Pelayanan Permintaan Sarung Tenun Dengan Metode Monte Carlo</h1>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3>Input Data Produksi dan Permintaan Produk</h3>
						</div>
						<div class="panel-body">
							<form method="post" action="#" id="inputtable">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th rowspan="2"><h4>Bulan</h4></th>
											<th colspan="2"><h4>Betel Terbang</h4></th>
											<th colspan="2"><h4>Asultan</h4></th>
											<th colspan="2"><h4>Rayden</h4></th>
										</tr>
										<tr>
											<th>Produksi</th>
											<th>Permintaan</th>
											<th>Produksi</th>
											<th>Permintaan</th>
											<th>Produksi</th>
											<th>Permintaan</th>
										</tr>
									</thead>
									<tbody>
										<?php
										for($i = 1;$i <= $banyakdata;$i++)
										{
										?>
										<tr>
											<td><?php echo $i;?></td>
											<td><input type="text" class="form-control" id="BT-pro<?php echo $i;?>" name="BT-pro<?php echo $i;?>"></td>
											<td><input type="text" class="form-control" id="BT-per<?php echo $i;?>" name="BT-per<?php echo $i;?>"></td>
											<td><input type="text" class="form-control" id="AS-pro<?php echo $i;?>" name="AS-pro<?php echo $i;?>"></td>
											<td><input type="text" class="form-control" id="AS-per<?php echo $i;?>" name="AS-per<?php echo $i;?>"></td>
											<td><input type="text" class="form-control" id="RA-pro<?php echo $i;?>" name="RA-pro<?php echo $i;?>"></td>
											<td><input type="text" class="form-control" id="RA-per<?php echo $i;?>" name="RA-per<?php echo $i;?>"></td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
								<div class="btn-group btn-group-justified">
									<div class="btn-group">
										<input type="reset" value="RESET" class="btn btn-danger btn-block btn-large">
									</div>
									<div class="btn-group">
										<input type="submit" value="SUMBIT" class="btn btn-primary btn-block btn-large">
									</div>
								</div>
							</form>
							<br>
							<button class="btn btn-warning btn-large btn-block" id="btnrandom">RANDOM</button>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="panel panel-success">
						<div class="panel-heading">
							<h3>Penyusun Tugas</h3>
						</div>
						<div class="panel-body">
							<h5>Kelompok 4</h5>
							<ul type="disc">
								<li>Farah Manthovani D. K (11150910000031)</li>
								<li>Muhammad Ilham (11150910000034)</li>
								<li>Ali Akbar Sastrapraja (11150910000036)</li>
								<li>Abdan Syakuro (11150910000038)</li>
								<li>Muhammad Rizky S (11150910000054)</li>
							</ul>
						</div>
					</div>
				</div>
				<!--
				<div class="col-sm-4" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3>Input data harga dan jumlah mesin yang digunakan</h3>
						</div>
						<div class="panel-body">
							<form action="#" id="dataharga">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Produk</th>
											<th>Harga per satuan produk</th>
											<th>Jumlah mesin yang digunakan</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Betel Terbang</td>
											<td><input type="text" class="form-control" name="bt_harga" id="bt_harga" /></td>
											<td><input type="text" class="form-control" name="bt_mesin" id="bt_mesin" /></td>
										</tr>
										<tr>
											<td>Asultan</td>
											<td><input type="text" class="form-control" name="as_harga" id="as_harga" /></td>
											<td><input type="text" class="form-control" name="as_mesin" id="as_mesin" /></td>
										</tr>
										<tr>
											<td>Raydan</td>
											<td><input type="text" class="form-control" name="ra_harga" id="ra_harga" /></td>
											<td><input type="text" class="form-control" name="ra_mesin" id="ra_mesin" /></td>
										</tr>
									</tbody>
								</table>
								<div class="btn-group btn-group-justified">
									<div class="btn-group">
										<input type="reset" class="btn btn-danger" value="RESET" />
									</div>
									<div class="btn-group">
										<input type="submit" class="btn btn-success" value="APPLY" />
									</div>
								</div> 
							</form>
							<br>
							<button id="btndefault" class="btn btn-block btn-warning">DEFAULT</button>
						</div>
					</div>
				</div>-->
			</div>
			<div class="row" id="result1">
			</div>
			<div class="row" id="result2">
				<div class="col-sm-12" id="chartplace">
				</div>
			</div>
		</div>
	</body>
<html>