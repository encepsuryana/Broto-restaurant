<?php
	$dbcon = mysqli_connect("localhost","root","","restobro_db");
	if(mysqli_connect_errno()){
		echo "Gagal Menghubungkan Ke Database <br/>";
		echo "Error : ".mysqli_connect_error();
	}
?>