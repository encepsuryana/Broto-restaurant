<?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  if($_SESSION['pelanggan']){
    header("Location: pelanggan.php");
  }

  if(($_SESSION['jabatan']!="PANTRY") || (!isset($_SESSION['nip']))){
    header('Location: admin.php');
  }

  if($_POST['logout']){
    header("Location: logout.php");
  }

  if($_POST['batal']){
    $status_batas = null;
  }

  include_once("koneksi.php");

  if($_POST['newlapbtn']){
    $status_batas = "3";
  }

  if($_POST['simpan_data_baru']){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $kode_lap = strtoupper($_POST[kodelaporan]);
    $sqlnew = "INSERT INTO t_laporan_belanja VALUES ('$kode_lap',
                   '$_POST[buat]',$_POST[anggaran],$_POST[belanja],'$_SESSION[nip]')";
    $querynew = mysqli_query($dbcon,$sqlnew);
    if($querynew){
      echo "<script>sleep(300)</script>";
      for($i=1;$i<$_POST['baris'];$i++){
        $input = '("'.$_POST["kodelaporan"].'",'.$_POST["nomors($i)"].','.$_POST["jumitems($i)"].','.$_POST["hrgsatuans($i)"].','.$_POST["hrgtotals($i)"].',"'.$_POST["keterangans($i)"].'")';
        $sqldetail = "INSERT INTO t_detail_lap_belanja VALUES $input";
        $querydetail = mysqli_query($dbcon,$sqldetail);
      }
      if($querydetail){
        mysqli_commit($dbcon);
        $tipests = "success";
        $labelsts = "Berhasil";
        $messagests = 1;
        $message = "Data berhasil disimpan";
      }
    }else{
      mysqli_rollback($dbcon);
      $tipests = "error";
      $labelsts = "Gagal";
      $messagests = 0;
      $message = "Data tidak berhasil disimpan";
    }
  }

  if($_POST['tbl_simpan']){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $kodelap = strtoupper($_POST['kodelaporan']);
    $sqllaporan = "UPDATE t_laporan_belanja SET kode_lap_belanja = '$kodelap',
                   tanggal = '$_POST[buat]', budget = $_POST[anggaran], pengeluaran = $_POST[belanja],
                   NIP = '$_SESSION[nip]' WHERE kode_lap_belanja = '$_POST[kodelap]'";
    $querylaporan = mysqli_query($dbcon,$sqllaporan);
    if($querylaporan){
      $sqllaporan = "DELETE FROM t_detail_lap_belanja WHERE kode_lap_belanja = '$_POST[kodelap]'";
      $querydeletelap = mysqli_query($dbcon,$sqllaporan);
      if($querydeletelap){
        for($i=1;$i<$_POST['baris'];$i++){
          $input = '("'.$kodelap.'",'.$_POST["nomors($i)"].','.$_POST["jumitems($i)"].','.$_POST["hrgsatuans($i)"].','.$_POST["hrgtotals($i)"].',"'.strtoupper($_POST["keterangans($i)"]).'")';
          $sqldetail = "INSERT INTO t_detail_lap_belanja VALUES $input";
          $querydetail = mysqli_query($dbcon,$sqldetail);
        }
        if($querydetail){
          mysqli_commit($dbcon);
          $message = "Data berhasil disimpan";
          $tipests = "success";
          $labelsts = "Berhasil";
          $messagests = 1;
        }
      }
    }else{
      mysqli_rollback($dbcon);
      $tipests = "error";
      $labelsts = "Gagal";
      $messagests = 0;
      $message = "Data tidak berhasil disimpan";
    }
  }

  if($_POST["tbldelete"]){
    $kodelap = strtoupper($_POST['kodelap']);
    $sqldel = "DELETE FROM t_laporan_belanja WHERE kode_lap_belanja = '$kodelap'";
    $querydel = mysqli_query($dbcon,$sqldel);
    if($querydel){
      mysqli_commit($dbcon);
      $message = "Data berhasil dihapus";
      $tipests = "success";
      $labelsts = "Berhasil";
      $messagests = 1;
    }else {
      mysqli_rollback($dbcon);
      $tipests = "error";
      $labelsts = "Gagal";
      $messagests = 0;
      $message = "Data gagal dihapus";
    }
  }

  if($_POST['tbh_bts_bhn']){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $nobahan = $_POST['no_bhn'];
    $tgl_beli = $_POST['tgl_beli'];
    $tgl_produksi = $_POST['tgl_produksi'];
    $tgl_kadaluarsa = $_POST['tgl_kadaluarsa'];
    $jum_batas = $_POST['jum_batas'];
    $ket = strtoupper($_POST['ket_batas']);
    $sql = "INSERT INTO t_batas_pakai (no_bahan,tgl_beli,tgl_produksi,tgl_kadaluarsa,
            jumlah, keterangan) VALUES ($nobahan,'$tgl_beli','$tgl_produksi',
            '$tgl_kadaluarsa',$jum_batas,'$ket')";
    $queryadd = mysqli_query($dbcon,$sql);
    if($queryadd){
      mysqli_commit($dbcon);
      $tipests = "success";
      $labelsts = "Berhasil";
      $messagests = 1;
      $message = "Data berhasil disimpan";
    }else{
      mysqli_rollback($dbcon);
      $tipests = "error";
      $labelsts = "Gagal";
      $messagests = 0;
      $message = "Data tidak berhasil disimpan";
    }
  }

  if($_POST['ubh_bts_bhn']){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $nobahan = $_POST['no_bhn'];
    $tgl_beli = $_POST['tgl_beli'];
    $noreg = $_POST['no_reg'];
    $tgl_produksi = $_POST['tgl_produksi'];
    $tgl_kadaluarsa = $_POST['tgl_kadaluarsa'];
    $jum_batas = $_POST['jum_batas'];
    $ket = strtoupper($_POST['ket_batas']);
    $sql = "UPDATE t_batas_pakai SET tgl_beli='$tgl_beli',tgl_produksi='$tgl_produksi',
            tgl_kadaluarsa='$tgl_kadaluarsa',jumlah=$jum_batas,keterangan='$ket' WHERE no_bahan=$nobahan AND no_reg = $noreg";
    $querychange = mysqli_query($dbcon,$sql);
    if($querychange){
      mysqli_commit($dbcon);
      $tipests = "success";
      $labelsts = "Berhasil";
      $messagests = 1;
      $message = "Data berhasil diubah";
    }else{
      mysqli_rollback($dbcon);
      $tipests = "error";
      $labelsts = "Gagal";
      $messagests = 0;
      $message = "Data tidak berhasil diubah";
    }
  }

  if($_POST['hps_bts_bhn']){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $nobahan = $_POST['no_bhn'];
    $noreg = $_POST['no_reg'];
    $sql = "DELETE FROM t_batas_pakai WHERE no_bahan = $nobahan AND no_reg = $noreg";
    $querydelete = mysqli_query($dbcon,$sql);
    if($querydelete){
      mysqli_commit($dbcon);
      $message = "Data berhasil dihapus";
      $tipests = "success";
      $labelsts = "Berhasil";
      $messagests = 1;
    }else{
      mysqli_rollback($dbcon);
      $tipests = "error";
      $labelsts = "Gagal";
      $messagests = 0;
      $message = "Data tidak berhasil dihapus";
    }
  }

  if(($_POST['detail_lap'])||($_POST['newlapbtn'])){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    if($_POST['detail_lap']){
      $kode = "$_POST[textdetail1]";
      $tgl = "$_POST[textdetail2]";
      $anggaran = "$_POST[textdetail3]";
      $belanja = "$_POST[textdetail4]";
      $sqlbahan = "SELECT no_bahan, nama_bahan FROM t_bahan_baku";
      $querybahan = mysqli_query($dbcon,$sqlbahan);
      $sql = "SELECT a.no_bahan, nama_bahan, a.jumlah, harga_satuan,
              harga_total, a.keterangan FROM t_detail_lap_belanja a JOIN
              t_bahan_baku b ON a.no_bahan = b.no_bahan WHERE a.kode_lap_belanja = '$kode'";
      $querydetail = mysqli_query($dbcon,$sql);
      if($querydetail){
        mysqli_commit($dbcon);
        $messagepop = "Detail Belanja dengan Kode Laporan '$kode'";
        $status_batas = "3";
      }else{
        mysqli_rollback($dbcon);
      }
    }else if($_POST['newlapbtn']){
      $sqlbahan = "SELECT no_bahan, nama_bahan FROM t_bahan_baku";
      $querybahan = mysqli_query($dbcon,$sqlbahan);
      $status_batas = "3";
      $messagepop = "Buat Laporan Baru";
    }
  }

  if(($_POST['kelola_laporan'])||($_POST['home'])||
     ($_POST['tbl_simpan'])||($_POST['simpan_data_baru'])||
     ($_POST['tbl_batal'])||($_POST['tbldelete'])){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $sql = "SELECT kode_lap_belanja,tanggal,budget,pengeluaran,nama FROM t_laporan_belanja a
            JOIN t_pegawai b ON a.NIP = b.NIP";
    $querylaporan = mysqli_query($dbcon,$sql);
    if($querylaporan){
      mysqli_commit($dbcon);
      $messagepop = "Seluruh Laporan yang Pernah dibuat";
      $status_batas = "3";
    }else{
      mysqli_rollback($dbcon);
    }
  }

  if($_POST['cari_hidangan']){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
    $namabahan = strtoupper($_POST['cari_nama_hidangan']);
    $sql = "SELECT a.no_hidangan,nama_hidangan, b.jumlah, b.keterangan, a.status FROM (t_hidangan a JOIN t_resep b) JOIN t_bahan_baku c ON a.no_hidangan = b.no_hidangan AND b.no_bahan = c.no_bahan
            WHERE c.nama_bahan = '$namabahan'";
    $querycari = mysqli_query($dbcon,$sql);
    if($querycari){
      mysqli_commit($dbcon);
      $status_batas = "2";
    }else{
      mysqli_rollback($dbcon);
    }
  }

  if(($_POST['aktifkan'])||($_POST['nonaktifkan'])){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $nomor = $_POST['temp_no_hidangan'];
    $batas = strlen($nomor);
    $no = null;
    $gagal = false;
    for($i=0;$i<$batas;$i++){
      if(substr($nomor,$i,1)=="#"){
        if($_POST['aktifkan']){
          $sql = "UPDATE t_hidangan SET status=1 WHERE no_hidangan=$no";
        }else{
          $sql = "UPDATE t_hidangan SET status=0 WHERE no_hidangan=$no";
        }
        $query = mysqli_query($dbcon,$sql);
        $no = "";
        if(!$query){
          mysqli_rollback($dbcon);
          $message = "Data tidak berhasil disimpan";
          $tipests = "error";
          $labelsts = "Gagal";
          $messagests = 0;
          $gagal = true;
          break;
        }
      }else{
        $no = $no.substr($nomor,$i,1);
      }
    }
    if(!$gagal){
      mysqli_commit($dbcon);
      $tipests = "success";
      $labelsts = "Berhasil";
      $messagests = 1;
      $message = "Data berhasil disimpan";
    }
  }

  if(($_POST['atur_hidangan'])||($_POST['prosesselect'])||($_POST['aktifkan'])||($_POST['nonaktifkan'])){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
    $kondisipop = $_POST['kondisipop'];
    if($_POST['kondisipop'] == "Hidangan Aktif"){
      $sql = "SELECT no_hidangan, nama_hidangan,status FROM t_hidangan WHERE status=1";
    }else if($_POST['kondisipop'] == "Hidangan Tidak Aktif"){
      $sql = "SELECT no_hidangan, nama_hidangan,status FROM t_hidangan WHERE status=0";
    }else {
      $sql = "SELECT no_hidangan, nama_hidangan,status FROM t_hidangan";
    }
    $queryhidangan = mysqli_query($dbcon,$sql);
    if($queryhidangan){
      mysqli_commit($dbcon);
      $status_batas = "2";
    }else{
      mysqli_rollback($dbcon);
    }
  }

  if($_POST['simpan']){
    mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
    $nomor = $_POST['temp_no_tabel'];
    $batas = strlen($nomor);
    $no = null;
    $gagal = false;
    for($i=0;$i<$batas;$i++){
      if(substr($nomor,$i,1)=="#"){
        $jum = $_POST["jum($no)"];
        $ket = strtoupper($_POST["ket($no)"]);
        $sql = "UPDATE t_bahan_baku SET jumlah=$jum, keterangan='$ket' WHERE no_bahan=$no";
        $query = mysqli_query($dbcon,$sql);
        $no = "";
        if(!$query){
          mysqli_rollback($dbcon);
          $tipests = "error";
          $labelsts = "Gagal";
          $messagests = 0;
          $message = "Data tidak berhasil disimpan";
          $gagal = true;
          break;
        }
      }else{
        $no = $no.substr($nomor,$i,1);
      }
    }
    if(!$gagal){
      mysqli_commit($dbcon);
      $tipests = "success";
      $labelsts = "Berhasil";
      $messagests = 1;
      $message = "Data berhasil disimpan";
    }
  }

  if(($_POST["tbl"])||($_POST["tbh_bts_bhn"])||($_POST["ubh_bts_bhn"])||($_POST["hps_bts_bhn"])){
    $status_batas = "1";
    $namabahan = $_POST['temp_nama_bahan'];
    if($_POST["tbl"]){
      $nobahan = $_POST['temp_no_tabel_batas'];
    }
    $sqlbatas = "SELECT * FROM t_batas_pakai WHERE no_bahan = $nobahan";
    $querybatas = mysqli_query($dbcon,$sqlbatas);
  }

  //Validasi keadaan table dan menampilkan daftar bahan baku
  $sql    = "SELECT * FROM t_bahan_baku";
  $query    = mysqli_query($dbcon, $sql);
