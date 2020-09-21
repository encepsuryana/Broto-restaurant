function deletelap(){
  var btn = document.getElementById('tbldelete');
  swal({
    title: "Hapus laporan ini ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak",
  },
  function(isConfirm){
    if(isConfirm){
      btn.type="submit";
      btn.click();
    }
  });
}

function refreshhalaman(){
  location.reload(true);
}

function lapbaru(){
  document.getElementById('newlapbtn').type= "submit";
  document.getElementById('newlapbtn').submit();
}

function simpanlaporan(){
  var tbl = document.getElementById('tabledetail');
  var jumrows = tbl.rows.length;
  document.getElementById('baris').value = jumrows;
  document.getElementById('tbl_simpan').type = "submit";
  document.getElementById('tbl_simpan').submit();
}

function hometbl(){
  document.getElementById('home').type="submit";
  document.getElementById('home').submit();
}

function detail_laporan(vala,valb,valc,vald){
  document.getElementById('textdetail1').value = vala;
  document.getElementById('textdetail2').value = valb;
  document.getElementById('textdetail3').value = valc;
  document.getElementById('textdetail4').value = vald;
  document.getElementById('detail_lap').type="submit";
  document.getElementById('detail_lap').click();
}

function setfield(kode,buat,anggaran){
  document.getElementById('kode').value = kode;
  document.getElementById('buat').value = buat;
  document.getElementById('anggaran').value = anggaran;
}

function kelolalaporan(){
  document.getElementById('kelola_laporan').type="submit";
  document.getElementById('kelola_laporan').submit();
}

