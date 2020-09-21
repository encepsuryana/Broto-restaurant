<?php
	error_reporting(E_ALL & ~E_NOTICE);
	session_start();

	if($_SESSION['pelanggan']){
		header('Location: pelanggan.php');
	}

	if($_SESSION['jabatan']!="KOKI" || !isset($_SESSION['nip'])){
		header('Location: admin.php');
	}

	if($_POST["kitchen"]){
    header("Location: datapesanan.php");
  }

	if($_POST['logout']){
		header("Location: logout.php");
	}

	include_once("koneksi.php");

	if($_POST['batalkan']){
		$status_resep ="0";
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
					$gagal = true;
					break;
				}
			}else{
				$no = $no.substr($nomor,$i,1);
			}
		}
		if(!$gagal){
			mysqli_commit($dbcon);
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
      $status_atur = "1";
    }else{
      mysqli_rollback($dbcon);
    }
  }

	if($_POST['tbl_tambah']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$nimage = $_POST['nilaiimage'];
		$image = '';
		if($nimage!=null)
			$image = addslashes(file_get_contents($_FILES['realupload']['tmp_name']));
		$data = array(strtoupper($_POST['getnama']),
									$_POST['tipe'],
									$_POST['getharga']);
		$sql = "INSERT INTO t_hidangan VALUES (null,'$data[0]','$data[1]',$data[2],'$image',0,'$_SESSION[nip]')";
		$query = mysqli_query($dbcon,$sql);
		if($query){
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
			$message = "Data gagal disimpan";
		}

	}

	if($_POST['tbl_hapus']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$data = $_POST['getno'];
		$sql = "DELETE FROM t_hidangan WHERE no_hidangan = '$data'";
		$query = mysqli_query($dbcon,$sql);
		if($query){
			mysqli_commit($dbcon);
			$messagests = 1;
			$tipests = "success";
			$labelsts = "Berhasil";
			$message = "Data berhasil dihapus";
		}else{
			mysqli_rollback($dbcon);
			$tipests = "error";
			$labelsts = "Gagal";
			$messagests = 0;
			$message = "Data tidak berhasil dihapus";
		}
	}

	if($_POST['tbl_ubah']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$nimage = $_POST['nilaiimage'];
		$data = array($_POST['getno'],
								strtoupper($_POST['getnama']),
								$_POST['gettipe'],
								$_POST['getharga']);
		if(($nimage!=null)|($nimage=="KOSONG")){
			$image = '';
			if($nimage!="KOSONG")
				$image = addslashes(file_get_contents($_FILES['realupload']['tmp_name']));
			$strsql = "nama_hidangan='$data[1]', kode_tipe='$data[2]',
								 harga_hidangan='$data[3]', image='$image', NIP = '$_SESSION[nip]'";
		}else{
			$strsql = "nama_hidangan='$data[1]', kode_tipe='$data[2]',
							   harga_hidangan='$data[3]', NIP = '$_SESSION[nip]'";
		}

		$sql = "UPDATE t_hidangan SET $strsql WHERE no_hidangan = '$data[0]'";
		$query = mysqli_query($dbcon,$sql);
		$message = "Data berhasil diubah";
		if($query){
			mysqli_commit($dbcon);
			$tipests = "success";
			$labelsts = "Berhasil";
			$messagests = 0;
			$message = "Data berhasil diubah";
		}else{
			mysqli_rollback($dbcon);
			$tipests = "error";
			$labelsts = "Gagal";
			$messagests = 0;
			$message = "Data tidak berhasil diubah";
		}
	}

	if($_POST['tbh_bhn_baku']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$status_bhn_baku = '1';
		$sqltbhbhn = 'INSERT INTO t_bahan_baku (nama_bahan) VALUES ("'.strtoupper($_POST[namabhnbaku]).'")';
		$querytbhbhn = mysqli_query($dbcon,$sqltbhbhn);
		if($querytbhbhn){
			mysqli_commit($dbcon);
			$tipests = "success";
			$labelsts = "Berhasil";
			$messagests = 0;
			$message = 'Data berhasil tersimpan';
		}else{
			mysqli_rollback($dbcon);
			$tipests = "error";
			$labelsts = "Gagal";
			$messagests = 0;
			$message = 'Data gagal tersimpan';
		}
	}

	if($_POST['ubh_bhn_baku']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$status_bhn_baku = '1';
		$sqlubhbhn = 'UPDATE t_bahan_baku SET nama_bahan="'.strtoupper($_POST[namabhnbaku]).'" WHERE no_bahan='.$_POST[nobhnbaku];
		$queryubhbhn = mysqli_query($dbcon,$sqlubhbhn);
		if($queryubhbhn){
			mysqli_commit($dbcon);
			$tipests = "success";
			$labelsts = "Berhasil";
			$messagests = 0;
			$message = 'Data berhasil diubah';
		}else{
			mysqli_rollback($dbcon);
			$tipests = "error";
			$labelsts = "Gagal";
			$messagests = 0;
			$message = 'Data gagal diubah';
		}
	}

	if($_POST['hps_bhn_baku']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$status_bhn_baku = '1';
		$sqlhpsbhn = "DELETE FROM t_bahan_baku WHERE no_bahan=$_POST[nobhnbaku]";
		$queryhpsbhn = mysqli_query($dbcon,$sqlhpsbhn);
		if($queryhpsbhn){
			mysqli_commit($dbcon);
			$tipests = "success";
			$labelsts = "Berhasil";
			$messagests = 0;
			$message = 'Data berhasil dihapus';
		}else{
			mysqli_rollback($dbcon);
			$tipests = "error";
			$labelsts = "Gagal";
			$messagests = 0;
			$message = 'Data gagal dihapus';
		}
	}

	if(($_POST['tambah_bahan_baku'])or($_POST['tbh_bhn_baku'])or
		 ($_POST['ubh_bhn_baku'])or($_POST['hps_bhn_baku'])){
	 	 	mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
			$status_bhn_baku = '1';
			$sqlbhn = "SELECT * FROM t_bahan_baku";
			$querybhn = mysqli_query($dbcon,$sqlbhn);
			if($querybhn){mysqli_commit($dbcon);}else{mysqli_rollback($dbcon);}
	}

	mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
	$tipemakanan = $_POST['selekgettipe'];
	if($tipemakanan!=null){
		$kondisi = "WHERE kode_tipe = ";
	}else {
		$kondisi = "";
	}
	$sql 		= "SELECT no_hidangan, nama_hidangan, kode_tipe,
							 harga_hidangan FROM t_hidangan $kondisi $tipemakanan";
	$query 		= mysqli_query($dbcon, $sql);
	if($query){mysqli_commit($dbcon);}else{mysqli_rollback($dbcon);}

	mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
	if(($_POST['getno'])&($_POST['gettipe'])){
		$tipemkn = $_POST['gettipe'];
		$nomor = $_POST['getno'];
		$idx = $_POST['getidx'];
		$sqlsb  		= "SELECT * FROM t_hidangan
									 WHERE no_hidangan= $nomor";
		$querysb 	= mysqli_query($dbcon, $sqlsb);
		if($querysb){mysqli_commit($dbcon);}else{mysqli_rollback($dbcon);}
		$result = mysqli_fetch_array($querysb);
	}

	if($_POST['simpanresep']){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_WRITE);
		$inputan = "";
		$jum = $_POST['jumbaris'];
		for($i=1;$i<=$jum;$i++){
				if($i!=$jum){
					$input = $input.'('.$nomor.','.$_POST["nomor($i)"].','.$_POST["jumitem($i)"].',"'.strtoupper($_POST["ket($i)"]).'"),';
				}else{
					$input = $input.'('.$nomor.','.$_POST["nomor($i)"].','.$_POST["jumitem($i)"].',"'.strtoupper($_POST["ket($i)"]).'")';
				}
		}
		$sqlresep = "DELETE FROM t_resep WHERE no_hidangan = $nomor";
		$queryresep = mysqli_query($dbcon,$sqlresep);
		echo "<script>sleep(300)</script>";
		$sqlresep = "INSERT INTO t_resep VALUES $input";
		$queryresep = mysqli_query($dbcon,$sqlresep);
		if($queryresep){
			mysqli_commit($dbcon);
			$tipests = "success";
			$labelsts = "Berhasil";
			$messagests = 1;
			$message="Resep berhasil disimpan";
		}else{
			mysqli_rollback($dbcon);
			$tipests = "error";
			$labelsts = "Gagal";
			$messagests = 0;
			$message="Resep gagal disimpan";
		}
	}

	if(($_POST['resep'])||($_POST['simpanresep'])){
		mysqli_begin_transaction($dbcon,MYSQLI_TRANS_START_READ_ONLY);
		$status_resep = '1';
		$sql = "SELECT a.no_bahan,nama_bahan,a.jumlah,a.keterangan FROM t_resep a JOIN t_bahan_baku b
			      ON a.no_bahan = b.no_bahan WHERE no_hidangan = $nomor";
		$queryresep = mysqli_query($dbcon,$sql);
		$sqlbahanbaku = "SELECT no_bahan,nama_bahan FROM t_bahan_baku ORDER BY nama_bahan ASC";
		$querybahanbaku = mysqli_query($dbcon,$sqlbahanbaku);
		if($querybahanbaku){mysqli_commit($dbcon);}else{mysqli_rollback($dbcon);}
	}
