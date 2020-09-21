<?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  if($_SESSION['pelanggan']){
    header("Location: pelanggan.php");
  }

  if($_SESSION['jabatan']!="SERVICE" || !isset($_SESSION['nip'])){
		header('Location: admin.php');
	}

  if($_POST['logout']){
    header("Location: logout.php");
  }

  include_once("koneksi.php");


  if($_POST["tbl_batal"]){
    $_SESSION["alertsts"] = 0;
    header("Location: feedback.php");
  }

  if($_POST["tbl_simpan"]){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $_SESSION["alertsts"] = 1;
    $statusdata = $_POST["stslapbaru"];
    $kode = $_POST["shkodelap"];
    $kodelama = $_POST["tempnolaplama"];
    $subjek = $_POST["shsubjeklap"];
    $konten = $_POST["shkontenlap"];
    if($statusdata==1){
      $sql = "INSERT INTO t_laporan_feedback VALUES ('$kode',now(),'$subjek','$konten')";
      $query = mysqli_query($dbcon,$sql);
      if($query){
        mysqli_commit($dbcon);
        mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
        $tempno = $_POST['tempno'];
        $jumkata = strlen($tempno);
        $tempkata = "";
        for($idx=0;$idx<$jumkata;$idx++){
          if(substr($tempno,$idx,1)!="#"){
            $tempkata = $tempkata.substr($tempno,$idx,1);
          }else{
            $sql = "UPDATE t_feedback SET kode_laporan = '$kode' WHERE no_pemesanan = $tempkata";
            $querykues = mysqli_query($dbcon,$sql);
            $tempkata = null;
          }
        }
        if($querykues){
          mysqli_commit($dbcon);
          $_SESSION["tipests"] = "success";
          $_SESSION["labelsts"] = "Berhasil";
          $_SESSION["messagests"] = 1;
          $_SESSION["message"] = "Data berhasil disimpan";
          header("Location: feedback.php");
        }else{
          mysqli_rollback($dbcon);
          $_SESSION["tipests"] = "error";
          $_SESSION["labelsts"] = "Gagal";
          $_SESSION["messagests"] = 0;
          $_SESSION["message"] = "Data gagal disimpan";
          header("Location: service.php");
        }
      }else{
        if(mysqli_errno($dbcon)==1062){
            $_SESSION["message"] = "Kode laporan sudah terdapat di database";
        }else{
          $_SESSION["message"] = mysqli_errno($dbcon)." : ".mysqli_error($dbcon);
        }
        mysqli_rollback($dbcon);
        $_SESSION["tipests"] = "error";
        $_SESSION["labelsts"] = "Error";
        $_SESSION["messagests"] = 0;
        header("Location: feedback.php");
      }
    }else{
      $sql = "UPDATE t_laporan_feedback SET kode_laporan = '$kode', tanggal = now(), judul = '$subjek', konten = '$konten' WHERE kode_laporan = '$kodelama'";
      $query = mysqli_query($dbcon,$sql);
      if($query){
        mysqli_commit($dbcon);
        $_SESSION["tipests"] = "success";
        $_SESSION["labelsts"] = "Berhasil";
        $_SESSION["messagests"] = 1;
        $_SESSION["message"] = "Pengubahan berhasil disimpan";
        header("Location: feedback.php");
      }else{
        mysqli_rollback($dbcon);
        $_SESSION["tipests"] = "error";
        $_SESSION["labelsts"] = "Gagal";
        $_SESSION["messagests"] = 0;
        $_SESSION["message"] = "Pengubahan gagal disimpan";
        header("Location: feedback.php");
      }
    }
  }

  if($_POST["tbl_hapus2"]){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $_SESSION["alertsts"] = 1;
    $tempno = $_POST['tempno'];
    $jumkata = strlen($tempno);
    $tempkata = "";
    for($idx=0;$idx<$jumkata;$idx++){
      if(substr($tempno,$idx,1)!="#"){
        $tempkata = $tempkata.substr($tempno,$idx,1);
      }else{
        $sql = "DELETE FROM t_feedback WHERE no_pemesanan = $tempkata";
        $query = mysqli_query($dbcon,$sql);
        $tempkata = null;
      }
    }
    if($query){
      mysqli_commit($dbcon);
      $_SESSION["tipests"] = "success";
      $_SESSION["labelsts"] = "Berhasil";
      $_SESSION["messagests"] = 1;
      $_SESSION["message"] = "Data kuesioner berhasil dihapus";
      header("Location: feedback.php");
    }else{
      mysqli_rollback($dbcon);
      $_SESSION["tipests"] = "error";
      $_SESSION["labelsts"] = "Gagal";
      $_SESSION["messagests"] = 0;
      $_SESSION["message"] = "Data kuesioner gagal dihapus";
      header("Location: feedback.php");
    }
  }

  if($_POST["tbl_hapus1"]){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $_SESSION["alertsts"] = 1;
    $tempno = $_POST['tempnolap'];
    $jumkata = strlen($tempno);
    $jumkode = $jumkata/7;
    $tempkata = "";
    $hitung = 0;
    for($idx=1;$idx<=$jumkode;$idx++){
      $tempkata = $tempkata.substr($tempno,$hitung,7);
      $sql = "DELETE FROM t_laporan_feedback WHERE kode_laporan = '$tempkata'";
      $query = mysqli_query($dbcon,$sql);
      $tempkata = null;
      $hitung = $hitung + 7;
    }
    if($query){
      mysqli_commit($dbcon);

      $_SESSION["tipests"] = "success";
      $_SESSION["labelsts"] = "Berhasil";
      $_SESSION["messagests"] = 1;
      $_SESSION["message"] = "Data laporan berhasil dihapus";
      header("Location: feedback.php");
		}else{
			mysqli_rollback($dbcon);
      $_SESSION["tipests"] = "error";
      $_SESSION["labelsts"] = "Gagal";
      $_SESSION["messagests"] = 0;
      $_SESSION["message"] = "Data laporan gagal dihapus";
      header("Location: feedback.php");
		}
  }

  mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
  $sql = "SELECT * FROM t_laporan_feedback";
  $querylap = mysqli_query($dbcon,$sql);
  if($querylap){
    mysqli_commit($dbcon);
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
    $kondisi = " WHERE kode_laporan IS NULL";
    $_SESSION["selek"] = "0";
    if($_POST["seleksi"]){
      $_SESSION["alertsts"] = 0;
      $dataselek = $_POST["seleksidata"];
      switch ($dataselek) {
        case 1 :
          $kondisi = " WHERE kode_laporan IS NOT NULL";
          $_SESSION["selek"] = "1";
          break;
        case '' :
          $kondisi = " ";
          $_SESSION["selek"] = "";
          break;
      }
    }
    $sql = "SELECT * FROM t_feedback $kondisi";
    $query = mysqli_query($dbcon,$sql);
    if($query){
      mysqli_commit($dbcon);
    }else{
      mysqli_rollback($dbcon);
    }
  }else{
    mysqli_rollback($dbcon);
  }
