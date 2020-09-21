function kirimkues(){
  if((document.getElementById("subjek").value!="")&&
     (document.getElementById("textkonten").value!="")){
    document.getElementById("kuesbtn").type="submit";
    document.getElementById("kuesbtn").submit();
  }else{
    swal({
      title: "Pemberitahuan",
      text: "Form isian subjek atau konten masih kosong",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }
}

function backint(){
  var temp2 = document.getElementById("temp2").value;
  var temp4 = document.getElementById("temp4").value;
  if((temp2=="0")||(temp4!="1")){
    document.getElementById("back").type="submit";
    document.getElementById("back").value=" ";
    document.getElementById("back").submit();
  }else{
    swal({
      title: "Pemberitahuan",
      text: "Anda sudah melakukan pemesanan\ntekan tombol bayar untuk menyelesaikan transaksi",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }
}

function statusbayar(){
  document.getElementById("tbl_bayar").type="submit";
  document.getElementById("tbl_bayar").submit();
}

function closepopup(){
  document.getElementById("closebtn").type="submit";
  document.getElementById("closebtn").value=" ";
  document.getElementById("closebtn").submit();
}

function orderdatalist(){
  var tbl_list = document.getElementById('tablepesanan');
  var jumbaris = tbl_list.rows.length;
  var stats = 0;
  var konfirmasi;
  var ketemu = false;
  var temp = parseInt(document.getElementById("temp2").value);
  for(i=1;i<jumbaris;i++){
    var no = document.getElementById("nomor("+i+")").value;
    if(document.getElementById("jum("+no+")").value==""){
      if(!ketemu){
        ketemu = true;
      }
      document.getElementById("jum("+no+")").value = 1;
      document.getElementById("total("+no+")").value = document.getElementById("harga("+no+")").value;
      hitung = document.getElementById("totalbayar").value.substr(3);
      document.getElementById("totalbayar").value = "Rp. "+(parseInt(hitung) + parseInt(document.getElementById("total("+no+")").value));
    }
    temp = temp + parseInt(document.getElementById("total("+no+")").value);
  }
  if(ketemu){
    swal({
      title: "Peringatan",
      text : "Terdapat kolom jumlah kosong, pemesanan akan dianggap satu porsi, kirim pesanan ?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "Ya",
      cancelButtonText: "Tidak"
    },
    function(isConfirm){
      if(isConfirm){
        document.getElementById("temp").value = temp;
        document.getElementById("tbl_order_list").type="submit";
        document.getElementById("tbl_order_list").value=" ";
        document.getElementById("tbl_order_list").click();
      }
    });
    return false;
  }
  document.getElementById("temp").value = temp;
  document.getElementById("temp1").value = jumbaris-1;
  document.getElementById("tbl_order_list").type="submit";
  document.getElementById("tbl_order_list").value=" ";
  document.getElementById("tbl_order_list").submit();
}

function changetipe(valtipe){
  var app = document.getElementById('intone');
  var mac = document.getElementById('inttwo');
  var des = document.getElementById('intthree');
  var sd = document.getElementById('intfour');
  if(valtipe==""){
    app.style.display = "block";
    mac.style.display = "block";
    des.style.display = "block";
    sd.style.display = "block";
  }else if(valtipe=="APP"){
    app.style.display = "block";
    mac.style.display = "none";
    des.style.display = "none";
    sd.style.display  = "none";
  }else if(valtipe=="MAC"){
    mac.style.display = "block";
    app.style.display = "none";
    des.style.display = "none";
    sd.style.display  = "none";
  }else if(valtipe=="DES"){
    des.style.display = "block";
    mac.style.display = "none";
    app.style.display = "none";
    sd.style.display  = "none";
  }else{
    sd.style.display  = "block";
    mac.style.display = "none";
    app.style.display = "none";
    des.style.display = "none";
  }
}

function tempeljumlah(val,no_hid,harga_hid){
  validasi(val);
  document.getElementById("jum("+no_hid+")").value = val.value;
  var tot = document.getElementById("total("+no_hid+")").value;
  if(document.getElementById("totalbayar").value!=null){
    var valbayar = document.getElementById("totalbayar").value;
    var length = valbayar.length;
    bayar = valbayar.substr(3,length);
  }else {
    bayar = 0;
  }
  bayar = bayar - tot;
  if(val.value==""){
    document.getElementById("jumlah"+no_hid).style.width = "100%";
    document.getElementById("panelinfo"+no_hid).style.width = "50%";
    document.getElementById("total("+no_hid+")").value = 0;
    hasil = 0;
  }else{
    document.getElementById("panelinfo"+no_hid).style.width = "76%";
    document.getElementById("jumlah"+no_hid).style.width = "40%";
    hasil = val.value * parseInt(document.getElementById('harga('+no_hid+')').value);
    document.getElementById("total("+no_hid+")").value = hasil;
  }
  bayar = bayar + hasil;
  document.getElementById("totalbayar").value = "Rp. "+bayar;
  document.getElementById("temp").value = bayar;
}

function validasi(val){
  val.value = val.value.replace(/[^0-9]/g,'');
  return true;
}

function addNewRow(no_hid,nama_hid,hrg_hid) {
  var tbl = document.getElementById("tablepesanan");
  var jumrow = tbl.rows.length;
  var row = tbl.insertRow(tbl.rows.length);
  var td = new Array(document.createElement("td"),
                     document.createElement("td"),
                     document.createElement("td"),
                     document.createElement("td"),
                     document.createElement("td"),
                     document.createElement("td"),
                     document.createElement("td"));
  td[0].appendChild(generateIndex(row.rowIndex,no_hid));
  td[1].appendChild(generateCheckBoxno(no_hid));
  td[2].appendChild(generateCheckBox(row.rowIndex));
  td[3].appendChild(generateNama(row.rowIndex,no_hid,nama_hid));
  td[4].appendChild(generateJum(no_hid));
  td[5].appendChild(generateHarga(row.rowIndex,no_hid,hrg_hid));
  td[6].appendChild(generateTotal(row.rowIndex,no_hid));
  row.appendChild(td[0]);row.appendChild(td[1]);
  row.appendChild(td[2]);row.appendChild(td[3]);
  row.appendChild(td[4]);row.appendChild(td[5]);
  row.appendChild(td[6]);
}

function generateIndex(index,no_hid) {
  var idx   = document.createElement("input");
  idx.type  = "hidden";
  idx.name  = "nomor("+index+")";
  idx.id    = "nomor("+index+")";
  idx.value = ""+no_hid+"";
  idx.readOnly = "readOnly";
  return idx;
}

function generateCheckBoxno(no_hid) {
  var check  = document.createElement("input");
  check.type = "checkbox";
  check.name = "cekboxno("+no_hid+")";
  check.id   = "cekboxno("+no_hid+")";
  return check;
}

function generateCheckBox(index) {
  var check  = document.createElement("input");
  check.type = "checkbox";
  check.name = "cekbox("+index+")";
  check.id   = "cekbox("+index+")";
  check.onclick = function(){
    if(this.checked){
      document.getElementById("tbl_hapus_list").disabled = false;
    }else{
      var tbl_list = document.getElementById('tablepesanan');
      var jumbaris = tbl_list.rows.length;
      document.getElementById("tbl_hapus_list").disabled = true;
      for(i=1;i<jumbaris;i++){
        if(document.getElementById("cekbox("+i+")").checked){
          document.getElementById("tbl_hapus_list").disabled = false;
          break;
        }
      }
    }
  }
  return check;
}

function generateNama(index,no_hid,val) {
  var idx  = document.createElement("input");
  idx.type = "text";
  idx.name = "namahid("+no_hid+")";
  idx.id   = "namahid("+no_hid+")";
  idx.value= val;
  idx.onclick = function(){
    if(document.getElementById("cekbox("+index+")").checked){
      document.getElementById("cekbox("+index+")").checked = false;
      var tbl_list = document.getElementById('tablepesanan');
      var jumbaris = tbl_list.rows.length;
      document.getElementById("tbl_hapus_list").disabled = true;
      for(i=1;i<=jumbaris;i++){
        if(document.getElementById("cekbox("+i+")").checked){
          document.getElementById("tbl_hapus_list").disabled = false;
          break;
        }
      }
    }else{
      document.getElementById("tbl_hapus_list").disabled = false;
      document.getElementById("cekbox("+index+")").checked = true;
    }
  };
  idx.readOnly = "readOnly";
  return idx;
}

function generateJum(no_hid,val) {
  var idx  = document.createElement("input");
  idx.type = "text";
  idx.name = "jum("+no_hid+")";
  idx.id   = "jum("+no_hid+")";
  idx.maxLength = 2;
  idx.title = "Jumlah Pesan";
  idx.onkeyup = function (){
    this.value = this.value.replace(/[^0-9]/g,'');
    document.getElementById("jumlah"+no_hid).value = this.value;
    var tot = document.getElementById("total("+no_hid+")").value;
    if(document.getElementById("totalbayar").value!=null){
      var valbayar = document.getElementById("totalbayar").value;
      var length = valbayar.length;
      bayar = valbayar.substr(3,length);
    }else {
      bayar = 0;
    }
    bayar = bayar - tot;
    if(this.value==""){
      document.getElementById("total("+no_hid+")").value = 0;
      document.getElementById("jumlah"+no_hid).style.width = "100%";
      document.getElementById("panelinfo"+no_hid).style.width = "60%";
      hasil = 0;
    }else{
      document.getElementById("panelinfo"+no_hid).style.width = "76%";
      document.getElementById("jumlah"+no_hid).style.width = "40%";
      hasil = this.value * parseInt(document.getElementById('harga('+no_hid+')').value);
      document.getElementById("total("+no_hid+")").value = hasil;
    }
    bayar = bayar + hasil;
    document.getElementById("totalbayar").value = "Rp. "+bayar;
    document.getElementById("temp").value = bayar;
    return true;
  };
  if(val!=null){
    idx.value = val;
  }
  return idx;
}

function generateHarga(index,no_hid,val) {
  var idx  = document.createElement("input");
  idx.type = "text";
  idx.name = "harga("+no_hid+")";
  idx.id   = "harga("+no_hid+")";
  idx.readOnly = "readOnly";
  idx.value = val;
  idx.onclick = function(){
    if(document.getElementById("cekbox("+index+")").checked){
      document.getElementById("cekbox("+index+")").checked = false;
      var tbl_list = document.getElementById('tablepesanan');
      var jumbaris = tbl_list.rows.length;
      document.getElementById("tbl_hapus_list").disabled = true;
      for(i=1;i<=jumbaris;i++){
        if(document.getElementById("cekbox("+i+")").checked){
          document.getElementById("tbl_hapus_list").disabled = false;
          break;
        }
      }
    }else{
      document.getElementById("tbl_hapus_list").disabled = false;
      document.getElementById("cekbox("+index+")").checked = true;
    }
  };
  return idx;
}

function generateTotal(index,no_hid,val) {
  var idx  = document.createElement("input");
  idx.type = "text";
  idx.name = "total("+no_hid+")";
  idx.id   = "total("+no_hid+")";
  if(val!=null){
    idx.value = val;
  }else{
    idx.value = 0;
  }
  idx.readOnly = "readOnly";
  idx.onclick = function(){
    if(document.getElementById("cekbox("+index+")").checked){
      document.getElementById("cekbox("+index+")").checked = false;
      var tbl_list = document.getElementById('tablepesanan');
      var jumbaris = tbl_list.rows.length;
      document.getElementById("tbl_hapus_list").disabled = true;
      for(i=1;i<=jumbaris;i++){
        if(document.getElementById("cekbox("+i+")").checked){
          document.getElementById("tbl_hapus_list").disabled = false;
          break;
        }
      }
    }else{
      document.getElementById("tbl_hapus_list").disabled = false;
      document.getElementById("cekbox("+index+")").checked = true;
    }
  };
  return idx;
}

function bufferRow(table,cellno) {
  var tbl = document.getElementById("tablepesanan");
  var rowLen = tbl.rows.length;
  for (var idx=1;idx<rowLen;idx++) {
    var row = tbl.rows[idx];
    var cell = row.cells[cellno];
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
    }else if(cellno==2){
      pin_hidangan(row.cells[0].lastChild.value,"","",1);
    }
  }
}

function reIndex(table) {
  var tbl = document.getElementById("tablepesanan");
  var rowLen = table.rows.length;
  for (var idx=0;idx < rowLen;idx++) {
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
    td[1].appendChild(generateCheckBoxno(row.cells[0].lastChild.value));
    td[2].appendChild(generateCheckBox(row.rowIndex+1));
    td[3].appendChild(generateNama(row.rowIndex+1,row.cells[0].lastChild.value,row.cells[3].lastChild.value));
    td[4].appendChild(generateJum(row.cells[0].lastChild.value,row.cells[4].lastChild.value));
    td[5].appendChild(generateHarga(row.rowIndex+1,row.cells[0].lastChild.value,row.cells[5].lastChild.value));
    td[6].appendChild(generateTotal(row.rowIndex+1,row.cells[0].lastChild.value,row.cells[6].lastChild.value));
    rowTbl.appendChild(td[0]);
    rowTbl.appendChild(td[1]);
    rowTbl.appendChild(td[2]);
    rowTbl.appendChild(td[3]);
    rowTbl.appendChild(td[4]);
    rowTbl.appendChild(td[5]);
    rowTbl.appendChild(td[6]);
  }
}

function deleteAll(status) {
  var tbl = document.getElementById("tablepesanan");
  var rowLen = tbl.rows.length - 1;
  for (var idx=rowLen;idx > 0;idx--) {
    if(status==0){
      pin_hidangan(document.getElementById("nomor("+idx+")").value,"","",1);
    }
    tbl.deleteRow(idx);
  }
  document.getElementById("totalbayar").value = "Rp. "+document.getElementById("temp2").value;
}

function deleteRow(){
  var tble    = document.getElementById("tablepesanan");
  var error  = false;
  if (document.getElementById("checkmaster").checked == false)
    error    = true;
  var rowLen = tble.rows.length;
  for(var idx=1;idx<=rowLen;idx++){
    var row  = tble.rows[idx];
    var cell = row.cells[2];
    var node = cell.lastChild;
    if (node.checked == true) {
      error = false;
      break;
    }
  }
  if (error == true) {
    swal({
      title: "Pemberitahuan",
      text: "Tidak ada data terpilih pada list pesanan\nlakukan pemilihan dengan menyentang kotak isian",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
    return;
  }
  if (document.getElementById("checkmaster").checked == true) {
    deleteAll(0);
    document.getElementById("checkmaster").checked = false;
  } else {
    var table = document.createElement("table");
    bufferRow(table,2);
    deleteAll(1);
    reIndex(table);
    var tbl = document.getElementById("tablepesanan");
    var nbaris = tbl.rows.length;
    var bayar= parseInt(document.getElementById("temp2").value);
    for(i=1;i<nbaris;i++){
      no = document.getElementById("nomor("+i+")").value;
      bayar = bayar + parseInt(document.getElementById("total("+no+")").value);
    }
    document.getElementById("totalbayar").value = "Rp. "+bayar;
  }
}

function hapusdatalist(){
  document.getElementById("tbl_hapus_list").disabled =true;
  deleteRow();
  var tbl = document.getElementById("tablepesanan");
  var baris = tbl.rows.length
  if(baris==1){
    document.getElementById("tbl_order_list").disabled = true;
  }
}

function deleteRowpin() {
  var table = document.createElement("table");
  bufferRow(table,1);
  deleteAll();
  reIndex(table);
  var tbl = document.getElementById("tablepesanan");
  var nbaris = tbl.rows.length;
  var bayar= parseInt(document.getElementById("temp2").value);
  for(i=1;i<nbaris;i++){
    no = document.getElementById("nomor("+i+")").value;
    bayar = bayar + parseInt(document.getElementById("total("+no+")").value);
  }
  document.getElementById("totalbayar").value = "Rp. "+bayar;
}

function checkall(){
  var checked = false;
  if(document.getElementById("checkmaster").checked == true)
    checked = true;
  var tbl = document.getElementById("tablepesanan");
  var rowLen = tbl.rows.length;

  if(checked){
    document.getElementById("tbl_hapus_list").disabled = false;
  }else{
    document.getElementById("tbl_hapus_list").disabled = true;
  }
  for (var idx=1;idx<=rowLen;idx++){
    if(checked){
      document.getElementById("cekbox("+idx+")").checked = true;
    }else{
      document.getElementById("cekbox("+idx+")").checked = false;
    }
  }
}

function checkall1(){
  if(document.getElementById("checkmaster").checked == false){
    document.getElementById("checkmaster").checked = true;
    checked = true;
  }else{
    document.getElementById("checkmaster").checked = false;
    var checked = false;
  }
  var tbl = document.getElementById("tablepesanan");
  var rowLen = tbl.rows.length;

  if(checked){
    document.getElementById("tbl_hapus_list").disabled = false;
  }else{
    document.getElementById("tbl_hapus_list").disabled = true;
  }
  for (var idx=1;idx<=rowLen;idx++){
    if(checked){
      document.getElementById("cekbox("+idx+")").checked = true;
    }else{
      document.getElementById("cekbox("+idx+")").checked = false;
    }
  }
}

function pin_hidangan(i,namahidangan,hargahidangan,status){
  var colorpanel = document.getElementById('table'+i);
  var pin = document.getElementById('pin'+i);
  var wrapinfo = document.getElementById('wrapinfo'+i);
  var panelinfo = document.getElementById('panelinfo'+i);
  if(document.getElementById('check'+i).checked==true){
    document.getElementById('check'+i).checked=false;
    colorpanel.style.background ='#fff';
    colorpanel.style.color ='#000';
    pin.style.display = 'none';
    wrapinfo.style.background = '#dddddd';
    document.getElementById("jumlah"+i).value = "";
    document.getElementById("jumlah"+i).disabled=true;
    panelinfo.style.width = '100%';
    if(status==0){
      document.getElementById('cekboxno('+i+')').checked = true;
      deleteRowpin();
    }
    var tabelpesan = document.getElementById("tablepesanan");
    if(tabelpesan.rows.length==1){
      document.getElementById('tbl_order_list').disabled= true;
    }
  }else{
    var jum = document.getElementById("jumlah"+i);
    jum.style.width = "100%";
    document.getElementById('check'+i).checked=true;
    pin.style.display = 'block';
    colorpanel.style.background ='#000';
    wrapinfo.style.background = '#fff';
    colorpanel.style.color = '#fff';
    wrapinfo.style.color = '#000';
    panelinfo.style.width = '50%';
    document.getElementById('tbl_order_list').disabled= false;
    document.getElementById("jumlah"+i).disabled=false;
    addNewRow(i,namahidangan,hargahidangan);
  }
}