?>
<html>
	<head>
		<title>My Resto | Koki</title>
	</head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/hidangan.css" type="text/css">
	<script type="text/javascript" async="" src="javascript/filter.js"></script>
	<link rel="stylesheet" href="css/sweetalert.css" type="text/css">
	<script type="text/javascript" async="" src="javascript/sweetalert.min.js"></script>
	<script type="text/javascript">
		function ubahdata(){
		  var id = document.getElementById('bahan_resep').value;
		  if(id!=""){
		    var numb = document.getElementById('ubahresep').value;
		    var tbl = document.getElementById('dataresep');
		    var jumrow = tbl.rows.length;
		    var length = id.length;
		    var i = 0;
		    var nama = "";
		    while(nama!='#'){
		      nama = id.substring(i,i+1);
		      i++;
		    }
		    nama = id.substring(i,length);
		    no = id.substring(0,i-1);
				sama = false;
		    for(i = 1;i<=jumrow-1;i++){
		      if(document.getElementById('nomor('+i+')').value==no){
		        sama = true;
		        break;
		      }
		    }
		    if(sama){
					swal({
						title: "Info",
						text: "Data "+nama+" telah terdaftar pada tabel resep",
						type: "info",
						confirmButtonColor: "#2b5dcd",
						confirmButtonText: "OK",
						closeOnConfirm: true
					});
		    }else{
		      document.getElementById('namabahan('+numb+')').value=nama;
		      document.getElementById('nomor('+numb+')').value= ''+no;
		      document.getElementById('tbl_tambah_bahan').value = "Tambah";
		    }
		  }else if(id==''){
				swal({
					title: "Info",
					text: "Tidak ada bahan baku yang dapat diubah",
					type: "info",
					confirmButtonColor: "#2b5dcd",
					confirmButtonText: "OK",
				});
		  }
		}

		function addNewRow(stats,nobahan,nama) {
		  var tbl = document.getElementById("dataresep");
		  var jumrow = tbl.rows.length;
		  var sama = false;
		  var bahan = document.getElementById('bahan_resep').value;
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
		  }
		  for(var i = 1;i<=jumrow-1;i++){
		    if(document.getElementById('nomor('+i+')').value==nobahan){
		      sama = true;
		      break;
		    }
		  }
		  if(((bahan!="")||(stats==1))&&(!sama)){
		    var row = tbl.insertRow(tbl.rows.length);
		    var td = new Array(document.createElement("td"),
		                       document.createElement("td"),
		                       document.createElement("td"),
		                       document.createElement("td"),
		                       document.createElement("td"));
		    td[0].appendChild(generateIndex(row.rowIndex,nobahan));
		    td[1].appendChild(generateCheckBox(row.rowIndex));
		    td[2].appendChild(generateNamaBahan(row.rowIndex));
		    td[3].appendChild(generateJumItem(row.rowIndex));
		    td[4].appendChild(generateKet(row.rowIndex));
		    row.appendChild(td[0]);row.appendChild(td[1]);
		    row.appendChild(td[2]);row.appendChild(td[3]);
		    row.appendChild(td[4]);
		    if(stats!=1){
		      document.getElementById("namabahan("+row.rowIndex+")").value = nama;
		    }
		  }else{
		    if(sama){
					swal({
						title: "Info",
						text: "Data bahan baku telah terdaftar ditabel resep",
						type: "info",
						confirmButtonColor: "#2b5dcd",
						confirmButtonText: "OK",
						closeOnConfirm: true
					});
		    }else{
					swal({
						title: "Info",
						text: "Tidak ada data bahan yang ditambahkan\nanda belum memilih data bahan",
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
		  idx.name  = "nomor("+index+")";
		  idx.id    = "nomor("+index+")";
		  idx.value = ""+no_bahan+"";
		  return idx;
		}

		function generateCheckBox(index) {
		  var check  = document.createElement("input");
		  check.type = "checkbox";
		  check.name = "check("+index+")";
		  check.id   = "check("+index+")";
		  return check;
		}

		function generateNamaBahan(index,val) {
		  var idx  = document.createElement("input");
		  idx.type = "text";
		  idx.name = "namabahan("+index+")";
		  idx.id   = "namabahan("+index+")";
		  idx.onclick = function(){
		    document.getElementById('bahan_resep').value=document.getElementById("nomor("+index+")").value+'#'+document.getElementById("namabahan("+index+")").value;
		    document.getElementById('tbl_tambah_bahan').value="Ubah";
		    document.getElementById('ubahresep').value = index;
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
		  idx.name = "jumitem("+index+")";
		  idx.id   = "jumitem("+index+")";
		  idx.onkeyup = function (){
		    this.value = this.value.replace(/[^0-9]/g,'');
		    return true;
		  };
		  if(val!=null){
		    idx.value = val;
		  }
		  return idx;
		}

		function generateKet(index,val) {
		  var idx  = document.createElement("input");
		  idx.type = "text";
		  idx.name = "ket("+index+")";
		  idx.id   = "ket("+index+")";
		  if(val!=null){
		    idx.value = val;
		  }
		  return idx;
		}

		function clickAll() {
		  var checked = false;
		  if(document.getElementById("checkMaster").checked == true)
		    checked = true;
		  var tbl = document.getElementById("dataresep");
		  var rowLen = tbl.rows.length;
		  for (var idx=1;idx<rowLen;idx++) {
		    var row = tbl.rows[idx];
		    var cell = row.cells[1];
		    var node = cell.lastChild;
		    node.checked = checked;
		  }
		}

		function deleteAll() {
		  var tbl = document.getElementById("dataresep");
		  var rowLen = tbl.rows.length - 1;
		  for (var idx=rowLen;idx > 0;idx--) {
		    tbl.deleteRow(idx)
		  }
		  document.getElementById('tbl_tambah_bahan').value = "Tambah";
		}

		function bufferRow(table) {
		  var tbl = document.getElementById("dataresep");
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
		      document.createElement("td"));
		      td[0].appendChild(row.cells[0].lastChild);
		      td[1].appendChild(row.cells[1].lastChild);
		      td[2].appendChild(row.cells[2].lastChild);
		      td[3].appendChild(row.cells[3].lastChild);
		      td[4].appendChild(row.cells[4].lastChild);
		      rowNew.appendChild(td[0]);
		      rowNew.appendChild(td[1]);
		      rowNew.appendChild(td[2]);
		      rowNew.appendChild(td[3]);
		      rowNew.appendChild(td[4]);
		    }
		  }
		}

		function reIndex(table) {
		  var tbl = document.getElementById("dataresep");
		  var rowLen = table.rows.length;
		  for (var idx=0;idx < rowLen;idx++) {
		    var row = table.rows[idx];
		    var rowTbl = tbl.insertRow(tbl.rows.length);
		    var td = new Array(document.createElement("td"),
		              document.createElement("td"),
		              document.createElement("td"),
		              document.createElement("td"),
		              document.createElement("td"));
		    td[0].appendChild(generateIndex(row.rowIndex+1,row.cells[0].lastChild.value));
		    td[1].appendChild(generateCheckBox(row.rowIndex+1));
		    td[2].appendChild(generateNamaBahan(row.rowIndex+1,row.cells[2].lastChild.value));
		    td[3].appendChild(generateJumItem(row.rowIndex+1,row.cells[3].lastChild.value));
		    td[4].appendChild(generateKet(row.rowIndex+1,row.cells[4].lastChild.value));
		    rowTbl.appendChild(td[0]);
		    rowTbl.appendChild(td[1]);
		    rowTbl.appendChild(td[2]);
		    rowTbl.appendChild(td[3]);
		    rowTbl.appendChild(td[4]);
		  }
		}

		function deleteRow() {
		  var tbl    = document.getElementById("dataresep");
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
					text: "Tidak ada data yang dicentang",
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

		function cancelresep(){
			document.getElementById('batalkan').type="submit";
		  document.getElementById('batalkan').submit();
		}

		function pilihaksi(){
		  if(document.getElementById('tbl_tambah_bahan').value=="Tambah"){
		    addNewRow();
		  }else{
		    ubahdata();
		  }
		}
	</script>
	<script type="text/javascript" src="javascript/proseshidangan.js"></script>
	<body>
		<form id="klik" method="post" enctype="multipart/form-data">
			<!--POPUP Resep-->
			<div id="popupBox" class="popup">
				<div id="tambahbahanwrap">
					<div class="olahbahan">
						<fieldset>
							<div><label>Olah Bahan Baku</label></div>
							<div class="close"><span class="exit">x</span></div>
						</fieldset>
					</div>
					<div class="panelcontentbatas">
						<aside class="side">
							<div class="batas1">
								<fieldset class="blockpop">
									<legend>Pencarian Data</legend>
									<div><input type="search" class="light-table-filter"
												data-table="tablebahanbaku" placeholder="Cari Data Bahan Baku"
												title="Mencari hidangan pada tabel hidangan"/></div>
								</fieldset>
								<br>
								<fieldset class="blockpop">
									<legend>Manipulasi Data</legend>
									<div><input id="nobhn" type="text" placeholder="No" title="Nomor tidak perlu diisi saat menambah data" readOnly>
											 <input id="namabhnbaku" onkeyup="validasinomor()" name="namabhnbaku" type="text" placeholder="Nama Bahan"></div>
									<div><input id="tbh_bhn_baku" onClick="tambahitem()" name="tbh_bhn_baku" type="button" value="Tambah"></div>
									<div><input id="ubh_bhn_baku" onClick="ubahitem()" name="ubh_bhn_baku" type="button" value="Ubah"></div>
									<div><input id="hps_bhn_baku" onClick="hapusitem()" name="hps_bhn_baku" type="button" value="Hapus"></div>
								</fieldset>
							</div>
						</aside>
						<section>
							<div id="databahan" >
									<table id="tablebahanbaku" class="tablebahanbaku">
										<thead><tr>
												<th style="display:none" width="0%"></th>
												<th width="8%">NO</th>
												<th width="42%">NAMA BAHAN BAKU</th>
												<th width="20%">JUMLAH DI PANTRY</th>
											  <th width="30%">KETERANGAN</th></thead>
										<?php
											if($querybhn){
												$i=1;
												while($resultdata=mysqli_fetch_assoc($querybhn)){
													echo "<tr onclick=detail_bhn_baku(this.cells[0].innerHTML,this.cells[1].innerHTML,this.cells[2].innerHTML) title='Klik untuk menyeleksi data'>
																		<td style='display:none'>$resultdata[no_bahan]</td>
																		<td>$i</td>
																		<td>$resultdata[nama_bahan]</td>
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
				<div id="tablewrap">
					<div class="olahbahan">
						<div class="label"><label>Bahan Baku Resep</label></div>
						<div class="close"><span class="exit">x</span></div>
						<div id="daftar_resep_baru">
							<fieldset>
								<legend>Olah Resep</legend>
								<select id="bahan_resep" name="bahan_resep">
									<option class="figur" value="">Pilih Bahan Baku</option>
									<?php
										while($resultbahanbaku=mysqli_fetch_assoc($querybahanbaku)){
											echo '<option value="'.$resultbahanbaku['no_bahan'].'#'.$resultbahanbaku['nama_bahan'].'">'
													.$resultbahanbaku['nama_bahan'].
													'</option>';
										}
									?>
								</select>
								<input type="button" id="tbl_tambah_bahan" onclick="javascript:pilihaksi();" value="Tambah" title="Klik untuk menambahkan bahan masakan">
								<input type="button" id="tbl_delete_bahan" onclick="javascript:deleteRow();" value="Hapus" title="Klik untuk menghapus baris data bahan baku">
							</fieldset>
						</div>
					</div>
					<div class="tabledatawrap">
						<table id="dataresep">
							<tr>
								<th width="0%"></th>
								<th width="2%" align="center"><input type="checkbox"
									name="checkMaster" id="checkMaster" onClick="clickAll();"/></th>
								<th width="45%">NAMA HIDANGAN</th>
								<th width="24%">JUMLAH ITEM</th>
								<th width="29%">KETERANGAN</th>
								<?php
									if($queryresep){
										$i=1;
										while($resultresep = mysqli_fetch_assoc($queryresep)){
											echo "<script>
																addNewRow(1,'$resultresep[no_bahan]','$resultresep[no_bahan]#$resultresep[nama_bahan]');
																document.getElementById('nomor($i)').value = '$resultresep[no_bahan]';
																document.getElementById('namabahan($i)').value = '$resultresep[nama_bahan]';
																document.getElementById('jumitem($i)').value = '$resultresep[jumlah]';
																document.getElementById('ket($i)').value = '$resultresep[keterangan]';
														</script>";
											$i++;
										}
									}
								?>
							</tr>
						</table>
					</div>
					<div id="panelsimpan">
						<input type="button" id="simpanresep" name="simpanresep" onclick="save();" value="Simpan" title="Klik untuk menyimpan resep ke database">
						<input type="button" id="batalkan"  onclick="cancelresep()" value="Batalkan" title="Klik untuk membatalkan seluruh perubahan">
					</div>
				</div>
				<!-- END RESEP -->
				<!-- ATUR HIDANGAN -->
				<div id="aturhidangan">
					<div class="olahbahan">
						<fieldset>
							<div><label>Pengolahan Status Hidangan</label></div>
							<div class="close"><span class="exit">x</span></div>
						</fieldset>
					</div>
					<div class="panelcontentbatas">
						<aside class="side">
							<div class="batas1">
								<fieldset class="blockpop">
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
							</div>
							<div class="batas1">
								<fieldset class="blockpop">
										<legend>Cari Hidangan</legend>
										<input type="search" class="light-table-filter"
											    data-table="tablepop" placeholder="Cari Hidangan"
													title="Mencari hidangan pada tabel hidangan"/>
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
									}
								?>
							</div>
							<input type='hidden' name="temp_no_hidangan" id="temp_no_hidangan">

						</section>
					</div>
				</div>
				<!-- END ATUR HIDANGAN -->
			</div>
			<header>
				<aside>
					<label class="judul">Panel Koki - Resto Bro</label>
				</aside>
				<aside class="panelhead">
					<input class="sign" id="logout" onclick="log_out()" name="logout" type="button" value="Log out">
				</aside>
			</header>
			<section class="panelcontent">
					<div id="leftsb">
						<div name="olahdata">
							<div  class="data_baru"><label>Tambah Menu Baru</label>
								<input id="tbl_data_baru" type="button" width="30%" value="Tambah"
								onclick="buat_data()" title="Tambah Menu Baru">
								<input id="tbl_clear" type="button" width="30%" value="Batal"
								onclick="clear_data()" title="Batalkan">
							</div>
							<div class="panelfoto">
									<?php
										if(($result!=null)&($result[image]!=null)){
											echo '<image id=img src="data:image/jpeg;base64,'.base64_encode($result[image]).'"
											 			height=100% width=100%>';
											echo '<script>document.getElementById("img").value="ISI";</script>';
										}else{
											echo '<image id=img src="img/image_not_found.png" height=100% width=100%>';
											echo '<script>document.getElementById("img").value = ""</script>';
										}?>
							</div>
							<div class="olah_foto" name="olah_foto">
								<input type="button" id="unggah" onclick="unggah_img()" value="Unggah Foto" title="Masukan Foto">
								<input type="button" id="delete" onclick="delete_img()" value="Hapus Foto" title="Hapus Foto">
							</div>
							<fieldset>
									<div><input id="no" type="text" value="<?php echo "$result[no_hidangan]"?>"
												title="Nomor hidangan" placeholder="No" disabled>
											 <input id="nama_hidangan" type="text" value="<?php echo "$result[nama_hidangan]"?>"
											 title="Menu Hidangan" placeholder="Nama Hidangan">
									</div>
									<div><select id='tipe' name="tipe" title="Tipe hidangan">
												<option value="">PILIH JENIS MENU</option>
												<option value="APP">MENU PEMBUKA</option>
												<option value="MAC">MENU UTAMA</option>
												<option value="DES">MENU PENUTUP</option>
												<option value="SD">MINUMAN</option>
											</select>
											<?php if($result) echo "<script>document.getElementById('tipe').value='$tipemkn'</script>";?>
									</div>
									<div>
										<input type="text" onkeyup="return validasi(this)" value="<?php echo "$result[harga_hidangan]"?>"
											title="Harga Menu" placeholder="Harga Menu" id="harga_hidangan">
									</div>
								</fieldset>
								<div class="panelolah" name="panelolah">
										<input type="button" id="tambah" name="tbl_tambah" onclick="tambah_dan_ubah_data(1)" value="Tambah" title="Tambah Menu">
										<input type="button" id="ubah" name="tbl_ubah" onclick="tambah_dan_ubah_data(2)" value="Ubah" title="Ubah Menu">
										<input type="button" id="hapus" name="tbl_hapus" onclick="hapus_data()" value="Hapus" title="Hapus Menu">
								</div>
							</div>
						</div>
					<div class="content" >
						<aside class="head">
							<label>Daftar Menu Tersedia</label>
							<div><input type="search" class="light-table-filter"
									    data-table="order-table" placeholder="Cari Menu ... "
											title="Cari Data Menu"/></div>
						</aside>
						<aside class="table_head">
							<table>
								<thead>
									<tr><th>NO HID
											<th>NAMA MENU
											<th>TIPE
											<th>HARGA
								</thead>
							</table>
						</aside>
						<div class="table">
								<?php
									echo '<table id="tabledata" class="order-table"><tbody>';
									$i = 0;
									while($qtabel=mysqli_fetch_assoc($query)){
										echo "<tr onclick=detail(this.cells[0].innerHTML,this.cells[2].innerHTML,this.cells[4].innerHTML) title='Klik untuk melihat detail'>
															<td>$qtabel[no_hidangan]</td>
															<td>$qtabel[nama_hidangan]</td>
															<td>$qtabel[kode_tipe]</td>
															<td>$qtabel[harga_hidangan]</td>
															<td>$i</td>
															</tr>";
															$i++;
									}
									echo "</tbody></table>";
								?>
						</div>
					</div>
					<aside class="sidebar">
					<div  class="data_baru" style="height: 10%;"><label>Daftar Pesanan</label>
								<aside id="panelkitchen" class="panelhead">
									<input type="button" onclick="kitchenopen()" class="kitchen" id="kitchen" name="kitchen" value="Lihat">
								</aside>
							</div>
							</br>
						<aside id="wrapper">
							<fieldset class="seleksi">
								<legend>Tampilkan Berdasarkan : </legend>
									<div>
											 <input type="radio" id="all" name="tipe_hidangan" onclick=select_radio("all") value=""/>
											 <span onclick=select_radio("all")  title="Detail Menu">Tampil Semua Menu</span>
									</div>
									<div>
											<input type="radio" id="'APP'" name="tipe_hidangan" onclick=select_radio("'APP'") value="APP"/>
											<span onclick=select_radio("'APP'") title="Menu Pembuka">Menu Pembuka</span>
									</div>
									<div>
											<input type="radio" id="'MAC'" name="tipe_hidangan" onclick=select_radio("'MAC'") value="MAC"/>
											<span onclick=select_radio("'MAC'") title="Menu Utama">Menu Utama</span>
									</div>
									<div>
											<input type="radio" id="'DES'" name="tipe_hidangan" onclick=select_radio("'DES'") value="DES"/>
											<span onclick=select_radio("'DES'") title="Menu Penutup">Menu Penutup</span>
									</div>
									<div>
											<input type="radio" id="'SD'" name="tipe_hidangan" onclick=select_radio("'SD'") value="SD"/>
											<span onclick=select_radio("'SD'") title="Minuman">Minuman</span>
									</div>
							</fieldset>
						</aside>
							<aside id="wrapper">
						<legend>Opsi</legend>
								<div class="panelolah" name="panelolah">
								<div id="resepwrap">
									<input type="button" onclick="aktif_bhn_bku()" name="tambah_bahan_baku" id="tambah_bahan_baku" value="Tambah Bahan Baku" title="Tambah Data">
									<input type="button" onclick="aktif_resep()" name="resep" id="resep" value="Resep" title="Aktifkan Resep">
									<input type="button" id="atur_hidangan" name="atur_hidangan" onclick="atur_status()" value="Status Menu">
								</div>
								</div>
						</aside>						
						</br>
					</aside>
			</section>
			<input type="hidden" name="getno" id="getno">
			<input type="hidden" name="getidx" id="getidx" value="<?php echo $idx ?>">
			<input type="hidden" name="getnama" id="getnama">
			<input type="hidden" name="gettipe" id="gettipe">
			<input type="hidden" name="getharga" id="getharga">
			<input type="hidden" name="selekgettipe" id="selekgettipe">
			<input type="hidden" name="errormessage" id="errormessage">
			<input type="hidden" name="nilaiimage" id="nilaiimage">
			<input type="hidden" name="ubahresep" id="ubahresep">
			<input type="hidden" name="jumbaris" id="jumbaris">
			<input type="hidden" name="nobhnbaku" id="nobhnbaku">
			<div style="display:none"><input type="file" id="realupload"
				name="realupload" onchange="return ValidateSingleInput(this)"></div>
		</form>
		<?php
			if($_POST['kondisipop']){
				echo "<script>document.getElementById('kondisipop').value = '$kondisipop'</script>";
			}
		?>
		<script>
			var kondisi = new Array("<?php echo $tipemakanan ?>","<?php echo $nomor ?>","<?php echo $tipemkn ?>");
			var status_resep = "<?php echo $status_resep ?>";
			var status_bhn_baku = "<?php echo $status_bhn_baku ?>";
			var status_atur = "<?php echo $status_atur ?>";
			if(kondisi[0]!=''){
				document.getElementById(kondisi[0]).checked=true;
				document.getElementById('selekgettipe').value = kondisi[0];
			}else{
			  document.getElementById('all').checked=true;
			}
			if(kondisi[1]!=''){
				var idx = "<?php echo $idx ?>";
				for(j=0;j<=3;j++){
					document.getElementById("tabledata").rows[idx].cells[j].style.background = "#22593e";
					document.getElementById("tabledata").rows[idx].cells[j].style.color = "#fff";
				}
				document.getElementById('getno').value = kondisi[1];
				document.getElementById('gettipe').value = kondisi[2];
				if(document.getElementById('img').value != "ISI"){
					document.getElementById('unggah').disabled = false;
				  document.getElementById('delete').disabled = true;
				}else{
					document.getElementById('unggah').disabled = true;
				}
				document.getElementById('tambah').disabled = true;
			}else{
				document.getElementById('nama_hidangan').readOnly = true;
				document.getElementById('tipe').disabled = true;
				document.getElementById('harga_hidangan').readOnly = true;
				document.getElementById('unggah').disabled = true;
				document.getElementById('delete').disabled = true;
				document.getElementById('tambah').disabled = true;
				document.getElementById('resep').disabled = true;
				document.getElementById('resep').title = "Untuk mengisi resep, anda harus memastikan bahwa data hidangan sudah tersimpan di database.\n\nTombol resep akan aktif apabila anda menyeleksi (mengklik) data hidangan\npada tabel hidangan";
				document.getElementById('ubah').disabled = true;
				document.getElementById('hapus').disabled = true;
				document.getElementById('tbl_clear').disabled = true;
			}


			var jwpopup = document.getElementById("popupBox");
			var tablewrap = document.getElementById('tablewrap');
			var tambahbahanwrap = document.getElementById('tambahbahanwrap');
			var atur = document.getElementById('aturhidangan');
			var close0 = document.getElementsByClassName("close")[0];
			var close1 = document.getElementsByClassName("close")[1];
			var close2 = document.getElementsByClassName("close")[2];

			function olah_resep() {
				jwpopup.style.display = 'block';
				tablewrap.style.display = 'block';
			}

			function tambah_bahan(){
				jwpopup.style.display = 'block';
				tambahbahanwrap.style.display = 'block';
			}

			function atur_hidangan(){
				jwpopup.style.display = 'block';
				atur.style.display = 'block';
			}

			if(status_resep!=''){
				olah_resep();
			}

			if(status_bhn_baku!=''){
				tambah_bahan();
			}

			if(status_atur!=''){
				atur_hidangan();
			}

			close0.onclick = function(){
				jwpopup.style.display = 'none';
				tablewrap.style.display = 'none';
			}

			close1.onclick = function(){
				jwpopup.style.display = 'none';
				tambahbahanwrap.style.display = 'none';
			}

			close2.onclick = function(){
				jwpopup.style.display = 'none';
				atur.style.display = 'none';
			}

			window.onclick = function(event) {
			  if (event.target == jwpopup)  {
			    jwpopup.style.display = 'none';
					tablewrap.style.display = 'none';
					tambahbahanwrap.style.display = 'none';
					atur.style.display = 'none';
			  }
			}
		</script>
		<?php
			if(($_POST['tbl_tambah'])or($_POST['tbl_hapus'])or($_POST['tbl_ubah'])or
				 ($_POST['tbh_bhn_baku'])or($_POST['ubh_bhn_baku'])or($_POST['hps_bhn_baku'])or
			 	 ($_POST['simpanresep'])){
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
