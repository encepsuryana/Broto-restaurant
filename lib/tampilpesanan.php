<?php
  include_once("../koneksi.php");
  $sql = "SELECT no_pemesanan,no_meja,total FROM t_pemesanan WHERE status_bayar = 1";
  $query = mysqli_query($dbcon,$sql);
  echo "
    <table>
      <tr>
        <th>Nomor Pesan</th>
        <th>Nomor Meja</th>
        <th>Total</th>
      </tr>
  ";
  while($row = mysqli_fetch_assoc($query)){
    echo "
      <tr onclick='generateharga($row[no_pemesanan],$row[no_meja],$row[total])'>
        <td>$row[no_pemesanan]</td>
        <td>$row[no_meja]</td>
        <td>$row[total]</td>
      </tr>
    ";
  }
?>
