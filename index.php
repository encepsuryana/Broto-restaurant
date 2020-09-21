<?php
	error_reporting(E_ALL & ~E_NOTICE);
	session_start();

	include_once("koneksi.php");

	if(!isset($_SESSION["statustahun"])){
		$sql = "SELECT COUNT(*) as jum from t_laporan_tahunan where tanggal = date_format(now(),'%Y')";
		$query = mysqli_query($dbcon,$sql);
		$data = mysqli_fetch_assoc($query);
		if($data["jum"]==0){
			$sql = "INSERT INTO t_laporan_tahunan VALUES (null,date_format(CURDATE(),'%Y'),null)";
			mysqli_query($dbcon,$sql);
		}
		$_SESSION["statustahunan"] = 1;
	}

	if(!isset($_SESSION["statusbulan"])){
		$sql = "SELECT COUNT(*) as jum from t_laporan_bulanan where tanggal = date_format(CURDATE(),'%Y%m')";
		$query = mysqli_query($dbcon,$sql);
		$data = mysqli_fetch_assoc($query);
		if($data["jum"]==0){
			$sql = "INSERT INTO t_laporan_bulanan VALUES
							(null,date_format(CURDATE(),'%Y%m'),null,
							(SELECT kode_lap_tahunan FROM t_laporan_tahunan ORDER BY kode_lap_tahunan DESC LIMIT 1))";
			mysqli_query($dbcon,$sql);
		}
		$_SESSION["statusbulan"] = 1;
	}

	if(!isset($_SESSION["statusminggu"])){
		$tgl_lalu = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$tgl_lalu = date("D",$tgl_lalu);
		switch($tgl_lalu){
			case "Sun" : $rentang = 0;break;
			case "Mon" : $rentang = 6;break;
			case "Tue" : $rentang = 5;break;
			case "Wed" : $rentang = 4;break;
			case "Thu" : $rentang = 3;break;
			case "Fri" : $rentang = 2;break;
			case "Sat" : $rentang = 1;break;
		}
		$sql = "SELECT COUNT(*) as jum from t_laporan_mingguan where tanggal = DATE_ADD(CURDATE(), INTERVAL $rentang DAY)";
		$query = mysqli_query($dbcon,$sql);
		$data = mysqli_fetch_assoc($query);
		if($data["jum"]==0){
			$sql = "INSERT INTO t_laporan_mingguan VALUES
							(null, DATE_ADD(CURDATE(), INTERVAL $rentang DAY),null,
							(SELECT kode_lap_bulanan FROM t_laporan_bulanan ORDER BY kode_lap_bulanan DESC LIMIT 1))";
			mysqli_query($dbcon,$sql);
		}
		$_SESSION["statusminggu"] = 1;
	}

	if(!isset($_SESSION["statushari"])){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$sql = "SELECT COUNT(*) as jum from t_laporan_harian where tanggal = CURDATE()";
		$query = mysqli_query($dbcon,$sql);
		if($query){
			mysqli_commit($dbcon);
			$data = mysqli_fetch_assoc($query);
			if($data["jum"]==0){
				mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
				$sql = "INSERT INTO t_laporan_harian VALUES (null,CURDATE(),null,
								(SELECT kode_lap_mingguan FROM t_laporan_mingguan ORDER BY kode_lap_mingguan DESC LIMIT 1))";
				$query = mysqli_query($dbcon,$sql);
				if($query){
					mysqli_commit($dbcon);
				}else{
					mysqli_rollback($dbcon);
				}
			}
		}else{
			mysqli_rollback($dbcon);
		}
		$_SESSION["statushari"] = 1;
	}

	if($_SESSION['pelanggan']){
		header("Location: pelanggan.php");
	}

	if($_POST['admin']){
		$_SESSION["statusmenu"] = 'sistem';
		header("Location: admin.php");
	}else if(($_POST['tetapkan'])&&($_POST['nomor']!=null)){
		$_SESSION['nomor'] = $_POST['nomor'];
		header("Location: pelanggan.php");
	}
?>

<html>
	<head>
		<title>Resto Bro</title>
		<link rel="stylesheet" href="css/index.css" type="text/css">
	</head>
	<body>
		<form method="POST">
			<!-- popup -->
			<div id="popupBox">
				<div id="wrapper">
					<div class="head">
						<fieldset>
							<div><label>Nomor Meja</label></div>
							<div class="close"><span class="exit">x</span></div>
						</fieldset>
					</div>
					<div id="isi">
						<fieldset>
							<div id="setinput">
								<input type="text" onkeyup="return validasi(this)" id="nomor" name="nomor">
								<input type="submit" value="Tetapkan" id="tetapkan" name="tetapkan">
							</div>
						</fieldset>
					</div>
				</div>
			</div>

			<aside class="utama">
				<aside class="interface">
					<header class="above">
						Resto Bro
					</header>
					<section class="middle">
						<aside class="panelmenu">
							
							<aside class="menu">
									<input type="submit" id="admin" name="admin" value="ADMIN" class="author">
									<input type="button" id="pelanggan" onclick="nomeja()" name="pelanggan" value="PELANGGAN" class="pelanggan">
							</aside>
							<span class="judul">Selamat Datang</span>
						</aside>
					</section>
					<footer class="bottom">
					</footer>
				</aside>
		</aside>
		</form>
	</body>
	<script>
		function validasi(val){
			val.value = val.value.replace(/[^0-9]/g,'');
			return true;
		}

		var popupbox = document.getElementById('popupBox');
		var wrap = document.getElementById('wrapper');
		var close = document.getElementsByClassName('close')[0];
		function nomeja(){
			popupbox.style.display = 'block';
			wrap.style.display = 'block';
		}

		close.onclick = function(){
			popupbox.style.display = 'none';
			wrap.style.display = 'none';
		}

		// membuka jwpopup ketika user melakukan klik diluar area popup
		window.onclick = function(event) {
			if (event.target == popupbox)  {
				popupbox.style.display = 'none';
				wrap.style.display = 'none';
			}
		}
	</script>
</html>
