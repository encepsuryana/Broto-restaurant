<?php
  include_once("../koneksi.php");

  mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
  $sql = "SELECT kode_laporan,tanggal,judul,konten FROM t_laporan_feedback";
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
            <th>Judul</th>
          </tr>
       </table>
  ";
  echo "<table>";
  while($data = mysqli_fetch_array($query)){
    echo '<tr onclick="detaillapkues('."'$data[0]','$data[2]'".')">';
    echo "    <td>$data[0]</td>
              <td>$data[1]</td>
              <td>$data[2]</td>
              <td id='$data[0]'>$data[3]</td>
            </tr>";
  }
  echo "</table></div>";
?>
