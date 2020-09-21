<?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  if($_SESSION['pelanggan']){
		header('Location: pelanggan.php');
	}

	if($_SESSION['jabatan']!="KOKI" || !isset($_SESSION['nip'])){
		header('Location: admin.php');
	}

  include_once("koneksi.php");

  if($_POST["logout"]){
    header("Location: logout.php");
  }

  if($_POST["koki"]){
    header("Location: hidangan.php");
  }
?>

<html>
  <head>
    <title>Resto Bro | Data Pesanan</title>
  </head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/pemesanan.css" type="text/css">
  <link rel="stylesheet" href="css/sweetalert.css" type="text/css">
  <script src="javascript/jquery.js"></script>
  <script type="text/javascript" async="" src="javascript/filter.js"></script>
  <script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>
  <meta charset="utf-8">
  <body>
    <form method="POST">
      <header>
        <label class="judul">Data Pesanan - Resto Bro</label>
        <aside id="panelkoki" class="panelhead">
            <input class="sign" id="koki" name="koki" onclick="kokiopen()" type="button" value="Kembali >">
        </aside>
      </header>
      <div id="bodycontent">
        <div id="leftblock">
          <div id="leftblockchild1">
            Data Pemesanan
          </div>
          <div id="leftblockchild2">
            <div id="tablepesan" class="table">

            </div>
          </div>
        </div>
        <div id="rightblock">
          <div id="rightblockchild1">
            Data Detail Pemesanan
          </div>
          <div id="rightblockchild2">
            <div id="tabledetail" class="table">
              <table>
                <tr>
                  <th>Nomor Pesan</th>
                  <th>Nama Hidangan</th>
                  <th>Jumlah</th>
                  <th>Aksi</th>
                </tr>
              </table>
              <div id="loader"></div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
  <script>
    function cekpesanan(){
      $("#tablepesan").load("lib/tampilpesanankoki.php");
    }
    cekpesanan();
    setInterval("cekpesanan()",5000);

    function cekdetail(nomor){
      $.ajax({
        url         : "lib/tampildetailpesanankoki.php",
        type        : 'POST',
        data        : 'nomor='+nomor,
        beforeSend  : function(){
            $("#loader").html("Loading ...");
          },
        success:function(data){
          $("#loader").hide();
          $("#tabledetail").html(data);
        }
      });
    }

    function dataselesai(nomor,nohid){
      $.ajax({
        url         : "lib/hidanganselesai.php",
        type        : 'POST',
        data        : 'nomor='+nomor+'&no_hidangan='+nohid,
        success:function(data){
          cekpesanan();
          cekdetail(nomor);
        }
      });
    }

    function log_out(){
      document.getElementById("logout").type="submit";
      document.getElementById("logout").submit();
    }

    function kokiopen(){
      document.getElementById("koki").type="submit";
      document.getElementById("koki").submit();
    }
  </script>
</html>
