<?php
  include_once("../koneksi.php");
  $nomor = $_POST["nomor"];
  $sql = "SELECT no_pemesanan,nama_hidangan,jum_item,a.no_hidangan FROM t_transaksi a JOIN
          t_hidangan b ON a.no_hidangan = b.no_hidangan
          WHERE a.no_pemesanan = $nomor AND status_buat = 0";
  $query = mysqli_query($dbcon,$sql);
  echo "
    <table>
      <tr>
        <th>Nomor Pesan</th>
        <th>Nama Hidangan</th>
        <th>Jumlah</th>
        <th>Aksi</th>
      </tr>
  ";
  while($row = mysqli_fetch_assoc($query)){
    echo "
      <tr>
        <td>$row[no_pemesanan]</td>
        <td>$row[nama_hidangan]</td>
        <td>$row[jum_item]</td>
        <td><input type='button' onclick='dataselesai($row[no_pemesanan],$row[no_hidangan])' id='finish' name='finish' value='Selesai'></td>
      </tr>
    ";
  }
?>
