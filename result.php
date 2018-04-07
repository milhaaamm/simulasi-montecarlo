<?php

//INPUT DATA KE DATABASE//====================================================================================================
include "conn.php";
$sql = "SELECT * FROM parameter WHERE name='banyakdata'";
$query = mysqli_query($connection,$sql);
$fetch = mysqli_fetch_array($query);
$banyakdata = $fetch["value"];
for($i = 0;$i<$banyakdata;$i++)
{
	$BT_pro[$i] = isset($_POST['BT-pro'.($i+1)]) ? $_POST['BT-pro'.($i+1)] : null;
	$AS_pro[$i] = isset($_POST['AS-pro'.($i+1)]) ? $_POST['AS-pro'.($i+1)] : null;
	$RA_pro[$i] = isset($_POST['RA-pro'.($i+1)]) ? $_POST['RA-pro'.($i+1)] : null;
	$BT_per[$i] = isset($_POST['BT-per'.($i+1)]) ? $_POST['BT-per'.($i+1)] : null;
	$AS_per[$i] = isset($_POST['AS-per'.($i+1)]) ? $_POST['AS-per'.($i+1)] : null;
	$RA_per[$i] = isset($_POST['RA-per'.($i+1)]) ? $_POST['RA-per'.($i+1)] : null;
}

$sqlbt = "INSERT INTO bt_data (data_ke,produksi,permintaan) VALUES ";
$sqlas = "INSERT INTO as_data (data_ke,produksi,permintaan) VALUES ";
$sqlra = "INSERT INTO ra_data (data_ke,produksi,permintaan) VALUES ";

for($i = 0;$i<$banyakdata;$i++)
{
	$j = $i+1;
	if($i == $banyakdata-1)
	{
		$sqlbt.= "($j,$BT_pro[$i],$BT_per[$i])";
		$sqlas.= "($j,$AS_pro[$i],$AS_per[$i])";
		$sqlra.= "($j,$RA_pro[$i],$RA_per[$i])";
	}
	else
	{
		$sqlbt.= "($j,$BT_pro[$i],$BT_per[$i]),";
		$sqlas.= "($j,$AS_pro[$i],$AS_per[$i]),";
		$sqlra.= "($j,$RA_pro[$i],$RA_per[$i]),";
	}
}

if(!mysqli_query($connection,$sqlbt))
	echo 'BT DATA GAGAL : '.mysqli_error($connection).'<br>';

if(!mysqli_query($connection,$sqlas))
	echo 'AS DATA GAGAL : '.mysqli_error($connection).'<br>';

if(!mysqli_query($connection,$sqlra))
	echo 'RA DATA GAGAL : '.mysqli_error($connection).'<br>';

//BUAT TABEL FREKUENSI PROBABILITASNYA//====================================================================================================
$k = ceil(1 + 3.3 * log10($banyakdata));
$cbtpro = ceil((max($BT_pro)-min($BT_pro))/$k)+1;
$caspro = ceil((max($AS_pro)-min($AS_pro))/$k)+1;
$crapro = ceil((max($RA_pro)-min($RA_pro))/$k)+1;
$cbtper = ceil((max($BT_per)-min($BT_per))/$k)+1;
$casper = ceil((max($AS_per)-min($AS_per))/$k)+1;
$craper = ceil((max($RA_per)-min($RA_per))/$k)+1;

$zerofpro = $zerofper = false;
$pk_atasprotemp = $pk_ataspertemp = 0;
$pk_bawahpro = $pk_ataspro = 0;
$pk_bawahper = $pk_atasper = 0;
/* DATA BETEL TERBANG (BT) */
$sql = "INSERT INTO distribusi_frekuensi (id,kelas,r_bawah,r_atas,f,p,pk_bawah,pk_atas) VALUES ";

