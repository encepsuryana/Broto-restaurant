function kitchenopen(){
  document.getElementById("kitchen").type="submit";
  document.getElementById("kitchen").submit();
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

function changekondisi(){
  document.getElementById('prosesselect').type="submit";
  document.getElementById('prosesselect').click();
}

function atur_status(){
  document.getElementById('atur_hidangan').type="submit";
  document.getElementById('atur_hidangan').submit();
}

function aktif_bhn_bku(){
  document.getElementById('tambah_bahan_baku').type="submit";
  document.getElementById('tambah_bahan_baku').submit();
}

function aktif_resep(){
  document.getElementById('resep').type="submit";
  document.getElementById('resep').submit();
}

function log_out(){
  document.getElementById('logout').type="submit";
  document.getElementById('logout').submit();
}

function buat_data(){
  document.getElementById('img').src = 'img/image_not_found.png';
  document.getElementById('unggah').disabled = false;
  document.getElementById('tambah').disabled = false;
  document.getElementById('nama_hidangan').readOnly = false;
  document.getElementById('tipe').disabled = false;
  document.getElementById('harga_hidangan').readOnly = false;
  document.getElementById('tbl_clear').disabled = false;
  document.getElementById('ubah').disabled = true;
  document.getElementById('resep').disabled = true;
  document.getElementById('resep').title = "Untuk mengisi resep, anda harus memastikan bahwa data hidangan sudah tersimpan di database.\n\nTombol resep akan aktif apabila anda menyeleksi (mengklik) data hidangan\npada tabel hidangan";
  document.getElementById('hapus').disabled = true;
  document.getElementById('delete').disabled = true;
  document.getElementById('tbl_data_baru').disabled = true;
  document.getElementById('nama_hidangan').value = '';
  document.getElementById('tipe').value = '';
  document.getElementById('harga_hidangan').value = '';
  document.getElementById('no').value = '';

}

function clear_data(){
  document.getElementById('getno').value = '';
  document.getElementById('getnama').value = '';
  document.getElementById('gettipe').value = '';
  document.getElementById('getharga').value = '';
  document.getElementById('realupload').value = '';
  document.getElementById('img').src = 'img/image_not_found.png';
  document.getElementById('unggah').disabled = true;
  document.getElementById('tambah').disabled = true;
  document.getElementById('resep').disabled = true;
  document.getElementById('nama_hidangan').readOnly = true;
  document.getElementById('tipe').disabled = true;
  document.getElementById('harga_hidangan').readOnly = true;
  document.getElementById('ubah').disabled = true;
  document.getElementById('hapus').disabled = true;
  document.getElementById('delete').disabled = true;
  document.getElementById('tbl_data_baru').disabled = false;
  document.getElementById('nama_hidangan').value = '';
  document.getElementById('tipe').value = '';
  document.getElementById('harga_hidangan').value = '';
  document.getElementById('no').value = '';
  document.getElementById('tbl_clear').disabled = true;
  document.getElementById('checkMaster').click();
  deleteAll();
}

function tambah_dan_ubah_data(kondisi){
  var arraydata = new Array(
  document.getElementById('nama_hidangan').value,
  document.getElementById('tipe').value,
  document.getElementById('harga_hidangan').value);
  document.getElementById('getnama').value = arraydata[0];
  document.getElementById('gettipe').value = arraydata[1];
  document.getElementById('getharga').value = arraydata[2];
  if((arraydata[0]=='')||(arraydata[1]=='')||
     (arraydata[2]=='')){
       swal({
         title: "Info",
         text: "Masih terdapat form kosong !",
         type: "info",
         confirmButtonColor: "#2b5dcd",
         confirmButtonText: "OK",
         closeOnConfirm: true
       });
     }else{
       if(kondisi==1){
         document.getElementById('tambah').type="submit";
         document.getElementById('tambah').submit();
       }else{
         document.getElementById('ubah').type="submit";
         document.getElementById('ubah').submit();
       }
     }
}

function hapus_data(){
  var nama = document.getElementById("nama_hidangan").value;
  document.getElementById('getnama').value = '';
  document.getElementById('gettipe').value = '';
  var btn = document.getElementById('hapus');
  swal({
    title: "Hapus "+nama+" ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#2b5dcd",
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak",
    closeOnConfirm: false
  },
  function(isConfirm){
    if(isConfirm){
      btn.type="submit";
      btn.click();
    }
  });
}

function detail(no,tipe,idx){
  document.getElementById('getno').value = no;
  document.getElementById('getidx').value = idx;
  document.getElementById('gettipe').value = tipe;
  document.getElementById('klik').submit();
}

function select_radio(val){
  if(val=="all"){
    document.getElementById(val).checked = true;
    val = "";
  }else if(val=="'APP'"){
    document.getElementById(val).checked = true;
  }else if(val=="'MAC'"){
    document.getElementById(val).checked = true;
  }else if(val=="'DES'"){
    document.getElementById(val).checked = true;
  }else{
    document.getElementById(val).checked = true;
  }
  document.getElementById('selekgettipe').value = val;
  document.getElementById('klik').submit();
}

function validasi(val){
  val.value = val.value.replace(/[^0-9]/g,'');
  return true;
}

function unggah_img(){
  document.getElementById('realupload').click();
}

function delete_img(){
  var hapus = document.getElementById('delete');
  var unggah = document.getElementById('unggah');
  var img = document.getElementById('img');
  var nilai = document.getElementById("nilaiimage");
  swal({
    title: "Hapus foto ini ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#2b5dcd",
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak"
  },
  function(isConfirm){
    if(isConfirm){
      hapus.disabled = "true";
      unggah.disabled = "false";
      img.src = "img/image_not_found.png";
      nilai.value = "KOSONG";
    }
  });
}

function PreviewImage() {
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById('realupload').files[0]);
  oFReader.onload = function (oFREvent) {
    document.getElementById('img').src = oFREvent.target.result;
  };
  document.getElementById('nilaiimage').value = 'isi';
};