?>

<html>
  <head>
    <title>Resto Bro | Pantry</title>
  </head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/pantry.css" type="text/css">
  <script type="text/javascript" async="" src="javascript/filter.js"></script>
  <script type="text/javascript" async="" src="javascript/prosespantry.js"></script>
  <link rel="stylesheet" href="css/sweetalert.css" type="text/css">
  <script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.css">
  <script src="javascript/jquery.js"></script>
  <script src="javascript/jquery-ui.js"></script>
  <script>
    $(function() {
      $(".datepicker").datepicker();
    });
  </script>
  <script>
    function ubahdata(){
      var id = document.getElementById('bahandata').value;
      if(id!=""){
        var numb = document.getElementById('ubahbahan').value;
        var tbl = document.getElementById('tabledetail');
        var jumrow = tbl.rows.length;
        var length = id.length;
        var i = 0;
        var nama = "";
        while(nama!='#'){
          nama = id.substring(i,i+1);
          i++;
        }
        no = id.substring(0,i-1);
        nama = id.substring(i,length);
        sama = false;
        for(i = 1;i<=jumrow-1;i++){
          if(document.getElementById('nomors('+i+')').value==no){
            sama = true;
            break;
          }
        }
        if(sama){
          swal({
            title: "Info",
            text: "Data bahan baku telah terdaftar pada tabel belanja",
            type: "info",
            confirmButtonColor: "#2b5dcd",
            confirmButtonText: "OK",
            closeOnConfirm: true
          });
        }else{
          document.getElementById('namabahans('+numb+')').value=nama;
          document.getElementById('nomors('+numb+')').value= ''+no;
        }
      }else{
        swal({
          title: "Info",
          text: "Tidak ada data bahan baku dipilih",
          type: "info",
          confirmButtonColor: "#2b5dcd",
          confirmButtonText: "OK",
          closeOnConfirm: true
        });
      }
    }

    function addNewRow(stats,nobahan,nama) {
      var tbl = document.getElementById("tabledetail");
      var jumrow = tbl.rows.length;
      var sama = false;
      var bahan = document.getElementById('bahandata').value;
      if((stats!=1)&&(bahan!="")){
        var id = bahan;
        var length = id.length;
        var i = 0;
        var nama = "";
        while(nama!='#'){
          nama = id.substring(i,i+1);
          i++;
        }
        nama = id.substring(i,length);
        nobahan = id.substring(0,i-1);
        for(var i = 1;i<=jumrow-1;i++){
          if(document.getElementById('nomors('+i+')').value==nobahan){
            sama = true;
            break;
          }
        }
      }

      if(((bahan!="")||(stats==1))&&(!sama)){
        var row = tbl.insertRow(tbl.rows.length);
        var td = new Array(document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"));
        td[0].appendChild(generateIndex(row.rowIndex,nobahan));
        td[1].appendChild(generateCheckBox(row.rowIndex));
        td[2].appendChild(generateNamaBahan(row.rowIndex));
        td[3].appendChild(generateJumItem(row.rowIndex));
        td[4].appendChild(generateHarga(row.rowIndex));
        td[5].appendChild(generateTotal(row.rowIndex));
        td[6].appendChild(generateKet(row.rowIndex));
        row.appendChild(td[0]);row.appendChild(td[1]);
        row.appendChild(td[2]);row.appendChild(td[3]);
        row.appendChild(td[4]);row.appendChild(td[5]);
        row.appendChild(td[6]);
        if(stats!=1){
          document.getElementById("namabahans("+row.rowIndex+")").value = nama;
        }
      }else{
        if(sama){
          swal({
            title: "Info",
            text: "Data bahan baku telah terdaftar pada tabel belanja",
            type: "info",
            confirmButtonColor: "#2b5dcd",
            confirmButtonText: "OK",
            closeOnConfirm: true
          });
        }else{
          swal({
            title: "Info",
            text: "Tidak ada data bahan baku dipilih",
            type: "info",
            confirmButtonColor: "#2b5dcd",
            confirmButtonText: "OK",
            closeOnConfirm: true
          });
        }
      }
    }

    function generateIndex(index,no_bahan) {
      var idx   = document.createElement("input");
      idx.type  = "hidden";
      idx.name  = "nomors("+index+")";
      idx.id    = "nomors("+index+")";
      idx.value = ""+no_bahan+"";
      return idx;
    }

    function generateCheckBox(index) {
      var check  = document.createElement("input");
      check.type = "checkbox";
      check.name = "checks("+index+")";
      check.id   = "checks("+index+")";
      return check;
    }

    function generateNamaBahan(index,val) {
      var idx  = document.createElement("input");
      idx.type = "text";
      idx.name = "namabahans("+index+")";
      idx.id   = "namabahans("+index+")";
      idx.title = "Klik untuk melakukan perubahan bahan baku";
      idx.onclick = function(){
        document.getElementById('bahandata').value=document.getElementById("nomors("+index+")").value+'#'+document.getElementById("namabahans("+index+")").value;
        document.getElementById('ubahbahan').value = index;
      };
      if(val!=null){
        idx.value = val;
      }else{
        idx.value = "";
      }
      idx.readOnly = "readOnly";
      return idx;
    }

    function generateJumItem(index,val) {
      var idx  = document.createElement("input");
      idx.type = "text";
      idx.name = "jumitems("+index+")";
      idx.id   = "jumitems("+index+")";
      idx.onkeyup = function (){
        var jum = parseInt(document.getElementById('jumitems('+index+')').value);
        var angkasatu = parseInt(document.getElementById('hrgsatuans('+index+')').value);
        if(document.getElementById('jumitems('+index+')').value==""){
          document.getElementById('jumitems('+index+')').value = 0;
        }else{
          document.getElementById('jumitems('+index+')').value = jum;
        }
        document.getElementById('hrgtotals('+index+')').value = angkasatu*jum;
        if(isNaN(document.getElementById('hrgtotals('+index+')').value)){
          document.getElementById('hrgtotals('+index+')').value = 0;
        }
        var tbl = document.getElementById('tabledetail');
        var jumrow = tbl.rows.length;
        var hasil = 0;
        for(var i=1;i<jumrow;i++){
          hasil = parseInt(hasil) + parseInt(document.getElementById('hrgtotals('+i+')').value);
        }
        document.getElementById('belanja').value = hasil;
        this.value = this.value.replace(/[^0-9]/g,'');
        return true;
      };
      if(val!=null){
        idx.value = val;
      }
      return idx;
    }

    function generateHarga(index,val) {
      var idx  = document.createElement("input");
      idx.type = "text";
      idx.name = "hrgsatuans("+index+")";
      idx.id   = "hrgsatuans("+index+")";
      idx.onkeyup = function (){
        var jum = parseInt(document.getElementById('hrgsatuans('+index+')').value);
        var angkasatu = parseInt(document.getElementById('jumitems('+index+')').value);
        if(document.getElementById('hrgsatuans('+index+')').value==""){
          document.getElementById('hrgsatuans('+index+')').value = 0;
        }else{
          document.getElementById('hrgsatuans('+index+')').value = jum;
        }
        document.getElementById('hrgtotals('+index+')').value = angkasatu*jum;
        if(isNaN(document.getElementById('hrgtotals('+index+')').value)){
          document.getElementById('hrgtotals('+index+')').value = 0;
        }
        var tbl = document.getElementById('tabledetail');
        var jumrow = tbl.rows.length;
        var hasil = 0;
        for(var i=1;i<jumrow;i++){
          hasil = parseInt(hasil) + parseInt(document.getElementById('hrgtotals('+i+')').value);
        }
        document.getElementById('belanja').value = hasil;
        this.value = this.value.replace(/[^0-9]/g,'');
        return true;
      };
      if(val!=null){
        idx.value = val;
      }
      return idx;
    }

    function generateTotal(index,val) {
      var idx  = document.createElement("input");
      idx.type = "text";
      idx.name = "hrgtotals("+index+")";
      idx.id   = "hrgtotals("+index+")";
      idx.readOnly = "readOnly";
      idx.title = "Harga total akan terhitung otomatis\ndari jumlah dikali harga satuan";
      if(val!=null){
        idx.value = val;
      }
      return idx;
    }

    function generateKet(index,val) {
      var idx  = document.createElement("input");
      idx.type = "text";
      idx.name = "keterangans("+index+")";
      idx.id   = "keterangans("+index+")";
      if(val!=null){
        idx.value = val;
      }
      return idx;
    }

    function clickAll() {
      var checked = false;
      if(document.getElementById("checkMaster").checked == true)
        checked = true;
      var tbl = document.getElementById("tabledetail");
      var rowLen = tbl.rows.length;
      for (var idx=1;idx<rowLen;idx++) {
        var row = tbl.rows[idx];
        var cell = row.cells[1];
        var node = cell.lastChild;
        node.checked = checked;
      }
    }

    function deleteAll() {
      var tbl = document.getElementById("tabledetail");
      var rowLen = tbl.rows.length - 1;
      for (var idx=rowLen;idx > 0;idx--) {
        tbl.deleteRow(idx)
      }
      document.getElementById('tbl_tambah_bahan').value = "Tambah";
    }

    function bufferRow(table) {
      var tbl = document.getElementById("tabledetail");
      var rowLen = tbl.rows.length;
      for (var idx=1;idx<rowLen;idx++) {
        var row = tbl.rows[idx];
        var cell = row.cells[1];
        var node = cell.lastChild;
        if (node.checked == false) {
          var rowNew = table.insertRow(table.rows.length);
          var td = new Array(document.createElement("td"),
                             document.createElement("td"),
                             document.createElement("td"),
                             document.createElement("td"),
                             document.createElement("td"),
                             document.createElement("td"),
                             document.createElement("td"));
          td[0].appendChild(row.cells[0].lastChild);
          td[1].appendChild(row.cells[1].lastChild);
          td[2].appendChild(row.cells[2].lastChild);
          td[3].appendChild(row.cells[3].lastChild);
          td[4].appendChild(row.cells[4].lastChild);
          td[5].appendChild(row.cells[5].lastChild);
          td[6].appendChild(row.cells[6].lastChild);
          rowNew.appendChild(td[0]);
          rowNew.appendChild(td[1]);
          rowNew.appendChild(td[2]);
          rowNew.appendChild(td[3]);
          rowNew.appendChild(td[4]);
          rowNew.appendChild(td[5]);
          rowNew.appendChild(td[6]);
        }
      }
    }

    function reIndex(table) {
      var tbl = document.getElementById("tabledetail");
      var rowLen = table.rows.length;
      for (var idx=0;idx<rowLen;idx++){
        var row = table.rows[idx];
        var rowTbl = tbl.insertRow(tbl.rows.length);
        var td = new Array(document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"),
                           document.createElement("td"));
        td[0].appendChild(generateIndex(row.rowIndex+1,row.cells[0].lastChild.value));
        td[1].appendChild(generateCheckBox(row.rowIndex+1));
        td[2].appendChild(generateNamaBahan(row.rowIndex+1,row.cells[2].lastChild.value));
        td[3].appendChild(generateJumItem(row.rowIndex+1,row.cells[3].lastChild.value));
        td[4].appendChild(generateHarga(row.rowIndex+1,row.cells[4].lastChild.value));
        td[5].appendChild(generateTotal(row.rowIndex+1,row.cells[5].lastChild.value));
        td[6].appendChild(generateKet(row.rowIndex+1,row.cells[6].lastChild.value));
        rowTbl.appendChild(td[0]);
        rowTbl.appendChild(td[1]);
        rowTbl.appendChild(td[2]);
        rowTbl.appendChild(td[3]);
        rowTbl.appendChild(td[4]);
        rowTbl.appendChild(td[5]);
        rowTbl.appendChild(td[6]);
      }
    }

    function deleteRow() {
      var tbl    = document.getElementById("tabledetail");
      var error  = false;
      if (document.getElementById("checkMaster").checked == false)
        error    = true;
      var rowLen = tbl.rows.length;
      for(var idx=1;idx<rowLen;idx++){
        var row  = tbl.rows[idx];
        var cell = row.cells[1];
        var node = cell.lastChild;
        if (node.checked == true) {
          error = false;
          break;
        }
      }
      if (error == true) {
        swal({
          title: "Info",
          text: "Tidak ada data dipilih",
          type: "info",
          confirmButtonColor: "#2b5dcd",
          confirmButtonText: "OK",
          closeOnConfirm: true
        });
        return;
      }
      if (document.getElementById("checkMaster").checked == true) {
        deleteAll();
        document.getElementById("checkMaster").checked = false;
      } else {
        var table = document.createElement("table");
        bufferRow(table);
        deleteAll();
        reIndex(table);
      }
    }

    function cancel(){
      document.getElementById('popupBox').style.display="none";
      document.getElementById('checkMaster').click();
      deleteAll();
    }
  </script>
  <body>
    <form method="POST">
      <div id="popupBox" class="popup">
        <div id="bataswrap">
          <div class="batasbahan">
            <fieldset>
              <div><label>Olah Bahan Baku</label></div>
              <div class="close"><span class="exit">x</span></div>
            </fieldset>
          </div>
          <div class="panelcontentbatas">
            <aside class="side">
              <div class="batas1">
                <input type="button" class="cleardata" onclick="clear_field()">
                <fieldset class="blockbatas">
                  <legend>Manipulasi Data</legend>
                  <div>
                    <table id="fieldatur" class="fieldpop">
                      <tr><td><span>Nomor</span></td><td><span>:</span></td><td><input id="noindex" type="text" name="noindex" title="Nomor Urut Bahan Baku" placeholdes="Nomor" readOnly></td></tr>
                      <tr><td><span>Beli</span></td><td><span>:</span></td><td><input id="tgl_beli" class="datepicker" onkeyup="validasinomor()" name="tgl_beli" type="text" title="Tanggal Beli" placeholder="EX: YYYY-mm-dd" readonly></td></tr>
                      <tr><td><span>Produksi</span></td><td><span>:</span></td><td><input id="tgl_produksi" class="datepicker" onkeyup="validasinomor()" name="tgl_produksi" type="text" title="Tanggal Produksi" placeholder="EX: YYYY-mm-dd" readonly></td></tr>
                      <tr><td><span>Kadaluarsa</span></td><td><span>:</span></td><td><input id="tgl_kadaluarsa" class="datepicker" name="tgl_kadaluarsa" type="text" title="Tanggal Kadaluarsa" placeholder="EX: YYYY-mm-dd" readonly></td></tr>
                      <tr><td><span>Jumlah</span></td><td><span>:</span></td><td><input id="jum_batas" onkeyup="javascript:return validasi(this,1);" name="jum_batas" type="number" min=0 title="Jumlah Bahan" placeholder="Jumlah Bahan"></td></tr>
                      <tr><td><span>Keterangan</span></td><td colspan=2><span>:</span></td></tr>
                      <tr><td colspan="3"><textarea id="ket_batas" onkeyup="validasinomor()" name="ket_batas" title="Keterangan" placeholder="Keterangan Bahan"></textarea></td></tr>
                    </table>
                  </div>
                  <hr>
                  <input id="tbh_bts_bhn" name="tbh_bts_bhn" type="button" onclick="validasifield(1)" value="Tambah">
                  <input id="ubh_bts_bhn" name="ubh_bts_bhn" type="button" onclick="validasifield(0)" value="Ubah" disabled>
                  <input id="hps_bts_bhn" name="hps_bts_bhn" type="button" onclick="validasihapus()" value="Hapus" disabled>
                </fieldset>
                <input type="hidden" id="no_bhn" name="no_bhn" value="<?php echo $nobahan?>">
                <input type="hidden" id="no_reg" name="no_reg">
              </div>
            </aside>
            <section>
              <div class="databatas">
                  <div class="redpanel">NAMA BAHAN : <?php echo $namabahan ?></div>
                  <hr>
                  <table id="tablebatasbahan" class="tablebatasbahan">
                    <thead>
                      <tr>
                        <th style="display:none" width="0%" rowspan="2"></th>
                        <th width="8%" rowspan="2">NO</th>
                        <th width="60%" colspan="3">TANGGAL</th>
                        <th width="10%" rowspan="2">JUMLAH</th>
                        <th width="22%" rowspan="2">KETERANGAN</th>
                      </tr>
                      <tr>
                        <th width="20%">BELI</th>
                        <th width="20%">PRODUKSI</th>
                        <th width="20%">KADALUARSA</th>
                      </tr>
                    </thead>
                      <?php
                        if($querybatas){
                          $i=1;
                          while($resultdata=mysqli_fetch_assoc($querybatas)){
                            echo "<tr onclick=detail_batas_bhn(this.cells[0].innerHTML,this.cells[1].innerHTML,this.cells[2].innerHTML,this.cells[3].innerHTML,this.cells[4].innerHTML,this.cells[5].innerHTML,this.cells[6].innerHTML) title='Klik untuk menyeleksi data'>
                                      <td style='display:none'>$resultdata[no_reg]</td>
                                      <td>$i</td>
                                      <td>$resultdata[tgl_beli]</td>
                                      <td>$resultdata[tgl_produksi]</td>
                                      <td>$resultdata[tgl_kadaluarsa]</td>
                                      <td>$resultdata[jumlah]</td>
                                      <td>$resultdata[keterangan]</td>
                                      </tr>";
                            $i++;
                          }
                        }
                      ?>
                  </table>
              </div>
            </section>
          </div>
        </div>
        <!-- popup kedua -->
        <div id="pencarianwrap">
          <div class="batasbahan">
            <fieldset>
              <div><label>Pengolahan Status Hidangan</label></div>
              <div class="closetbl"><span class="exit">x</span></div>
            </fieldset>
          </div>
          <div class="panelcontentbatas">
            <aside class="side">
              <div class="batas1">
                <fieldset class="blockbatas">
                  <legend>Olah Status Tampil Hidangan</legend>
                  <select name="kondisipop" id="kondisipop" onchange="changekondisi()">
                    <option>Semua Hidangan</option>
                    <option>Hidangan Aktif</option>
                    <option>Hidangan Tidak Aktif</option>
                  </select>
                  <input type="button" name="aktifkan" id="aktifkan" value="Aktifkan" onclick="centang_hidangan(1)">
                  <input type="button" name="nonaktifkan" id="nonaktifkan" value="Nonaktifkan" onclick="centang_hidangan(0)">
                  <input style="display:none" type="button" name="prosesselect" id="prosesselect" value="Proses">
                </fieldset>
                <fieldset class="blockbatas">
                    <legend>Cari Hidangan dengan Bahan Baku</legend>
                    <input type="text" name="cari_nama_hidangan" id="cari_nama_hidangan" placeholder="Isikan Nama Bahan Baku" title="Isikan nama bahan baku untuk mengetahui dihidangan apa saja ia dipakai">
                    <input type="button" name="cari_hidangan" id="cari_hidangan" value="Cari Hidangan" onclIck="cari_proses()">
                </fieldset>
              </div>
            </aside>
            <section>
              <div class="databatas">
                <?php
                  if(($queryhidangan)&&(!$querycari)){
                    echo "<table id='tablecari' class='tablepop'>
                            <thead>
                              <tr>
                                <th width='6%'><input type='checkbox' name='masterpop' id='masterpop' onclick='checkdatapop()'>
                                <th width='6%'>NO</th>
                                <th width='78%'>NAMA</th>
                                <th width='10%'>STATUS</th>
                              </tr>
                            </thead>";
                            $i=1;
                            while($resultdata=mysqli_fetch_assoc($queryhidangan)){
                              if($resultdata[status]==1){
                                $namepng = 'img/check.png';
                              }else{
                                $namepng = 'img/not.png';
                              }
                              echo "<tr onclick='checkrow($i)'>
                                      <td id='nohidangan($i)'>$resultdata[no_hidangan]</td>
                                      <td><input class='checkbox' onclick='checkrow($i)' type='checkbox' name='checkpop($resultdata[no_hidangan])' id='checkpop($i)'></td>
                                      <td>$i</td>
                                      <td>$resultdata[nama_hidangan]</td>
                                      <td align='center'><img class='status' src='$namepng'></td>
                                    </tr>";
                                $i++;
                              }

                          echo"</table>";
                  }else if($querycari){
                    echo "<table id='tablecari' class='tablepop'>
                            <thead>
                              <tr>
                                <th width='6%'><input type='checkbox' name='masterpop' id='masterpop' onclick='checkdatapop()'>
                                <th width='6%'>NO</th>
                                <th width='40%'>NAMA</th>
                                <th width='15%'>JUMLAH</th>
                                <th width='23%'>KETERANGAN</th>
                                <th width='10%'>STATUS</th>
                              </tr>
                            </thead>";
                            $i=1;
                            while($resultdata=mysqli_fetch_assoc($querycari)){
                              if($resultdata['status']==1){
                                $namepng = 'img/check.png';
                              }else{
                                $namepng = 'img/not.png';
                              }
                              echo "<tr onclick='checkrow($i)'>
                                      <td id='nohidangan($i)'>$resultdata[no_hidangan]</td>
                                      <td><input class='checkbox' type='checkbox' name='checkpop($resultdata[no_hidangan])' id='checkpop($i)'></td>
                                      <td>$i</td>
                                      <td>$resultdata[nama_hidangan]</td>
                                      <td>$resultdata[jumlah]</td>
                                      <td>$resultdata[keterangan]</td>
                                      <td align='center'><img class='status' src='$namepng'></td>
                                    </tr>";
                                $i++;
                              }
                          echo"</table>";
                  }
                ?>
              </div>
            </section>
          </div>
        </div>
         
      </div>
      <!-- end popup ketiga-->
      <header>
        <label class="judul">Panel Pantry - Resto Bro</label>
        <aside class="panellogout">
            <input class="sign" id="logout" name="logout" type="button" onclick="log_out()" value="Log out">
        </aside>
      </header>
      <div id="bodycontent">
        <div>
          <section id="rightside">
            <div id="head">
              <label>Data Gudang</label>

              <fieldset>
                <input type="text" class="light-table-filter"
                      data-table="order-table" placeholder="Cari Bahan Baku">
              </fieldset>
            </div>
            <aside id="table_head">
              <table>
                <thead>
                  <tr><th width="3%"><input type="checkbox" name="master" id="master" onclick="checkall()">
                      <th width="6%">NO
                      <th width="43%">NAMA BAHAN
                      <th width="9%">JUMLAH
                      <th width="2%">KETERANGAN
                </thead>
              </table>
            </aside>
            <div id="paneltable">
              <?php
                echo '<table id="tabledata" class="order-table"><tbody>';
                $i=1;
                while($qtabel=mysqli_fetch_assoc($query)){
                  $nama = '"'.$qtabel[nama_bahan].'"';
                  echo "<tr title='Detail..'>
                            <td id='nobahan($i)'>$qtabel[no_bahan]</td>
                            <td><input class='checkbox' type='checkbox' name='check($qtabel[no_bahan])' id='check($i)'></td>
                            <td>$i</td>
                            <td>$qtabel[nama_bahan]</td>
                            <td><input class='text_input' onchange='checkthis($i)' onkeyup='validasi(this,0)' type='number' min=0 name='jum($qtabel[no_bahan])' id='jum($i)' value='$qtabel[jumlah]'></td>
                            <td><input class='text_input' onchange='checkthis($i)' type='text' name='ket($qtabel[no_bahan])' id='ket($i)' value='$qtabel[keterangan]'</td>
                        </tr>";
                  $i++;
                }
                echo "</tbody></table>";
              ?>
            </div>
            <div class="panelinfo">
              <input type="button" onclick="simpan_data_bahan()" name="simpan" id="simpan" value="Simpan">
              <input type="button" onclick="clear_data(1)" name="batal" id="batal" value="Batal">
            </div>
          </section>
        </div>
      </div>
      <input type='hidden' id="temp_jum">
      <input type='hidden' id="temp_ket">
      <input type='hidden' name="temp_no_tabel" id="temp_no_tabel">
      <input type='hidden' name="temp_no_tabel_batas" id="temp_no_tabel_batas">
      <input type="hidden" name="temp_nama_bahan" id="temp_nama_bahan">
      <input type='hidden' name="temp_no_hidangan" id="temp_no_hidangan">
    </form>
    <?php
      if($_POST['kondisipop']){
        echo "<script>document.getElementById('kondisipop').value = '$kondisipop'</script>";
      }
    ?>
      <script>
        var status_batas = "<?php echo $status_batas ?>";
        var popupwrap = document.getElementById('popupBox');
        var tablewrap = document.getElementById('bataswrap');
        var cariwrap = document.getElementById('pencarianwrap');
        var lap = document.getElementById('kelolawrap');
        var close = document.getElementsByClassName('close')[0];
        var closetbl = document.getElementsByClassName('closetbl')[0];
        var closetbl1 = document.getElementsByClassName('closetbl1')[0];
        function olah_batas_waktu() {
          popupwrap.style.display = 'block';
          tablewrap.style.display = 'block';
        }

        function popupcari(){
          popupwrap.style.display = 'block';
          cariwrap.style.display = 'block';
        }

        function popuplap(){
          popupwrap.style.display = 'block';
          lap.style.display = 'block';
        }

        if(status_batas=="1"){
          olah_batas_waktu();
        }else if(status_batas=="2"){
          popupcari();
        }else if(status_batas=="3"){
          popuplap();
        }

        close.onclick = function(){
          popupwrap.style.display = 'none';
          tablewrap.style.display = 'none';
        }

        closetbl.onclick = function(){
          popupwrap.style.display = 'none';
          cariwrap.style.display = 'none';
        }

        closetbl1.onclick = function(){
          popupwrap.style.display = 'none';
          lap.style.display = 'none';
        }

        window.onclick = function(event) {
          if (event.target == popupwrap)  {
            popupwrap.style.display = 'none';
            tablewrap.style.display = 'none';
            cariwrap.style.display = 'none';
            lap.style.display = 'none';
          }
        }
      </script>
      <?php
        if(($_POST['simpan'])or($_POST['tbh_bts_bhn'])or($_POST['ubh_bts_bhn'])or($_POST['hps_bts_bhn'])or
            ($_POST['tbl_simpan'])or($_POST['simpan_data_baru'])or($_POST['tbldelete'])){
              if($messagests==1){
               echo "<script>
                        window.onload = function(){
                          swal({
                            title: '$labelsts',
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
                          title: '$labelsts',
                          text: '$message',
                          type: '$tipests',
                          confirmButtonColor: '#DD6B55',
                          confirmButtonText: 'OK',
                          closeOnConfirm: true
                        });
                      }
                    </script>";
             }
        }
        mysqli_close($dbcon);
      ?>
  </body>
</html>