for($i = 1;$i<=$k;$i++)
{
	if($i == 1)
	{
		$r_bawahpro = min($BT_pro);
		$r_bawahper = min($BT_per);
	}
	else
	{
		$r_bawahpro = $r_ataspro+1;
		$r_bawahper = $r_atasper+1;
	}
	$idpro = "BT_pro".$i;
	$idper = "BT_per".$i;
	$r_ataspro = $r_bawahpro + $cbtpro-1;
	$r_atasper = $r_bawahper + $cbtper-1;
	$fpro = frekuensi($r_bawahpro,$r_ataspro,$BT_pro);
	$fper = frekuensi($r_bawahper,$r_atasper,$BT_per);
	$ppro = $fpro / count($BT_pro);
	$pper = $fper / count($BT_per);
	if($i == 1)
	{
		$pk_bawahpro = 1;
		$pk_bawahper = 1;
		$pk_ataspro = $pk_bawahpro + (round($ppro,3)*100) - 1;
		$pk_atasper = $pk_bawahper + (round($pper,3)*100) - 1;
	}
	else
	{
		if($ppro == 0)
		{
			$zerofpro = true;
			$pk_atasprotemp = $pk_ataspro;
			$pk_bawahpro = 0;
			$pk_ataspro = 0;
		}
		else 
		{
			if($zerofpro)
			{
				$pk_bawahpro = $pk_atasprotemp+1;
				$zerofpro = false;
			}
			else
			{
				$pk_bawahpro = $pk_ataspro+1;
			}

			$pk_ataspro = $pk_bawahpro + (round($ppro,3)*100) - 1;
		}


		if($pper == 0)
		{
			$zerofper = true;
			$pk_ataspertemp = $pk_atasper;
			$pk_bawahper = 0;
			$pk_atasper = 0;
		}
		else
		{
			if($zerofper)
			{
				$pk_bawahper = $pk_ataspertemp+1;
				$zerofper = false;
			}
			else
			{
				$pk_bawahper = $pk_atasper+1;
			}

			$pk_atasper = $pk_bawahper + (round($pper,3)*100) - 1;
		}
	}

	if($i == $k)
	{
		$sql.= '("'.$idpro.'",'.$i.','.$r_bawahpro.','.$r_ataspro.','.$fpro.','.$ppro.','.$pk_bawahpro.','.$pk_ataspro.'),';
		$sql.= '("'.$idper.'",'.$i.','.$r_bawahper.','.$r_atasper.','.$fper.','.$pper.','.$pk_bawahper.','.$pk_atasper.')';
	}
	else
	{
		$sql.= '("'.$idpro.'",'.$i.','.$r_bawahpro.','.$r_ataspro.','.$fpro.','.$ppro.','.$pk_bawahpro.','.$pk_ataspro.'),';
		$sql.= '("'.$idper.'",'.$i.','.$r_bawahper.','.$r_atasper.','.$fper.','.$pper.','.$pk_bawahper.','.$pk_atasper.'),';
	}
}

if(!mysqli_query($connection,$sql))
	echo "GAGAL : ".mysqli_error($connection).'<br>';


/* DATA ASULTAN (AS) */
$sql = "INSERT INTO distribusi_frekuensi (id,kelas,r_bawah,r_atas,f,p,pk_bawah,pk_atas) VALUES ";
for($i = 1;$i<=$k;$i++)
{
	if($i == 1)
	{
		$r_bawahpro = min($AS_pro);
		$r_bawahper = min($AS_per);
	}
	else
	{
		$r_bawahpro = $r_ataspro+1;
		$r_bawahper = $r_atasper+1;
	}
	$idpro = "AS_pro".$i;
	$idper = "AS_per".$i;
	$r_ataspro = $r_bawahpro + $caspro-1;
	$r_atasper = $r_bawahper + $casper-1;
	$fpro = frekuensi($r_bawahpro,$r_ataspro,$AS_pro);
	$fper = frekuensi($r_bawahper,$r_atasper,$AS_per);
	$ppro = $fpro / count($AS_pro);
	$pper = $fper / count($AS_per);
	if($i == 1)
	{
		$pk_bawahpro = 1;
		$pk_bawahper = 1;
		$pk_ataspro = $pk_bawahpro + (round($ppro,3)*100) - 1;
		$pk_atasper = $pk_bawahper + (round($pper,3)*100) - 1;
	}
	else
	{
		if($ppro == 0)
		{
			$zerofpro = true;
			$pk_atasprotemp = $pk_ataspro;
			$pk_bawahpro = 0;
			$pk_ataspro = 0;
		}
		else 
		{
			if($zerofpro)
			{
				$pk_bawahpro = $pk_atasprotemp+1;
				$zerofpro = false;
			}
			else
			{
				$pk_bawahpro = $pk_ataspro+1;
			}

			$pk_ataspro = $pk_bawahpro + (round($ppro,3)*100) - 1;
		}


		if($pper == 0)
		{
			$zerofper = true;
			$pk_ataspertemp = $pk_atasper;
			$pk_bawahper = 0;
			$pk_atasper = 0;
		}
		else
		{
			if($zerofper)
			{
				$pk_bawahper = $pk_ataspertemp+1;
				$zerofper = false;
			}
			else
			{
				$pk_bawahper = $pk_atasper+1;
			}

			$pk_atasper = $pk_bawahper + (round($pper,3)*100) - 1;
		}
	}

	if($i == $k)
	{
		$sql.= '("'.$idpro.'",'.$i.','.$r_bawahpro.','.$r_ataspro.','.$fpro.','.$ppro.','.$pk_bawahpro.','.$pk_ataspro.'),';
		$sql.= '("'.$idper.'",'.$i.','.$r_bawahper.','.$r_atasper.','.$fper.','.$pper.','.$pk_bawahper.','.$pk_atasper.')';
	}
	else
	{
		$sql.= '("'.$idpro.'",'.$i.','.$r_bawahpro.','.$r_ataspro.','.$fpro.','.$ppro.','.$pk_bawahpro.','.$pk_ataspro.'),';
		$sql.= '("'.$idper.'",'.$i.','.$r_bawahper.','.$r_atasper.','.$fper.','.$pper.','.$pk_bawahper.','.$pk_atasper.'),';
	}
}

