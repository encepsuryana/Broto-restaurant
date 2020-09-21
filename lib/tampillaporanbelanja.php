<?php
  include_once("../koneksi.php");

  mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
  $sql = "SELECT kode_lap_belanja,tanggal,budget,pengeluaran FROM t_laporan_belanja";
  $query = mysqli_query($dbcon,$sql);
  if($query){
    mysqli_commit($dbcon);
  }else{
    mysqli_rollback($dbcon);
  }

  echo "<div class='shtable'>";
  echo "<table>
          <tr>
            <th>Kode Laporan</th>
            <th>Tanggal</th>
            <th>Budget</th>
            <th>Pengeluaran</th>
          </tr>
       </table>
  ";
  echo "<table>";
  while($data = mysqli_fetch_array($query)){
    echo "<tr onclick='detailbelanja(".'"'.$data[0].'"'.")'>";
    echo "    <td>$data[0]</td>
              <td>$data[1]</td>
              <td>$data[2]</td>
              <td>$data[3]</td>
            </tr>";
  }
  echo "</table></div>";
?>
