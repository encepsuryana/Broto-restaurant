<?php
  include_once("../koneksi.php");
  $nomor = $_POST["nomor"];
  $sql = "SELECT nama_hidangan,jum_item,a.harga,a.total FROM t_transaksi a JOIN t_pemesanan b
          ON a.no_pemesanan = b.no_pemesanan JOIN t_hidangan c ON a.no_hidangan = c.no_hidangan
          WHERE a.no_pemesanan = $nomor";
  $query = mysqli_query($dbcon,$sql);
  echo "
    <table>
      <tr>
        <th>Hidangan</th>
        <th>Jumlah Pesan</th>
        <th>Harga Satuan</th>
        <th>Harga Total</th>
      </tr>
  ";
  while($row = mysqli_fetch_assoc($query)){
    echo "
      <tr>
        <td>$row[nama_hidangan]</td>
        <td>$row[jum_item]</td>
        <td>$row[harga]</td>
        <td>$row[total]</td>
      </tr>
    ";
  }
?>
