<?php
	error_reporting(E_ALL & ~E_NOTICE);
	session_start();
	if(!isset($_SESSION['nomor'])){
		header("Location: index.php");
	}else{
		$_SESSION['pelanggan']=true;
	}

	if(isset($_SESSION['no_pesan'])){
		header("Location: order.php");
	}

	include_once("koneksi.php");

	if($_POST['masuk']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
		$sql = "SELECT no_pemesanan FROM t_pemesanan ORDER BY no_pemesanan DESC LIMIT 1";
		$query = mysqli_query($dbcon,$sql);
		if($query){
			mysqli_commit($dbcon);
			$data = mysqli_fetch_assoc($query);
			$_SESSION["no_pesan"] = (int)$data["no_pemesanan"] + 1;
			unset($_SESSION["total_bayar"]);
			unset($_SESSION["tahap"]);
			header("Location: order.php");
		}else{
			mysqli_rollback($dbcon);
		}
	}

	if($_POST['validasi']){
		if (empty($_POST['username']) || empty($_POST['password'])) {
			$error = 1;
			$pesan = "Kolom username atau password masih kosong";
		}else{
			$username 	= strip_tags($_POST['username']);
			$password 	= strip_tags($_POST['password']);

			$sql 		= "SELECT nip,sandi FROM t_pegawai WHERE nip = '$username'";
			$query 		= mysqli_query($dbcon, $sql);

			if($query){
				$row = mysqli_fetch_row($query);
				$dbusername = $row[0];
				$dbpassword	= $row[1];
			}

			if($username == $dbusername && $password == $dbpassword){
				session_destroy();
				header('Location: index.php');
			}else{
				$error = 1;
				$pesan = "Username atau password salah";
			}
		}
	}
?>

<html>
	<head>
		<title>Selamat Datang Di Resto Bro</title>
		<!--Link CSS-->
		<meta charset="UTF-8">
		<link rel=stylesheet href="css/css.css" type="text/css">
		<link rel=stylesheet href="css/sweetalert.css" type="text/css">
		<script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>
	</head>
	<body>
		<div id="popupBox" class="popup">
			<!-- jwpopup content -->
			<div class="popup-content">
				Validasi Kembali
				<span class="close">x</span>
			</div>
			<div class="popup-content2">
				<form action="" method="POST">
					Username
					<br>
					<input class="form_admin" type="text" name="username">
					<br>
					Password
					<br>
					<input class="form_admin" type="password" name="password">
					<br>
					<input type="submit" class="login_admin" name="validasi" value="Kembali"></input>
				</form>
			</div>
		</div>
		<span class="back" id="popuplogin" value="back"></span>
		<header class="header">
		</header>
		<section class="panelinputadmin">
			<div class="panelinput1" align="center">
				Meja Pelanggan No : <span><?php echo "$_SESSION[nomor]"?></span>
				<form method="POST" name="Masuk">
				</br>
					 <input class="submit" type="submit" name="masuk" value="Daftar Menu">
				</form>
			</div>
		</section>
		<script>
			var err = "<?php echo $error ?>";
			if(err == 1){
				window.onload = function(){
					swal({
						title: "Error",
						text: "<?php echo $pesan ?>",
						type: "error",
						confirmButtonColor: '#2b5dcd',
						confirmButtonText: 'OK',
						closeOnConfirm: true
					});
				}
			}
			// untuk mendapatkan jwpopup
			var jwpopup = document.getElementById("popupBox");
			var link = document.getElementById("popuplogin");
			var close = document.getElementsByClassName("close")[0];
			link.onclick = function() {
				jwpopup.style.display = 'block';
			}
			close.onclick = function(){
				jwpopup.style.display = 'none';
			}

			// membuka jwpopup ketika user melakukan klik diluar area popup
			window.onclick = function(event) {
				if (event.target == jwpopup)  {
					jwpopup.style.display = 'none';
				}
			}
		</script>
	</body>
</html>
