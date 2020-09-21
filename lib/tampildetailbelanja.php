<?php
  include_once("../koneksi.php");

  mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
  $sql = "SELECT nama_bahan,a.jumlah,harga_satuan,harga_total,a.keterangan FROM
          t_detail_lap_belanja a JOIN t_bahan_baku b ON a.no_bahan = b.no_bahan
          WHERE kode_lap_belanja = '$_POST[kode]'";
  $query = mysqli_query($dbcon,$sql);
  if($query){
    mysqli_commit($dbcon);
  }else{
    mysqli_rollback($dbcon);
  }

  echo "<div class='shtable'>";
  echo "<table>
          <tr>
            <th>Nama Bahan</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Harga Total</th>
            <th>Keterangan</th>
          </tr>
       </table>
  ";
  echo "<table>";
  while($data = mysqli_fetch_array($query)){
    echo '<tr>';
    echo "    <td>$data[0]</td>
              <td>$data[1]</td>
              <td>$data[2]</td>
              <td>$data[3]</td>
              <td>$data[4]</td>
            </tr>";
  }
  echo "</table></div>";
?>