function cari_proses(){
  if(document.getElementById('cari_nama_hidangan').value==""){
    swal({
      title: "Info",
      text: "Field pencarian masih kosong",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }else{
    document.getElementById('cari_hidangan').type="submit";
    document.getElementById('cari_hidangan').submit();
  }
}


function changekondisi(){
  document.getElementById('prosesselect').type="submit";
  document.getElementById('prosesselect').click();
}

function checkrow(val){
  if(document.getElementById('checkpop('+val+')').checked == true){
    document.getElementById('checkpop('+val+')').checked = false;
  }else{
    document.getElementById('checkpop('+val+')').checked = true;
  }
}

function centang_hidangan(val){
  var tbl = document.getElementById('tablecari');
  var rowlen = tbl.rows.length;
  var teks = "";
  for(j=1;j<rowlen;j++){
    if(document.getElementById('checkpop('+j+')').checked == true){
      teks = teks+document.getElementById('nohidangan('+j+')').innerHTML+'#';
    }
  }
  if(teks!=''){
    document.getElementById('temp_no_hidangan').value = teks;
    if(val==1){
      document.getElementById('aktifkan').type = "submit";
      document.getElementById('aktifkan').submit();
    }else{
      document.getElementById('nonaktifkan').type = "submit";
      document.getElementById('nonaktifkan').submit();
    }
  }else{
    swal({
      title: "Info",
      text: "Tidak ada data dicentang",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }
}

function log_out(){
  document.getElementById('logout').type="submit";
  document.getElementById('logout').submit();
}

function atur_status(){
  document.getElementById('atur_hidangan').type="submit";
  document.getElementById('atur_hidangan').submit();
}

function validasifield(kondisi){
  if((document.getElementById('tgl_beli').value=="")&&
  (document.getElementById('tgl_produksi').value=="")&&
  (document.getElementById('tgl_kadaluarsa').value=="")&&
  (document.getElementById('jum_batas').value=="")&&
  (document.getElementById('ket_batas').value=="")){
    swal({
      title: "Info",
      text: "Masih terdapat kolom kosong",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }else if(kondisi==1){
    document.getElementById('tbh_bts_bhn').type="submit";
    document.getElementById('tbh_bts_bhn').submit();
  }else{
    document.getElementById('ubh_bts_bhn').type="submit";
    document.getElementById('ubh_bts_bhn').submit();
  }
}

function validasihapus(){
  var btn = document.getElementById('hps_bts_bhn');
  swal({
    title: "Hapus data ini ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak",
  },
  function(isConfirm){
    if(isConfirm){
      btn.type="submit";
      btn.click();
    }
  });
}

function detail(no,nama,jumlah,ket){
  document.getElementById('no').value = no;
  document.getElementById('nama_bahan').value = nama;
  document.getElementById('jum_bahan').value = jumlah;
  document.getElementById('keterangan').value = ket;
}

function detail_batas_bhn(noreg,no,beli,produksi,kadaluarsa,jumlah,ket){
  document.getElementById('no_reg').value = noreg;
  document.getElementById('noindex').value = no;
  document.getElementById('tgl_beli').value = beli;
  document.getElementById('tgl_produksi').value = produksi;
  document.getElementById('tgl_kadaluarsa').value = kadaluarsa;
  document.getElementById('jum_batas').value = jumlah;
  document.getElementById('ket_batas').value = ket;
  document.getElementById('tbh_bts_bhn').disabled = true;
  document.getElementById('ubh_bts_bhn').disabled = false;
  document.getElementById('hps_bts_bhn').disabled = false;
}

function clear_field(){
  document.getElementById('noindex').value = "";
  document.getElementById('tgl_beli').value = "";
  document.getElementById('tgl_produksi').value = "";
  document.getElementById('tgl_kadaluarsa').value = "";
  document.getElementById('jum_batas').value = "";
  document.getElementById('ket_batas').value = "";
  document.getElementById('no_reg').value = "";
  document.getElementById('tbh_bts_bhn').disabled = false;
  document.getElementById('ubh_bts_bhn').disabled = true;
  document.getElementById('hps_bts_bhn').disabled = true;
}

function clear_data(stat){
  if(stat==1){
    document.getElementById('batal').type="submit";
    document.getElementById('batal').submit();
  }else{
    document.getElementById('tbl_batal').type="submit";
    document.getElementById('tbl_batal').submit();
  }
    //location.reload(false);
}

function checkall() {
  var checked = false;
  if(document.getElementById("master").checked == true)
    checked = true;
  var tbl = document.getElementById("tabledata");
  var rowLen = tbl.rows.length;
  for (var idx=1;idx<=rowLen;idx++) {
    if(checked){
      document.getElementById('check('+idx+')').checked = true;
    }else{
      document.getElementById('check('+idx+')').checked = false;
    }
  }
}

function checkdatapop(){
  var checked = false;
  if(document.getElementById("masterpop").checked == true)
    checked = true;
  var tbl = document.getElementById("tablecari");
  var rowLen = tbl.rows.length;
  for (var idx=1;idx<=rowLen;idx++) {
    if(checked){
      document.getElementById('checkpop('+idx+')').checked = true;
    }else{
      document.getElementById('checkpop('+idx+')').checked = false;
    }
  }
}


function checkthis(no){
  if(document.getElementById('check('+no+')').checked != true){
    document.getElementById('check('+no+')').checked = true;
  }
}

function validasinomor(){
  if((document.getElementById('tgl_beli').value=="")&&
    (document.getElementById('tgl_produksi').value=="")&&
    (document.getElementById('tgl_kadaluarsa').value=="")&&
    (document.getElementById('jum_batas').value=="")&&
    (document.getElementById('ket_batas').value==""))
  {
    document.getElementById('noindex').value="";
    document.getElementById('no_reg').value="";
    document.getElementById('tbh_bts_bhn').disabled = false;
  }
}

function validasi(val,tipe){
  val.value = val.value.replace(/[^0-9]/g,'');
  if(tipe==1){
    validasinomor();
  }
  return true;
}

function aturwaktu(no_bahan, nama){
  document.getElementById('temp_no_tabel_batas').value = no_bahan;
  document.getElementById('temp_nama_bahan').value = nama;
  document.getElementById('tbl('+no_bahan+')').type="submit";
  document.getElementById('tbl('+no_bahan+')').submit();
}

function simpan_data_bahan(){
  var tbl = document.getElementById("tabledata");
  var rowlen = tbl.rows.length;
  var string = "";
  for(i=1;i<=rowlen;i++){
    if(document.getElementById('check('+i+')').checked == true){
      string = string+document.getElementById('nobahan('+i+')').innerHTML+'#';
    }
  }
  if(string!=''){
    document.getElementById('temp_no_tabel').value = string;
    document.getElementById('simpan').type = "submit";
    document.getElementById('simpan').submit();
  }else{
    swal({
      title: "Info",
      text: "Tidak ada data yang diubah",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }
}
