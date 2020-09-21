<?php
	error_reporting(E_ALL & ~E_NOTICE);
	session_start();

	if(isset($_SESSION['pelanggan'])){
		header("Location: pelanggan.php");
	}

	if($_POST['submit']){
		include_once("koneksi.php");
		$username 	= $_POST['username'];
		$password 	= $_POST['password'];
		$sql 		    = "SELECT NIP,sandi,jabatan FROM t_pegawai WHERE NIP = '$username'";
		$query 	  	= mysqli_query($dbcon, $sql);
		if($query){
			$row = mysqli_fetch_assoc($query);
			$dbusername = $row["NIP"];
			$dbpassword	= $row["sandi"];
			$dbjabatan  = $row["jabatan"];
		}

		if(($username==$dbusername)&&($password == $dbpassword)){
			$_SESSION['jabatan'] = $dbjabatan;
			$_SESSION['nip'] = $dbusername;
			$_SESSION['password'] = $dbpassword;

			switch($dbjabatan){
				case "KOKI" 	: header("Location: hidangan.php");break;
				case "PANTRY" 	: header("Location: pantry.php");break;
				case "KASIR" 	: header("Location: kasir.php");break;
				case "SERVICE" 	: header("Location: feedback.php");break;
			}
		}else{
			$error = 1;
		}
	}
?>

<html>
	<head>
		<title>Panel Pegawai</title>
		<!--Link CSS-->
		<meta charset="UTF-8">
		<link rel=stylesheet href="css/css.css" type="text/css">
		<link rel="stylesheet" href="css/sweetalert.css" type="text/css">
		<script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>
		<script src="javascript/jquery.js"></script>
	</head>
	<script>
		function validasilogin(){
			var user = document.getElementById("username").value;
			var pass = document.getElementById("password").value;
			if((user == "")||(pass == "")){
				swal({
					title: "Error",
					text: " Kolom username atau password masih kosong",
					type: "error",
					confirmButtonColor: "#2b5dcd",
					confirmButtonText: "OK",
					closeOnConfirm: true
				});
			}else{
				document.getElementById("login").type="submit";
				document.getElementById("login").submit();
			}
		}
	</script>
	<body>
		<a href="index.php"><span class="back"></span></a>
		<header class="header">
			<div class="panelheader">
				Resto Bro - Akses Pegawai
			</div>
		</header>
		<div class="utama">
			<div class="panelinputadmin">
				<div class="panelheaderadmin">Panel Pegawai</div>
				<div class="panelcontentadmin">
					<form method="POST" name="admin">
						Username<br>
						<input class="form_admin" id="username" type="text" name="username" placeholder="NIP">
						<br>Password<br>
						<input class="form_admin" id="password" type="password" name="password" placeholder="Password">
						<br>
						<input class="login_admin" id="login" onclick="validasilogin()" type="button" name="submit" value="Masuk">
					</form>
				</div>
			</div>
		</div>
		<script>
			var err = "<?php echo $error?>";
			if(err==1){
				window.onload = function(){
					swal({
						title: "Error",
						text: " Kolom username atau password salah",
						type: "error",
						confirmButtonColor: "#2b5dcd",
						confirmButtonText: "OK",
						closeOnConfirm: true
					});
				}
			}
		</script>
	</body>
</html>
