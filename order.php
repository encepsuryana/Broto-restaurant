<?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  if(!isset($_SESSION['pelanggan']) || !isset($_SESSION['nomor'])){
    header('Location: pelanggan.php');
  }

  include_once("koneksi.php");

  if(($_POST["back"])||($_POST["tutup"])){
      unset($_SESSION['no_pesan']);
      unset($_SESSION['aktifbayar']);
      unset($_SESSION['total_bayar']);
      unset($_SESSION['tahap']);
      unset($_SESSION['status_popup']);
      header("Location: pelanggan.php");
  }


  if(isset($_SESSION["total_bayar"])){
    $_SESSION['aktifbayar'] = "1";
  }

  if(isset($_SESSION["aktifbayar"])){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
    $sql = "SELECT nama_hidangan,jum_item,harga,total FROM t_transaksi a JOIN t_hidangan b ON a.no_hidangan = b.no_hidangan WHERE no_pemesanan = $_SESSION[no_pesan]";
    $querytrans = mysqli_query($dbcon,$sql);
    if($querytrans){
      mysqli_commit($dbcon);
    }else{
      mysqli_rollback($dbcon);
    }
  }

  if($_POST["kuesbtn"]){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $hal = strtoupper($_POST["subjek"]);
    $konten = $_POST["textkonten"];
    $sql = "INSERT INTO t_feedback VALUES ($_SESSION[no_pesan],now(),'$hal','$konten',null)";
    $query = mysqli_query($dbcon,$sql);
    if($query){
      mysqli_commit($dbcon);
      $displaysmile=1;
    }else{
      mysqli_rollback($dbcon);
    }
  }

  if($_POST["tbl_order_list"]){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $_SESSION["total_bayar"] = $_POST["temp"];
    if(!isset($_SESSION["tahap"])){
      $sql = "INSERT INTO t_pemesanan  VALUES ($_SESSION[no_pesan],$_SESSION[nomor],CURDATE(),$_SESSION[total_bayar],0,(SELECT kode_lap_harian FROM t_laporan_harian ORDER BY kode_lap_harian DESC LIMIT 1))";
      $_SESSION["tahap"] = true;
    }else{
      $sql = "UPDATE t_pemesanan SET total= $_SESSION[total_bayar], status_bayar=0 WHERE no_pemesanan = $_SESSION[no_pesan]";
    }
    $querysimpan = mysqli_query($dbcon,$sql);
    if($querysimpan){
      mysqli_commit($dbcon);
      mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
      $jumbaris = $_POST["temp1"];
      echo "<script></script>";
      for($i=1;$i<=$jumbaris;$i++){
        $nohidangan = $_POST["nomor($i)"];
        $jum = $_POST["jum($nohidangan)"];
        $sql = "INSERT INTO t_transaksi VALUES ($_SESSION[no_pesan],
              $nohidangan,$jum,(SELECT harga_hidangan FROM t_hidangan WHERE no_hidangan = $nohidangan),
              $jum*((SELECT harga_hidangan FROM t_hidangan WHERE no_hidangan = $nohidangan)),0)";
        $querytransaksi = mysqli_query($dbcon,$sql);
        if($querytransaksi){
          mysqli_commit($dbcon);
          $_SESSION["aktifbayar"] = "1";
          header("Location: order.php");
        }else{
          mysqli_rollback($dbcon);
        }
      }
    }else{
      mysqli_rollback($dbcon);
    }
  }

  if($_POST["tbl_bayar"]){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $sql = "UPDATE t_pemesanan SET status_bayar= 1 WHERE no_pemesanan = $_SESSION[no_pesan]";
    $queryupdate = mysqli_query($dbcon,$sql);
    if($queryupdate){
      mysqli_commit($dbcon);
      $_SESSION["status_popup"] = "1";
      header("Location: order.php");
    }else{
      mysqli_rollback($dbcon);
    }
  }

  mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
  $sqlapp = "SELECT no_hidangan,nama_hidangan,kode_tipe,harga_hidangan,image FROM t_hidangan WHERE status=1 AND kode_tipe = 'APP' ORDER BY kode_tipe";
  $queryapp = mysqli_query($dbcon,$sqlapp);
  $sqlmac = "SELECT no_hidangan,nama_hidangan,kode_tipe,harga_hidangan,image FROM t_hidangan WHERE status=1 AND kode_tipe = 'MAC' ORDER BY kode_tipe";
  $querymac = mysqli_query($dbcon,$sqlmac);
  $sqldes = "SELECT no_hidangan,nama_hidangan,kode_tipe,harga_hidangan,image FROM t_hidangan WHERE status=1 AND kode_tipe = 'DES' ORDER BY kode_tipe";
  $querydes = mysqli_query($dbcon,$sqldes);
  $sqlsd = "SELECT no_hidangan,nama_hidangan,kode_tipe,harga_hidangan,image FROM t_hidangan WHERE status=1 AND kode_tipe = 'SD' ORDER BY kode_tipe";
  $querysd = mysqli_query($dbcon,$sqlsd);
  if($queryapp && $querymac && $querydes && $querysd){
    mysqli_commit($dbcon);
  }else{
    mysqli_rollback($dbcon);
  }
