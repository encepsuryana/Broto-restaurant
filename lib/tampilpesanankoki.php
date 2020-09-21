<?php
  include_once("../koneksi.php");
  $sql = "SELECT a.no_pemesanan,no_meja, (SELECT sum(jum_item) FROM t_transaksi b WHERE b.no_pemesanan = a.no_pemesanan AND status_buat <> 1) FROM
          t_pemesanan a WHERE status_bayar <> 2";
  $query = mysqli_query($dbcon,$sql);
  echo "
    <table>
      <tr>
        <th>Nomor Pesan</th>
        <th>Nomor Meja</th>
        <th>Jumlah Hidangan</th>
      </tr>
  ";
  while($row = mysqli_fetch_array($query)){
    echo "
      <tr onclick=cekdetail($row[0])>
        <td>$row[0]</td>
        <td>$row[1]</td>
        <td>$row[2]</td>
      </tr>
    ";
  }
?>
