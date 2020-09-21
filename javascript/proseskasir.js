function prosessimpan(){
  document.getElementById("proses").type="submit";
  document.getElementById("proses").submit();
}

function batalkan(){
  document.getElementById("batal").type="submit";
  document.getElementById("batal").submit();
}

function log_out(){
  document.getElementById('logout').type="submit";
  document.getElementById('logout').click();
}

function generateharga(nopesan,nomeja,total){
  document.getElementById("nopesan").value = "Nomor Pemesanan : "+nopesan;
  document.getElementById("nomeja").value  = "Nomor Meja      : "+nomeja;
  document.getElementById("belanja").value = "Rp."+total;
  hidanganpesan(nopesan);
}

function validasi(val){
  val.value = val.value.replace(/[^0-9]/g,'');
  var totbelanja = document.getElementById("belanja").value;
  totbelanja = totbelanja.substr(3,totbelanja.length);
  var uangbayar = document.getElementById("uangbayar").value;
  var hasil = uangbayar - totbelanja;
  document.getElementById("uangbayar").value = "Rp."+val.value;
  document.getElementById("kembalian").value = "Rp."+(uangbayar-totbelanja);
  if((uangbayar-totbelanja)<0){
    document.getElementById("proses").disabled = true;
  }else{
    document.getElementById("proses").disabled = false;
  }
  return true;
}