?>

<html>
  <head>
    <title>Daftar Menu - Resto Bro</title>
  </head>
  <link rel="stylesheet" href="css/order.css" type="text/css">
  <script type="text/javascript" async="" src="javascript/filter.js"></script>
  <script type="text/javascript" async="" src="javascript/prosesorder.js"></script>
  <link rel="stylesheet" href="css/sweetalert.css" type="text/css">
  <script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>

  <body>
    <!-- FORM -->
    <form method="POST">
      <input type="hidden" id="konfirmasi">
      <div id="popup" class="popup">
        <div id="info1" class="info">Terima Kasih Atas Kunjungannya</div>
        <div id="info2" class="info">Mohon Tunggu Sebentar, Pelayan kami akan segera datang..</div>
        <div id="panelkues">
          <div  id="panelheader">
            Silahkan Isi FeedBack, Untuk membantu kami lebih baik
            <div class="lingkar">
              <input id="closebtn" name="tutup" type="button" onclick="closepopup()">
            </div>
          </div>
          <div id="panelcontent">
            <div id="emotsmile">
            </div>
            <div id="contenttext">
              <div><label>Subjek/Tentang :</label></div>
              <input type="text" id="subjek" name="subjek">
              <div><label>Konten :</label></div>
              <textarea id="textkonten" name="textkonten"></textarea>
              <hr>
              <input type="button" id="kuesbtn" name="kuesbtn" value="Kirim" onclick="kirimkues()">
            </div>
          </div>
        </div>
      </div>
      <header>
        <label class="judul">Resto Bro - Daftar Menu</label>
        <input type="button" id="back" name="back" onclick="backint()">
      </header>
      <!-- MAINBODY -->
      <div id="mainbody">
        <!-- LEFT -->
        <div id="left">
          <!-- RBLOCKCONTENT1 -->
          <div  id="lblockcontent1">
            <div class="block">
              <fieldset>
                <legend>Pilih Jenis Menu</legend>
                <select id="tipe" name="tipe" onchange="changetipe(this.value)">
                  <option value="">SEMUA JENIS</option>
                  <option value="APP">MENU PEMBUKA</option>
                  <option value="MAC">MENU UTAMA</option>
                  <option value="DES">MENU PENUTUP</option>
                  <option value="SD">MINUMAN</option>
                </select>
              </fieldset>
            </div>
            <div class="block">
              <fieldset class="blockpop">
                  <legend>Cari Menu</legend>
                  <input type="search" class="light-table-filter"
                        data-table="order-table" placeholder="Isikan Nama Menu..  "
                        title="Cari Menu...."/>
              </fieldset>
            </div>
          </div>
          <div  id="lblockcontent2">
              <div class="paneltable">
    						<div class="table">
    								<?php
                      $kondisi = 1;
                      $i=1;
                      echo '<div id="intone" class="tabledishes">';
                      echo "<div id='label_app'></div>";
                      while($qtabel=mysqli_fetch_assoc($queryapp)){
                        echo '<div class="tabledata'.$kondisi.'">';
                        $nama= '"'.$qtabel[nama_hidangan].'"';
                        echo '<table id="table'.$qtabel["no_hidangan"].'" class="order-table">';
                        echo "  <tbody>";
    										echo "    <tr onclick='pin_hidangan($qtabel[no_hidangan],$nama,$qtabel[harga_hidangan],0)' title='Klik untuk menyimpan ke list pesanan'>";
  											echo "         <td style='display:none'>$qtabel[no_hidangan]</td>
                                       <td style='display:none'><input type='checkbox' id='check$qtabel[no_hidangan]'></td>";
                        echo '         <td><div id="pin'.$qtabel["no_hidangan"].'" class="pinit"></div><div class="panelgbr"><div style=display:none>'.$qtabel[nama_hidangan].$qtabel[harga_hidangan].'</div><img src="data:image/jpeg;base64,'.base64_encode($qtabel[image]).'">
                                       </div>
                                       <tr><td><td><td><div id="wrapinfo'.$qtabel[no_hidangan].'" class="wrapinfo"><div id="panelinfo'.$qtabel[no_hidangan].'" class="panelinfo"><label id="nama'.$qtabel[no_hidangan].'" class="nama">'.$qtabel[nama_hidangan].'</label><br><label id="harga'.$qtabel[no_hidangan].'" class="harga">Rp.'.$qtabel[harga_hidangan].'</label></div>
                                       <div class="paneljumlah"><input type="text" id="jumlah'.$qtabel[no_hidangan].'" onkeyup="return tempeljumlah(this,'.$qtabel[no_hidangan].','.$qtabel[harga_hidangan].')" placeholder="JUMLAH" maxlength="2" title="Jumlah pesan" disabled></div></div>
                                       </td>';
    										echo "		</tr>";
      									echo "  </tbody>
                              </table>
                              </div>";
                        $kondisi++;
                        $i++;
                        if($kondisi == 4){
                          $kondisi = 1;
                        }
                      }
                      echo "</div>";
                      $kondisi = 1;
                      echo '<div id="inttwo" class="tabledishes">';
                      echo "<div id='label_mac'></div>";
                      while($qtabel=mysqli_fetch_assoc($querymac)){
                        echo '<div class="tabledata'.$kondisi.'">';
                        $nama= '"'.$qtabel[nama_hidangan].'"';
                        echo '<table id="table'.$qtabel["no_hidangan"].'" class="order-table">';
                        echo "  <tbody>";
    										echo "    <tr onclick='pin_hidangan($qtabel[no_hidangan],$nama,$qtabel[harga_hidangan])' title='Klik untuk menyimpan ke list pesanan'>";
  											echo "         <td style='display:none'>$qtabel[no_hidangan]</td>
                                       <td style='display:none'><input type='checkbox' id='check$qtabel[no_hidangan]'></td>";
                        echo '         <td><div id="pin'.$qtabel["no_hidangan"].'" class="pinit"></div><div class="panelgbr"><div style=display:none>'.$qtabel[nama_hidangan].$qtabel[harga_hidangan].'</div><img src="data:image/jpeg;base64,'.base64_encode($qtabel[image]).'">
                                       </div>
                                       <tr><td><td><td><div id="wrapinfo'.$qtabel[no_hidangan].'" class="wrapinfo"><div id="panelinfo'.$qtabel[no_hidangan].'" class="panelinfo"><label id="nama'.$qtabel[no_hidangan].'" class="nama">'.$qtabel[nama_hidangan].'</label><br><label id="harga'.$qtabel[no_hidangan].'" class="harga">Rp.'.$qtabel[harga_hidangan].'</label></div>
                                       <div class="paneljumlah"><input type="text" id="jumlah'.$qtabel[no_hidangan].'" onkeyup="return tempeljumlah(this,'.$qtabel[no_hidangan].','.$qtabel[harga_hidangan].')" placeholder="JUMLAH" maxlength="2" disabled></div></div>
                                       </td>';
    										echo "		</tr>";
      									echo "  </tbody>
                              </table>
                              </div>";
                        $kondisi++;
                        $i++;
                        if($kondisi == 4){
                          $kondisi = 1;
                        }
                      }
                      echo "</div>";
                      $kondisi = 1;
                      echo '<div id="intthree" class="tabledishes">';
                      echo "<div id='label_des'></div>";
                      while($qtabel=mysqli_fetch_assoc($querydes)){
                        echo '<div class="tabledata'.$kondisi.'">';
                        $nama= '"'.$qtabel[nama_hidangan].'"';
                        echo '<table id="table'.$qtabel["no_hidangan"].'" class="order-table">';
                        echo "  <tbody>";
    										echo "    <tr onclick='pin_hidangan($qtabel[no_hidangan],$nama,$qtabel[harga_hidangan])' title='Klik untuk menyimpan ke list pesanan'>";
  											echo "         <td style='display:none'>$qtabel[no_hidangan]</td>
                                       <td style='display:none'><input type='checkbox' id='check$qtabel[no_hidangan]'></td>";
                        echo '         <td><div id="pin'.$qtabel["no_hidangan"].'" class="pinit"></div><div class="panelgbr"><div style=display:none>'.$qtabel[nama_hidangan].$qtabel[harga_hidangan].'</div><img src="data:image/jpeg;base64,'.base64_encode($qtabel[image]).'">
                                       </div>
                                       <tr><td><td><td><div id="wrapinfo'.$qtabel[no_hidangan].'" class="wrapinfo"><div id="panelinfo'.$qtabel[no_hidangan].'" class="panelinfo"><label id="nama'.$qtabel[no_hidangan].'" class="nama">'.$qtabel[nama_hidangan].'</label><br><label id="harga'.$qtabel[no_hidangan].'" class="harga">Rp.'.$qtabel[harga_hidangan].'</label></div>
                                       <div class="paneljumlah"><input type="text" id="jumlah'.$qtabel[no_hidangan].'" onkeyup="return tempeljumlah(this,'.$qtabel[no_hidangan].','.$qtabel[harga_hidangan].')" placeholder="JUMLAH" maxlength="2" disabled></div></div>
                                       </td>';
    										echo "		</tr>";
      									echo "  </tbody>
                              </table>
                              </div>";
                        $kondisi++;
                        $i++;
                        if($kondisi == 4){
                          $kondisi = 1;
                        }
                      }
                      echo "</div>";
                      $kondisi = 1;
                      echo '<div id="intfour" class="tabledishes">';
                      echo "<div id='label_sd'></div>";
                      while($qtabel=mysqli_fetch_assoc($querysd)){
                        echo '<div class="tabledata'.$kondisi.'">';
                        $nama= '"'.$qtabel[nama_hidangan].'"';
                        echo '<table id="table'.$qtabel["no_hidangan"].'" class="order-table">';
                        echo "  <tbody>";
    										echo "    <tr onclick='pin_hidangan($qtabel[no_hidangan],$nama,$qtabel[harga_hidangan])' title='Klik untuk menyimpan ke list pesanan'>";
  											echo "         <td style='display:none'>$qtabel[no_hidangan]</td>
                                       <td style='display:none'><input type='checkbox' id='check$qtabel[no_hidangan]'></td>";
                        echo '         <td><div id="pin'.$qtabel["no_hidangan"].'" class="pinit"></div><div class="panelgbr"><div style=display:none>'.$qtabel[nama_hidangan].$qtabel[harga_hidangan].'</div><img src="data:image/jpeg;base64,'.base64_encode($qtabel[image]).'">
                                       </div>
                                       <tr><td><td><td><div id="wrapinfo'.$qtabel[no_hidangan].'" class="wrapinfo"><div id="panelinfo'.$qtabel[no_hidangan].'" class="panelinfo"><label id="nama'.$qtabel[no_hidangan].'" class="nama">'.$qtabel[nama_hidangan].'</label><br><label id="harga'.$qtabel[no_hidangan].'" class="harga">Rp.'.$qtabel[harga_hidangan].'</label></div>
                                       <div class="paneljumlah"><input type="text" id="jumlah'.$qtabel[no_hidangan].'" onkeyup="return tempeljumlah(this,'.$qtabel[no_hidangan].','.$qtabel[harga_hidangan].')" placeholder="JUMLAH" maxlength="2" disabled></div></div>
                                       </td>';
    										echo "		</tr>";
      									echo "  </tbody>
                              </table>
                              </div>";
                        $kondisi++;
                        $i++;
                        if($kondisi == 4){
                          $kondisi = 1;
                        }
                      }
                      echo "</div>";
    								?>
    						</div>
    					</div>
          </div>
          <!-- END LBLOCKCONTENT1 -->
        </div>
        <!-- END LEFT -->
        <!-- right -->
        <div id="right">
          <div id="listdipesan" class="rblockcontent1">
              <div><label>Hidangan Telah Dipesan</label><hr></div>
              <table class="tablepesan">
                <thead>
                  <tr>
                    <th></th>
                    <th></th>
                    <th>HIDANGAN</th>
                    <th>JUM</th>
                    <th>HRG</th>
                    <th>TOTAL</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if($querytrans){
                      while($data = mysqli_fetch_assoc($querytrans)){
                        echo "<tr>
                                <td></td>
                                <td></td>
                                <td>$data[nama_hidangan]</td>
                                <td>$data[jum_item]</td>
                                <td>$data[harga]</td>
                                <td>$data[total]</td>
                              </tr>";
                      }
                    }
                  ?>
                </tbody>
              </table>
          </div>
          <div id="listpesan" class="rblockcontent1">
            <div>
              <div><label>Daftar Pesanan Baru</label><hr></div>
              <table id="tablepesanan" class="tablepesan">
                <thead>
                  <tr>
                    <th style="display:none"></th>
                    <th></th>
                    <th><input type="checkbox" id="checkmaster" onclick="checkall()"></th>
                    <th  onclick="checkall1()">HIDANGAN</th>
                    <th  onclick="checkall1()">JUM</th>
                    <th  onclick="checkall1()">HRG</th>
                    <th  onclick="checkall1()">TOTAL</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div class="orderoption">
            <div class="panelbtn">
              <input id="tbl_hapus_list" name="tbl_hapus_list" onclick="hapusdatalist(<?php echo $tot_bayar ?>)" type="button" value="" title="Menghapus data terseleksi pada list pesanan" disabled>
              <input id="tbl_order_list" onclick="orderdatalist()" name="tbl_order_list" type="button" title="Memesan hidangan dari data list pesanan" disabled>
            </div>
            <div id="panelbayar">
              <input type="text" id="totalbayar" name="totalbayar" placeholder="Rp. 0" value="Rp. 0" readonly>
              <input type="button" id="tbl_bayar" name="tbl_bayar" onclick="statusbayar()" value="Bayar" disabled>
            </div>
          </div>
        </div>
        <!-- END right -->
      </div>
      <!-- END MAINBODY -->
      <input type="hidden" id="temp" name="temp" value="0" readonly>
      <input type="hidden" id="temp1" name="temp1" readonly>
      <input type="hidden" id="temp2" name="temp2" value="0" readonly>
      <input type="hidden" id="temp4" name="temp4" value="<?php echo "$_SESSION[aktifbayar]" ?>" readonly>
    </form>
    <footer></footer>
    <script>
      var statusaktif = "<?php echo "$_SESSION[aktifbayar]" ?>";
      var statuspopup = "<?php echo "$_SESSION[status_popup]" ?>";
      var dispsmile = "<?php echo $displaysmile ?>";
      if(statusaktif=="1"){
        document.getElementById("tbl_bayar").disabled = false;
        document.getElementById("totalbayar").value = "Rp. <?php echo "$_SESSION[total_bayar]" ?>";
        document.getElementById("temp2").value = "<?php echo "$_SESSION[total_bayar]" ?>";
        document.getElementById("listpesan").style.height = "60%";
        document.getElementById("listdipesan").style.display = "block";
      }
      if(statuspopup=="1"){
        document.getElementById("popup").style.display = "block";
        document.getElementById("tbl_bayar").disabled = true;
        document.getElementById("totalbayar").value = "Rp. <?php echo "$_SESSION[total_bayar]" ?>";
        document.getElementById("temp2").value = "<?php echo "$_SESSION[total_bayar]" ?>";
        document.getElementById("temp4").value = "<?php echo "$_SESSION[status_popup]" ?>";
      }
      if(dispsmile==1){
        document.getElementById("popup").style.display = "block";
        document.getElementById("emotsmile").style.display = "block";
        document.getElementById("contenttext").style.display = "none";
      }
    </script>
  </body>
</html>