if(!mysqli_query($connection,$sql))
	echo "GAGAL : ".mysqli_error($connection).'<br>';


/* DATA RAYDAN (RA) */
$sql = "INSERT INTO distribusi_frekuensi (id,kelas,r_bawah,r_atas,f,p,pk_bawah,pk_atas) VALUES ";
for($i = 1;$i<=$k;$i++)
{
	if($i == 1)
	{
		$r_bawahpro = min($RA_pro);
		$r_bawahper = min($RA_per);
	}
	else
	{
		$r_bawahpro = $r_ataspro+1;
		$r_bawahper = $r_atasper+1;
	}
	$idpro = "RA_pro".$i;
	$idper = "RA_per".$i;
	$r_ataspro = $r_bawahpro + $crapro-1;
	$r_atasper = $r_bawahper + $craper-1;
	$fpro = frekuensi($r_bawahpro,$r_ataspro,$RA_pro);
	$fper = frekuensi($r_bawahper,$r_atasper,$RA_per);
	$ppro = $fpro / count($RA_pro);
	$pper = $fper / count($RA_per);
	if($i == 1)
	{
		$pk_bawahpro = 1;
		$pk_bawahper = 1;
		$pk_ataspro = $pk_bawahpro + (round($ppro,3)*100) - 1;
		$pk_atasper = $pk_bawahper + (round($pper,3)*100) - 1;
	}
	else
	{
		if($ppro == 0)
		{
			$zerofpro = true;
			$pk_atasprotemp = $pk_ataspro;
			$pk_bawahpro = 0;
			$pk_ataspro = 0;
		}
		else 
		{
			if($zerofpro)
			{
				$pk_bawahpro = $pk_atasprotemp+1;
				$zerofpro = false;
			}
			else
			{
				$pk_bawahpro = $pk_ataspro+1;
			}

			$pk_ataspro = $pk_bawahpro + (round($ppro,3)*100) - 1;
		}


		if($pper == 0)
		{
			$zerofper = true;
			$pk_ataspertemp = $pk_atasper;
			$pk_bawahper = 0;
			$pk_atasper = 0;
		}
		else
		{
			if($zerofper)
			{
				$pk_bawahper = $pk_ataspertemp+1;
				$zerofper = false;
			}
			else
			{
				$pk_bawahper = $pk_atasper+1;
			}

			$pk_atasper = $pk_bawahper + (round($pper,3)*100) - 1;
		}
	}

	if($i == $k)
	{
		$sql.= '("'.$idpro.'",'.$i.','.$r_bawahpro.','.$r_ataspro.','.$fpro.','.$ppro.','.$pk_bawahpro.','.$pk_ataspro.'),';
		$sql.= '("'.$idper.'",'.$i.','.$r_bawahper.','.$r_atasper.','.$fper.','.$pper.','.$pk_bawahper.','.$pk_atasper.')';
	}
	else
	{
		$sql.= '("'.$idpro.'",'.$i.','.$r_bawahpro.','.$r_ataspro.','.$fpro.','.$ppro.','.$pk_bawahpro.','.$pk_ataspro.'),';
		$sql.= '("'.$idper.'",'.$i.','.$r_bawahper.','.$r_atasper.','.$fper.','.$pper.','.$pk_bawahper.','.$pk_atasper.'),';
	}
}

