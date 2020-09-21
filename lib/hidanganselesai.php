<?php
  include_once("../koneksi.php");
  $sql = "UPDATE t_transaksi SET status_buat = 1 WHERE no_pemesanan = $_POST[nomor] AND no_hidangan = $_POST[no_hidangan]";
  $query = mysqli_query($dbcon,$sql);
  if($query){
    mysqli_commit($dbcon);
  }else{
    mysqli_rollback($dbcon);
  }
?>
