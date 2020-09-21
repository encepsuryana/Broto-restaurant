<?php
  include_once("../koneksi.php");

  switch($_POST["jenislap"]){
    case '0' : $hari = "(CASE
                          WHEN WEEKDAY(tanggal)=0 THEN 'Senin'
                          WHEN WEEKDAY(tanggal)=1 THEN 'Selasa'
                          WHEN WEEKDAY(tanggal)=2 THEN 'Rabu'
                          WHEN WEEKDAY(tanggal)=3 THEN 'Kamis'
                          WHEN WEEKDAY(tanggal)=4 THEN 'Jumat'
                          WHEN WEEKDAY(tanggal)=5 THEN 'Sabtu'
                          ELSE 'Minggu'
                        END)
                       ";
             $bulan = "(CASE
                         WHEN MONTH(tanggal)=1 THEN 'Januari'
                         WHEN MONTH(tanggal)=2 THEN 'Februari'
                         WHEN MONTH(tanggal)=3 THEN 'Maret'
                         WHEN MONTH(tanggal)=4 THEN 'April'
                         WHEN MONTH(tanggal)=5 THEN 'Mei'
                         WHEN MONTH(tanggal)=6 THEN 'Juni'
                         WHEN MONTH(tanggal)=7 THEN 'Juli'
                         WHEN MONTH(tanggal)=8 THEN 'Agustus'
                         WHEN MONTH(tanggal)=9 THEN 'September'
                         WHEN MONTH(tanggal)=10 THEN 'Oktober'
                         WHEN MONTH(tanggal)=11 THEN 'November'
                         ELSE 'Desember'
                       END)
                      ";
               $tgl = "CONCAT($hari,', ',(DATE_FORMAT(tanggal,'%d')),' ',$bulan,' ',(YEAR(tanggal)))";
               $sql = "SELECT no_pemesanan, $tgl, total FROM t_pemesanan WHERE kode_lap_harian = $_POST[no]";
               $kolom[0] = "Nomor Pesanan"; $kolom[1] = "Tanggal"; $kolom[2] = "Total";$title="Detail Pemesanan Tanggal $_POST[tgl]"; break;
    case '1' : $hari = "(CASE
                          WHEN WEEKDAY(tanggal)=0 THEN 'Senin'
                          WHEN WEEKDAY(tanggal)=1 THEN 'Selasa'
                          WHEN WEEKDAY(tanggal)=2 THEN 'Rabu'
                          WHEN WEEKDAY(tanggal)=3 THEN 'Kamis'
                          WHEN WEEKDAY(tanggal)=4 THEN 'Jumat'
                          WHEN WEEKDAY(tanggal)=5 THEN 'Sabtu'
                          ELSE 'Minggu'
                        END)
                       ";
             $bulan = "(CASE
                         WHEN MONTH(tanggal)=1 THEN 'Januari'
                         WHEN MONTH(tanggal)=2 THEN 'Februari'
                         WHEN MONTH(tanggal)=3 THEN 'Maret'
                         WHEN MONTH(tanggal)=4 THEN 'April'
                         WHEN MONTH(tanggal)=5 THEN 'Mei'
                         WHEN MONTH(tanggal)=6 THEN 'Juni'
                         WHEN MONTH(tanggal)=7 THEN 'Juli'
                         WHEN MONTH(tanggal)=8 THEN 'Agustus'
                         WHEN MONTH(tanggal)=9 THEN 'September'
                         WHEN MONTH(tanggal)=10 THEN 'Oktober'
                         WHEN MONTH(tanggal)=11 THEN 'November'
                         ELSE 'Desember'
                       END)
                      ";
               $tgl = "CONCAT($hari,', ',(DATE_FORMAT(tanggal,'%d')),' ',$bulan,' ',(YEAR(tanggal)))";
               $sql = "SELECT kode_lap_harian, $tgl, total FROM t_laporan_harian WHERE kode_lap_mingguan = $_POST[no]";
               $kolom[0] = "Nomor Laporan"; $kolom[1] = "Tanggal"; $kolom[2] = "Total";$title="Pemasukkan Harian Minggu ini"; break;
    case '2' : $bulan = "(CASE
                         WHEN MONTH(tanggal)=1 THEN 'Januari'
                         WHEN MONTH(tanggal)=2 THEN 'Februari'
                         WHEN MONTH(tanggal)=3 THEN 'Maret'
                         WHEN MONTH(tanggal)=4 THEN 'April'
                         WHEN MONTH(tanggal)=5 THEN 'Mei'
                         WHEN MONTH(tanggal)=6 THEN 'Juni'
                         WHEN MONTH(tanggal)=7 THEN 'Juli'
                         WHEN MONTH(tanggal)=8 THEN 'Agustus'
                         WHEN MONTH(tanggal)=9 THEN 'September'
                         WHEN MONTH(tanggal)=10 THEN 'Oktober'
                         WHEN MONTH(tanggal)=11 THEN 'November'
                         ELSE 'Desember'
                       END)
                      ";
               $tgl = "CONCAT((DATE_FORMAT(tanggal,'%d')),' ',$bulan,' ',(YEAR(tanggal)))";
               $sql = "SELECT kode_lap_mingguan, $tgl, total FROM t_laporan_mingguan WHERE kode_lap_bulanan = $_POST[no]";
               $kolom[0] = "Nomor Laporan"; $kolom[1] = "Tanggal"; $kolom[2] = "Total";$title="Pemasukkan Mingguan Bulan ini"; break;
    case '3' : $bulan = "(CASE
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=1 THEN 'Januari'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=2 THEN 'Februari'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=3 THEN 'Maret'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=4 THEN 'April'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=5 THEN 'Mei'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=6 THEN 'Juni'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=7 THEN 'Juli'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=8 THEN 'Agustus'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=9 THEN 'September'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=10 THEN 'Oktober'
                WHEN MONTH((CONVERT((CONCAT(tanggal,'00')),DATE)))=11 THEN 'November'
                ELSE 'Desember'
              END)";
              $tgl = "CONCAT($bulan,' ',(YEAR((CONVERT((CONCAT(tanggal,'00')),DATE)))))";
              $sql = "SELECT kode_lap_bulanan, $tgl, total FROM t_laporan_bulanan WHERE kode_lap_tahunan = $_POST[no]";
              $kolom[0] = "Nomor Laporan"; $kolom[1] = "Tanggal"; $kolom[2] = "Total";$title="Pemasukkan Bulanan Tahun ini"; break;
  }
  mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
  $query = mysqli_query($dbcon,$sql);
  if($query){
    mysqli_commit($dbcon);
  }else{
    mysqli_rollback($dbcon);
  }
  echo "<div class='panelheader'><input type='button' onclick='closepopup()' class='exit' value='X'><label>$title</label></div>";
  echo "<div class='shtable'>";
  echo "<table>
          <tr>
            <th>$kolom[0]</th>
            <th>$kolom[1]</th>
            <th>$kolom[2]</th>
          </tr>
        </table>
  ";
  echo "<table>";
  while($data = mysqli_fetch_array($query)){
    echo " <tr>
              <td>$data[0]</td>
              <td>$data[1]</td>
              <td>Rp. $data[2]</td>
            </tr>";
  }
  echo "</table></div>";
?>