if(!mysqli_query($connection,$sql))
	echo "GAGAL : ".mysqli_error($connection);

//BUAT TABEL DATA BARU//====================================================================================================
for($i = 0;$i<$banyakdata;$i++)
{
	$BT_pronew[$i] = newdata($connection,"BT_pro",mt_rand(1,100),$k);
	$AS_pronew[$i] = newdata($connection,"AS_pro",mt_rand(1,100),$k);
	$RA_pronew[$i] = newdata($connection,"RA_pro",mt_rand(1,100),$k);
	$BT_pernew[$i] = newdata($connection,"BT_per",mt_rand(1,100),$k);
	$AS_pernew[$i] = newdata($connection,"AS_per",mt_rand(1,100),$k);
	$RA_pernew[$i] = newdata($connection,"RA_per",mt_rand(1,100),$k);
}

$sql = "INSERT INTO bt_new (data_ke,produksi,permintaan) VALUES ";
for($i = 0;$i<$banyakdata;$i++)
{
	$sql.= "(($i+1),$BT_pronew[$i],$BT_pernew[$i])";
	if($i != $banyakdata-1)
		$sql.=",";
}
mysqli_query($connection,$sql);


$sql = "INSERT INTO as_new (data_ke,produksi,permintaan) VALUES ";
for($i = 0;$i<$banyakdata;$i++)
{
	$sql.= "(($i+1),$AS_pronew[$i],$AS_pernew[$i])";
	if($i != $banyakdata-1)
		$sql.=",";
}
mysqli_query($connection,$sql);

$sql = "INSERT INTO ra_new (data_ke,produksi,permintaan) VALUES ";
for($i = 0;$i<$banyakdata;$i++)
{
	$sql.= "(($i+1),$RA_pronew[$i],$RA_pernew[$i])";
	if($i != $banyakdata-1)
		$sql.=",";
}
mysqli_query($connection,$sql);

function frekuensi($bawah,$atas,$array)
{
	$count = 0;
	for($i = 0;$i < count($array);$i++)
	{
		if($array[$i] >= $bawah && $array[$i] <= $atas)
			$count++;
	}
	return $count;
}


function newdata($conn,$id,$number,$kk)
{
	for($j = 1;$j <= $kk;$j++)
	{
		$idd = $id.$j;
		$fetch = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM distribusi_frekuensi WHERE id = '$idd'"));
		if($number>=$fetch["pk_bawah"] && $number<=$fetch["pk_atas"])
			return mt_rand(($fetch["r_bawah"]-1),($fetch["r_atas"]+1));

	}
}
$result1 = mysqli_query($connection,"SELECT * FROM distribusi_frekuensi WHERE id LIKE '%BT_pro%' ORDER BY kelas ASC");
$result2 = mysqli_query($connection,"SELECT * FROM distribusi_frekuensi WHERE id LIKE '%BT_per%' ORDER BY kelas ASC");
$result3 = mysqli_query($connection,"SELECT * FROM distribusi_frekuensi WHERE id LIKE '%AS_pro%' ORDER BY kelas ASC");
$result4 = mysqli_query($connection,"SELECT * FROM distribusi_frekuensi WHERE id LIKE '%AS_per%' ORDER BY kelas ASC");
$result5 = mysqli_query($connection,"SELECT * FROM distribusi_frekuensi WHERE id LIKE '%RA_pro%' ORDER BY kelas ASC");
$result6 = mysqli_query($connection,"SELECT * FROM distribusi_frekuensi WHERE id LIKE '%RA_per%' ORDER BY kelas ASC");

