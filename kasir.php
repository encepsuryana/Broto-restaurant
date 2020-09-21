<?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  include_once("koneksi.php");

  if($_SESSION['pelanggan']){
		header('Location: pelanggan.php');
	}

  if($_SESSION['jabatan']!="KASIR" || !isset($_SESSION['nip'])){
    header('Location: admin.php');
  }

  if($_POST["logout"]){
    header("Location: logout.php");
  }

  if($_POST["batal"]){
    header("Location: kasir.php");
  }

  if($_POST["proses"]){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $sql = "UPDATE t_pemesanan SET status_bayar = 2 WHERE no_pemesanan = $_POST[temp]";
    $query = mysqli_query($dbcon,$sql);
    if($query){
      mysqli_commit($dbcon);
      $tipests = "success";
      $labelsts = "Berhasil";
      $messagests = 1;
      $message = "Data pemesanan disimpan";
    }else{
      mysqli_rollback($dbcon);
      $tipests = "error";
      $labelsts = "Error";
      $messagests = 1;
      $message = mysqli_errno($dbcon)." : ".mysqli_error($dbcon);
    }
  }

  mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
  $sql = "SELECT no_pemesanan, no_meja, total FROM t_pemesanan";
  $querypesan = mysqli_query($dbcon,$sql);
  if($querpesan){
    mysqli_commit($dbcon);
  }else{
    mysqli_rollback($dbcon);
  }
?>

<html>
  <head>
    <title>Panel Kasir - Resto Bro</title>
  </head>
  <meta charset="utf-8">
  <script type="text/javascript" async="" src="javascript/proseskasir.js"></script>
  <link rel="stylesheet" type="text/css" href="css/kasir.css">
  <link rel="stylesheet" href="css/sweetalert.css" type="text/css">
	<script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>
  <script type="text/javascript" src="javascript/jquery.js"></script>
  <body>
    <form method="POST">
      <section>
        <div class="block2">
          <div class="block2child1">
            <label>Data Pelanggan</label>
          </div>
          <div class="block2child2">
            <div id="tablelist" class="table">
            </div>
         </div>
        </div>
          <div class="block1">
            <div id="panelinfo">
             <aside class="panellogout">
                 <input type="button" class="sign" onclick="log_out()" id="logout" name="logout" value="Keluar">
             </aside>
              <input type="text" id="nopesan" placeholder="Nomor Pesan" readonly>
              <input type="text" id="nomeja" placeholder="Nomor Meja" readonly>
            </div>

            <div class="panelhitung">
              <div>
                <legend>Pembayaran</legend>
                <input type="text" id="uangbayar" name="uangbayar" placeholder="Rp.0" onkeyup="return validasi(this)" min=0>
              </div>
              <div>
                <legend>Total</legend>
                <input type="text" id="belanja" name="belanja" value="Rp.0" readOnly>
              </div>
              <div>
                <legend>Kembalian</legend>
                <input type="text" id="kembalian" name="kembalian" value="Rp.0" readOnly>
              </div>
            </div>
            <div id="paneltabelpesanan">
              <div id="tablehidangan" class="table">
                <table>
                  <tr>
                    <th>Hidangan</th>
                    <th>Jumlah Pesan</th>
                    <th>Harga Satuan</th>
                    <th>Harga Total</th>
                  </tr>
                </table>
                <div id="loader"></div>
              </div>
            </div>
            <div id="panelolah">
              <input type="button" onclick="prosessimpan()" value="Proses" id="proses" name="proses" disabled>
              <input type="button" onclick="batalkan()" value="Batal" id="batal" name="batal">
            </div>
          </div>
      </section>
      <input type="hidden" id="temp" name="temp">
    </form>
    <?php
      if($_POST["proses"]){
         if($messagests==1){
          echo "<script>
                   window.onload = function(){
                     swal({
                       title:'$labelsts',
                       text: '$message',
                       type: '$tipests',
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
                     title: '$labelsts',";
          echo '     text: "'.$message.'",';
          echo "     type: '$tipests',
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
  <script>
    function cekpesanan(){
      $("#tablelist").load("lib/tampilpesanan.php");
    }
    cekpesanan();
    setInterval("cekpesanan()",5000);

    function hidanganpesan(nomor){
      document.getElementById("temp").value=nomor;
      $.ajax({
        url         : "lib/tampildetailpesan.php",
        type        : 'POST',
        data        : 'nomor='+nomor,
        beforeSend  : function(){
            $("#loader").html("Loading ...");
          },
        success:function(data){
          $("#loader").hide();
          $("#tablehidangan").html(data);
        }
      });
    }
  </script>
</html>