function ValidateSingleInput(oInput) {
  var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
  if (oInput.type == "file") {
      var sFileName = oInput.value;
       if (sFileName.length > 0) {
          var blnValid = false;
          for (var j = 0; j < _validFileExtensions.length; j++) {
              var sCurExtension = _validFileExtensions[j];
              if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                  blnValid = true;
                  break;
              }
          }

          if (!blnValid) {
              swal({
                title: "Error",
                text: "Maaf,"+sFileName+" ekstensi file ini tidak cocok,\nberikut ekstensi yang diizinkan "+_validFileExtensions.join(", "),
                type: "error",
                confirmButtonColor: "#2b5dcd",
                confirmButtonText: "OK",
                closeOnConfirm: true
              });
              oInput.value = "";
              return false;
          }else{
            document.getElementById('unggah').disabled = true;
            document.getElementById('delete').disabled = false;
            PreviewImage();
          }
      }
  }
  return true;
}

function save(){
  var kosong = false;
  var tbl = document.getElementById('dataresep');
  var row = tbl.rows.length-1;
  for(var i=1; i<=row;i++){
    if((document.getElementById('jumitem('+i+')').value=="")&&(document.getElementById('ket('+i+')').value=="")){
        kosong = true;
    }
  }
  if(!kosong){
    var jum = document.getElementById('dataresep');
    document.getElementById('jumbaris').value = jum.rows.length-1;
    document.getElementById('simpanresep').type="submit";
    document.getElementById('simpanresep').submit();
  }else{
    swal({
      title: "Info",
      text: "Terdapat kolom kosong\ndata tidak akan tersimpan",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }
}

function detail_bhn_baku(nobhn,no,nama){
  document.getElementById('namabhnbaku').value = nama;
  document.getElementById('nobhnbaku').value = nobhn;
  document.getElementById('nobhn').value = no;
}

function validasidata(){
  var nama = document.getElementById('namabhnbaku').value.toUpperCase();
  var tbl = document.getElementById('tablebahanbaku');
  var jumbaris = tbl.rows.length-1;
  var ada = false;
  var i = 1;
  while((i<=jumbaris)&&(!ada)){
    var namabahan = tbl.rows[i].cells[2].innerHTML;
    if(namabahan == nama){
      ada = true;
    }else{
      i++;
    }
  }
  if(ada){
    swal({
      title: "Info",
      text: namabahan+" sudah terdapat di database, dapat dilihat pada nomor "+i,
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
    return true;
  }else{
    return false;
  }
}

function tambahitem(){
  var fielddata = document.getElementById("namabhnbaku").value;
  if(fielddata==""){
    swal({
      title: "Info",
      text: "Kolom nama bahan baku masih kosong",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }else{
    var ada = validasidata();
    if(!ada){
      document.getElementById('tbh_bhn_baku').type="submit";
      document.getElementById('tbh_bhn_baku').submit();
    }
  }
}

function ubahitem(){
  if(document.getElementById("nobhn").value!=""){
    var ada = validasidata();
    if(!ada){
      document.getElementById('ubh_bhn_baku').type="submit";
      document.getElementById('ubh_bhn_baku').submit();
    }
  }else{
    swal({
      title: "Info",
      text: "Tidak ada data dipilih",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }
}

function hapusitem(){
  if(document.getElementById("nobhn").value!=""){
    var btn = document.getElementById("hps_bhn_baku");
    var nama = document.getElementById("namabhnbaku").value;
    swal({
      title: "Hapus "+nama+" ?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "Ya",
      cancelButtonText: "Tidak",
      closeOnConfirm: false,
    },
    function(isConfirm){
      if(isConfirm){
        btn.type="submit";
        btn.click();
      }
    });
  }else{
    swal({
      title: "Info",
      text: "Kolom nama bahan baku masih kosong",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }
}

function validasinomor(){
  if(document.getElementById('namabhnbaku').value=="")
  {
    document.getElementById('nobhn').value="";
    document.getElementById('nobhnbaku').value="";
  }
}