?>
<div class="col-sm-12">
		<div class="panel-group">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h1>Tabel Distribusi Frekuensi</h1>
			</div>
			<div class="panel-body">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#betelterbang1">Betel Terbang</a></li>
					<li><a data-toggle="tab" href="#asultan1">Asultan</a></li>
					<li><a data-toggle="tab" href="#raydan1">Raydan</a></li>
				</ul>
				<div class="tab-content">
					<div id="betelterbang1" class="tab-pane fade in active">
						<h3>Tabel distribusi frekuensi produksi dan permintaan produk Betel Terbang</h3>
						<div class="row">
							<div class="col-sm-6">
								<table class="table table-bordered">
									<caption>Produksi</caption>
									<thead>
										<tr>
											<th rowspan="2">Kelas</th>
											<th colspan="2">Range</th>
											<th rowspan="2">Frekuensi</th>
											<th rowspan="2">Probabilitas</th>
											<th colspan="2">Interval Angka Random</th>
										</tr>
										<tr>
											<th>Bawah</th>
											<th>Atas</th>
											<th>Bawah</th>
											<th>Atas</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while($fetch = mysqli_fetch_array($result1))
											{
										?>
										<tr>
											<td><?php echo $fetch['kelas'];?></td>
											<td><?php echo $fetch['r_bawah'];?></td>
											<td><?php echo $fetch['r_atas'];?></td>
											<td><?php echo $fetch['f'];?></td>
											<td><?php echo $fetch['p'];?></td>
											<td><?php echo $fetch['pk_bawah'];?></td>
											<td><?php echo $fetch['pk_atas'];?></td>
										</tr>
										<?php 
											} 
										?>
									</tbody>
									<tfoot>
										<tr>
											<td>Jumlah</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?php echo $banyakdata; ?></td>
											<td>1</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="col-sm-6">
								<table class="table table-bordered">
									<caption>Permintaan</caption>
									<thead>
										<tr>
											<th rowspan="2">Kelas</th>
											<th colspan="2">Range</th>
											<th rowspan="2">Frekuensi</th>
											<th rowspan="2">Probabilitas</th>
											<th colspan="2">Interval Angka Random</th>
										</tr>
										<tr>
											<th>Bawah</th>
											<th>Atas</th>
											<th>Bawah</th>
											<th>Atas</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while($fetch = mysqli_fetch_array($result2))
											{
										?>
										<tr>
											<td><?php echo $fetch['kelas'];?></td>
											<td><?php echo $fetch['r_bawah'];?></td>
											<td><?php echo $fetch['r_atas'];?></td>
											<td><?php echo $fetch['f'];?></td>
											<td><?php echo $fetch['p'];?></td>
											<td><?php echo $fetch['pk_bawah'];?></td>
											<td><?php echo $fetch['pk_atas'];?></td>
										</tr>
										<?php 
											} 
										?>
									</tbody>
									<tfoot>
										<tr>
											<td>Jumlah</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?php echo $banyakdata; ?></td>
											<td>1</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div id="asultan1" class="tab-pane fade">
						<h3>Tabel distribusi frekuensi produksi dan permintaan produk Asultan</h3>
						<div class="row">
							<div class="col-sm-6">
								<table class="table table-bordered">
									<caption>Produksi</caption>
									<thead>
										<tr>
											<th rowspan="2">Kelas</th>
											<th colspan="2">Range</th>
											<th rowspan="2">Frekuensi</th>
											<th rowspan="2">Probabilitas</th>
											<th colspan="2">Interval Angka Random</th>
										</tr>
										<tr>
											<th>Bawah</th>
											<th>Atas</th>
											<th>Bawah</th>
											<th>Atas</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while($fetch = mysqli_fetch_array($result3))
											{
										?>
										<tr>
											<td><?php echo $fetch['kelas'];?></td>
											<td><?php echo $fetch['r_bawah'];?></td>
											<td><?php echo $fetch['r_atas'];?></td>
											<td><?php echo $fetch['f'];?></td>
											<td><?php echo $fetch['p'];?></td>
											<td><?php echo $fetch['pk_bawah'];?></td>
											<td><?php echo $fetch['pk_atas'];?></td>
										</tr>
										<?php 
											} 
										?>
									</tbody>
									<tfoot>
										<tr>
											<td>Jumlah</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?php echo $banyakdata; ?></td>
											<td>1</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="col-sm-6">
								<table class="table table-bordered">
									<caption>Permintaan</caption>
									<thead>
										<tr>
											<th rowspan="2">Kelas</th>
											<th colspan="2">Range</th>
											<th rowspan="2">Frekuensi</th>
											<th rowspan="2">Probabilitas</th>
											<th colspan="2">Interval Angka Random</th>
										</tr>
										<tr>
											<th>Bawah</th>
											<th>Atas</th>
											<th>Bawah</th>
											<th>Atas</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while($fetch = mysqli_fetch_array($result4))
											{
										?>
										<tr>
											<td><?php echo $fetch['kelas'];?></td>
											<td><?php echo $fetch['r_bawah'];?></td>
											<td><?php echo $fetch['r_atas'];?></td>
											<td><?php echo $fetch['f'];?></td>
											<td><?php echo $fetch['p'];?></td>
											<td><?php echo $fetch['pk_bawah'];?></td>
											<td><?php echo $fetch['pk_atas'];?></td>
										</tr>
										<?php 
											} 
										?>
									</tbody>
									<tfoot>
										<tr>
											<td>Jumlah</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?php echo $banyakdata; ?></td>
											<td>1</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div id="raydan1" class="tab-pane fade">
						<h3>Tabel distribusi frekuensi produksi dan permintaan produk Rayden</h3>
						<div class="row">
							<div class="col-sm-6">
								<table class="table table-bordered">
									<caption>Produksi</caption>
									<thead>
										<tr>
											<th rowspan="2">Kelas</th>
											<th colspan="2">Range</th>
											<th rowspan="2">Frekuensi</th>
											<th rowspan="2">Probabilitas</th>
											<th colspan="2">Interval Angka Random</th>
										</tr>
										<tr>
											<th>Bawah</th>
											<th>Atas</th>
											<th>Bawah</th>
											<th>Atas</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while($fetch = mysqli_fetch_array($result5))
											{
										?>
										<tr>
											<td><?php echo $fetch['kelas'];?></td>
											<td><?php echo $fetch['r_bawah'];?></td>
											<td><?php echo $fetch['r_atas'];?></td>
											<td><?php echo $fetch['f'];?></td>
											<td><?php echo $fetch['p'];?></td>
											<td><?php echo $fetch['pk_bawah'];?></td>
											<td><?php echo $fetch['pk_atas'];?></td>
										</tr>
										<?php 
											} 
										?>
									</tbody>
									<tfoot>
										<tr>
											<td>Jumlah</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?php echo $banyakdata; ?></td>
											<td>1</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="col-sm-6">
								<table class="table table-bordered">
									<caption>Permintaan</caption>
									<thead>
										<tr>
											<th rowspan="2">Kelas</th>
											<th colspan="2">Range</th>
											<th rowspan="2">Frekuensi</th>
											<th rowspan="2">Probabilitas</th>
											<th colspan="2">Interval Angka Random</th>
										</tr>
										<tr>
											<th>Bawah</th>
											<th>Atas</th>
											<th>Bawah</th>
											<th>Atas</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while($fetch = mysqli_fetch_array($result6))
											{
										?>
										<tr>
											<td><?php echo $fetch['kelas'];?></td>
											<td><?php echo $fetch['r_bawah'];?></td>
											<td><?php echo $fetch['r_atas'];?></td>
											<td><?php echo $fetch['f'];?></td>
											<td><?php echo $fetch['p'];?></td>
											<td><?php echo $fetch['pk_bawah'];?></td>
											<td><?php echo $fetch['pk_atas'];?></td>
										</tr>
										<?php 
											} 
										?>
									</tbody>
									<tfoot>
										<tr>
											<td>Jumlah</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?php echo $banyakdata; ?></td>
											<td>1</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h1>Hasil Generate Random angka berdasarkan distribusi frekuensi menggunakan metode Monte Carlo</h1>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th rowspan="2">Minggu ke</th>
							<th colspan="2">Betel Terbang</th>
							<th colspan="2">Asultan</th>
							<th colspan="2">Raydan</th>
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
						for($ii = 0;$ii < $banyakdata;$ii++)
						{
						?>
						<tr>
							<td><?php echo ($ii+1);?></td>
							<td><?php echo $BT_pronew[$ii];?></td>
							<td><?php echo $BT_pernew[$ii];?></td>
							<td><?php echo $AS_pronew[$ii];?></td>
							<td><?php echo $AS_pernew[$ii];?></td>
							<td><?php echo $RA_pronew[$ii];?></td>
							<td><?php echo $RA_pernew[$ii];?></td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>					