?>

<html>
  <head>
    <title>Panel FeedBack - Resto Bro</title>
  </head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/feedback.css" type="text/css">
  <link rel="stylesheet" href="css/sweetalert.css" type="text/css">
  <script type="text/javascript" async="" src="javascript/filter.js"></script>
  <script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>
  <script type="text/javascript" async="" src="javascript/prosesservice.js"></script>
  <meta charset="utf-8">
  <body>
    <form method="POST">
      <header>
        <label class="judul">Panel FeedBack - Resto Bro</label>
        <aside class="panellogout">
    				<input class="sign" id="logout" name="logout" onclick="logoutint()" type="button" value="Keluar">
    		</aside>
      </header>
      <hr>
      <div id="bodycontent">
        <section id="rightside">

          <!-- BLOCK KUES 2 -->
          <div id="bkues1" class="blockkues">
            <div class="panelheadertable">
              FeedBack - Pelanggan
              <input type="button" class="refresh" onclick="refreshdata('noradio2','rad','shtgl','shsubjek','shkonten','master','temp2','tabledata')">
            </div>
            <!-- KUES BARU -->
            <div id="tablekues">
              <aside class="table_head" id="tablehead">
                <table>
                  <thead>
                    <tr>
                      <th style='display:none'></th>
                      <th style='display:none'></th>
                      <th><input type="checkbox" id="master" onclick="checkall(0,'master','tabledata','check')"></th>
                      <th onclick="checkall(1,'master','tabledata','check')">TANGGAL</th>
                      <th onclick="checkall(1,'master','tabledata','check')">PERIHAL</th>
                      <th style='display:none'>KONTEN</th>
                    </tr>
                  </thead>
                </table>
              </aside>
              <div class="paneltable">
                <?php
                  echo '<table id="tabledata"><tbody>';
                  $i=1;
                  $cekname = '"check"';
                  $tblname = '"tabledata"';
                  $radname = '"rad"';
                  $tempname = '"temp2"';
                  while($qtabel=mysqli_fetch_assoc($query)){
                    echo "<tr title='Detail ..'>
                            <td id='nopesan$i'>$qtabel[no_pemesanan]</td>
                            <td><input class='checkbox' type='checkbox' id='check$i'></td>
                            <td id='tgl$i' onclick='detail_kues($i,$tblname)'>$qtabel[tanggal]</td>
                            <td onclick='detail_kues($i,$tblname)' id='hal$i'>$qtabel[perihal]</td>
                            <td id='konten$i'>$qtabel[konten]</td>
                          </tr>
                          <input type='radio' onchange='rubahwarna($radname,$tblname,$tempname)' id='rad$i' name='radkues' class='rad'>";
                    $i++;
                  }
                  echo "</tbody></table>";
                ?>
                <!--<td onclick='detail_kues($i,$tblname)'><input type='radio' id='rad$i' name='slideshow' class='slide' onclick='detail_kues_rad($i,$tblname)'></td>-->
                <input type="hidden" id="temp2">
                <input type="hidden" id="noradio2">
              </div>
            </div>
            <!-- END KUESBARU -->
            <!-- SHOW MESSAGE-->
            <div id="showmessage2" class="showmessage">
              <br>
              <fieldset>
              
                <div><input type="text" id="shtgl" placeholder="Tanggal" readonly><input type="text" id="shsubjek" placeholder="Subjek" readOnly></div>
                <div><textarea id="shkonten" placeholder="Konten" readonly></textarea></div>
              </fieldset>
            </div>
            <!-- END SHOW MESSAGE -->
            <div id="po1" class="paneloption">
              <input type="button" id="seleksi" name="seleksi">
              <input type="button" id="tbl_hapus2" class="tbl_hapus2" name="tbl_hapus2" onclick="hapuslaporkues('tabledata','temp2','check','nopesan','tempnokues','tbl_hapus2')" value=" ">
              <input type="hidden" id="tempnokues" name="tempno">
              <input type="hidden" id="tempnobuat" name="tempnobuat">
            </div>
          </div>
          <!-- END BLOCK KUES 1 -->
        </section>
      </div>
    </form>
    <script>
      var selek = document.getElementById("seleksidata");
      selek.value = "<?php echo $_SESSION[selek] ?>";
      if((selek.value != 0)||(selek.value == "")){
        document.getElementById("tbl_baru").disabled = true;
      }
    </script>
    <?php
      if($_SESSION["alertsts"]==1){
         if($_SESSION["messagests"]==1){
          echo "<script>
                   window.onload = function(){
                     swal({
                       title:'$_SESSION[labelsts]',
                       text: '$_SESSION[message]',
                       type: '$_SESSION[tipests]',
                       confirmButtonColor: '#2b5dcd',
                       confirmButtonText: 'OK',
                       closeOnConfirm: true
                     });
                   }
               </script>";
        }else{
          echo "<script>
                 window.onload = function(){
                   swal({
                     title: '$_SESSION[labelsts]',";
          echo '     text: "'.$_SESSION[message].'",';
          echo "     type: '$_SESSION[tipests]',
                     confirmButtonColor: '#DD6B55',
                     confirmButtonText: 'OK',
                     closeOnConfirm: true
                   });
                 }
               </script>";
        }
      }
    ?>
  </body>
</html>
