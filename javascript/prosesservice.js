function batal(){
  document.getElementById("tbl_batal").type="submit";
  document.getElementById("tbl_batal").submit();
}

function aturkode(stats){
  if((document.getElementById("shkodelap").value=="")||((stats==0)&&(document.getElementById("shkodelap").value.length==1))){
    var tgl = new Date();
    var tahun = tgl.getYear();
    tahun = (tahun<1000) ? (tahun +1900)+"" : tahun+"";
    tahun = tahun.substr(2,4);
    if(stats==1){
      var tbl = document.getElementById("tabledatalap");
      var baris = tbl.rows.length;
      if(baris!=0){
        var urutan = tbl.rows[baris-1].cells[1].innerHTML;
        baris = urutan.length;
        urutan = parseInt(urutan.substr(4,7))+1;
        while(urutan.length!=3){
          urutan = "0"+urutan;
        }
        document.getElementById("shkodelap").value = "CS"+tahun+urutan;
      }else{
        document.getElementById("shkodelap").value = "CS"+tahun
      }
    }else{
      document.getElementById("shkodelap").value = "CS"+tahun;
    }
  }
}

function simpanlap(){
  var kode = document.getElementById("shkodelap").value;
  var subjek = document.getElementById("shsubjeklap").value;
  var konten = document.getElementById("shkontenlap").value;
  if((kode=="")||(subjek=="")||(konten=="")){
    swal({
      title: "Pemberitahuan",
      text: "Masih terdapat form isian yang kosong",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }else if(kode.length<7){
    swal({
      title: "Pemberitahuan",
      text: "Kode laporan kurang kurang dari 7 digit huruf/angka",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }else{
    document.getElementById("tbl_simpan").type="submit";
    document.getElementById("tbl_simpan").value=" ";
    document.getElementById("tbl_simpan").submit();
  }
}

function lapbaru(){
  var tbl = document.getElementById("tabledata");
  var baris = tbl.rows.length;
  var temp = "";
  for(var i=1;i<=baris;i++){
    if(document.getElementById("check"+i).checked){
      temp = temp + document.getElementById("nopesan"+i).innerHTML+"#";
    }
  }
  if(temp==""){
    swal({
      title: "Pemberitahuan",
      text: "Tidak ada data terpilih pada tabel kuseioner\nlakukan pemilihan dengan menyentang kotak isian",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }else{
    for(var i=1;i<=baris;i++){
      document.getElementById("check"+i).disabled = true;
    }
    document.getElementById("master").disabled = true;
    document.getElementById("tempnokues").value = temp;
    document.getElementById("tbl_batal").style.display = "inline-block";
    document.getElementById("stslapbaru").value = 1;
    document.getElementById("tbl_baru").style.display = "none";
    document.getElementById("tbl_hapus2").disabled = true;
    document.getElementById("tbl_hapus1").style.display = "none";
    document.getElementById("tbl_simpan").style.display = "inline-block";
    document.getElementById("tbl_simpan").disabled = false;
    aturkode(1);
    document.getElementById("tablelap").style.display = "none";
    document.getElementById("showmessage1").style.height = "85%";
    document.getElementById("shkontenlap").style.height = "85%";
  }
}

function refreshdata(norad,radname,tglname,subjekname,kontenname,mastername,temp,tblname){
  var no = document.getElementById(norad).value;
  var idx = parseInt(document.getElementById(temp).value);
  var tbl = document.getElementById(tblname);
  if(!isNaN(idx)){
    if(tblname=="tabledatalap"){
      document.getElementById("tbl_simpan").style.display = "none";
      document.getElementById("tbl_simpan").disabled = true;
      if(idx%2==0){warna="#cce7fa";}else{warna = "#dcf1ff";}
      for(i=0;i<4;i++){
        tbl.rows[idx-1].cells[i].style.background = warna;
        tbl.rows[idx-1].cells[i].style.color = "inherit";
      }
    }else{
      if(idx%2==0){warna="#ffebce";}else{warna = "#ffce84";}
      for(i=1;i<4;i++){
        tbl.rows[idx-1].cells[i].style.background = warna;
        tbl.rows[idx-1].cells[i].style.color = "inherit";
      }
    }
  }
  if(no!=""){
    document.getElementById(radname+no).checked = false;
    document.getElementById(norad).value="";
  }
  document.getElementById(tglname).value = "";
  document.getElementById(subjekname).value = "";
  document.getElementById(kontenname).value = "";
  if(!document.getElementById(mastername).checked){
    document.getElementById(mastername).click();
    document.getElementById(mastername).click();
  }else{
    document.getElementById(mastername).click();
  }
}

function hapuslaporkues(tblname,tempname,cekname,kodeorpsn,tempno,btnhpsname){
  var tbl = document.getElementById(tblname);
  var baris = tbl.rows.length;
  var temp = "";
  var hitung = 0;
  if(kodeorpsn=="kodelap"){pager=""}else{pager="#"}
  var notbl = document.getElementById(tempname).value;
  for(var i=0;i<baris;i++){
    if(document.getElementById(cekname+(i+1)).checked){
      temp = temp + document.getElementById(kodeorpsn+(i+1)).innerHTML+pager;
      hitung++;
    }
  }
  if((temp=="")&&(notbl=="")){
    swal({
      title: "Pemberitahuan",
      text: "Tidak ada data terpilih",
      type: "info",
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "OK",
      closeOnConfirm: true
    });
  }else{
    var pesan;
    if(temp!=""){
      document.getElementById(tempno).value = temp;
      if(tempno=="tempnolap"){
        pesan = "Hapus "+hitung+" data laporan ?";
      }else{
        pesan = "Hapus "+hitung+" data kuesioner ?";
      }
    }else{
      temp = document.getElementById(kodeorpsn+notbl).innerHTML+pager;
      document.getElementById(tempno).value = temp;
      if(tempno=="tempnolap"){
        pesan = "Hapus laporan "+temp+" ?";
      }else{
        pesan = "Hapus kuesioner ini ?";
      }
    }
    swal({
      title: pesan,
      text: "Data laporan akan dihapus permanen",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#2b5dcd",
      confirmButtonText: "Ya",
      cancelButtonText: "Tidak",
    },
    function(isConfirm){
      if(isConfirm){
        document.getElementById(btnhpsname).type="submit";
        document.getElementById(btnhpsname).value=" ";
        document.getElementById(btnhpsname).click();
      }
    });
  }
}

function logoutint(){
  document.getElementById("logout").type="submit";
  document.getElementById("logout").submit();
}

function selekdb(){
  document.getElementById("seleksi").type="submit";
  document.getElementById("seleksi").click();
}

function rubahwarna(radname,tblname,tempname){
  var idx = document.getElementById(tempname).value;
  if(idx!=""){
    var tbl = document.getElementById(tblname);
    if(tblname=="tabledata"){
      if(idx%2==0){warna = "#a7a7a7";}else{warna = "#dcdcdc";}
      for(i=1;i<4;i++){
        tbl.rows[parseInt(idx)-1].cells[i].style.background = warna;
        tbl.rows[parseInt(idx)-1].cells[i].style.color = "inherit";
      }
    }else{
      if(idx%2==0){warna = "#cce7fa";}else{warna = "#dcf1ff";}
      for(i=0;i<4;i++){
        tbl.rows[parseInt(idx)-1].cells[i].style.background = warna;
        tbl.rows[parseInt(idx)-1].cells[i].style.color = "inherit";
      }
    }
  }
}

function detail_kues(idx,tblname){
  var tbl = document.getElementById(tblname);
  if(tblname=="tabledatalap"){
    if(!document.getElementById("radlap"+idx).checked){
      document.getElementById("radlap"+idx).click();
      for(i=0;i<4;i++){
        tbl.rows[idx-1].cells[i].style.background = "#003955";
        tbl.rows[idx-1].cells[i].style.color = "yellow";
      }
      document.getElementById("temp1").value = idx;
      document.getElementById("noradio1").value = idx;
      document.getElementById("shkodelap").value = tbl.rows[idx-1].cells[1].innerHTML;
      document.getElementById("shsubjeklap").value = tbl.rows[idx-1].cells[3].innerHTML;
      document.getElementById("shkontenlap").value = tbl.rows[idx-1].cells[4].innerHTML;
      document.getElementById("tempnolaplama").value = tbl.rows[idx-1].cells[1].innerHTML;
      document.getElementById("tbl_simpan").style.display = "inline-block";
      document.getElementById("tbl_simpan").disabled = false;
    }else{
      if(idx%2==0){warna="#cce7fa";}else{warna = "#dcf1ff";}
      for(i=0;i<4;i++){
        tbl.rows[idx-1].cells[i].style.background = warna;
        tbl.rows[idx-1].cells[i].style.color = "inherit";
      }
      document.getElementById("temp1").value = "";
      document.getElementById("radlap"+idx).click();
      document.getElementById("radlap"+idx).checked=false;
      document.getElementById("noradio1").value = "";
      document.getElementById("shkodelap").value = "";
      document.getElementById("shsubjeklap").value = "";
      document.getElementById("shkontenlap").value = "";
      document.getElementById("tempnolaplama").value = "";
      document.getElementById("tbl_simpan").style.display = "none";
      document.getElementById("tbl_simpan").disabled = true;
    }
  }else{
    if(!document.getElementById("rad"+idx).checked){
      document.getElementById("rad"+idx).click();
      for(i=1;i<4;i++){
        tbl.rows[idx-1].cells[i].style.background = "#2f2f2f";
        tbl.rows[idx-1].cells[i].style.color = "white";
      }
      document.getElementById("temp2").value = idx;
      document.getElementById("noradio2").value = idx;
      document.getElementById("shtgl").value = tbl.rows[idx-1].cells[2].innerHTML;
      document.getElementById("shsubjek").value = tbl.rows[idx-1].cells[3].innerHTML;
      document.getElementById("shkonten").value = tbl.rows[idx-1].cells[4].innerHTML;
    }else{
      if(idx%2==0){warna="#ffebce";}else{warna = "#ffce84";}
      for(i=1;i<4;i++){
        tbl.rows[idx-1].cells[i].style.background = warna;
        tbl.rows[idx-1].cells[i].style.color = "inherit";
      }
      document.getElementById("temp2").value = "";
      document.getElementById("rad"+idx).click();
      document.getElementById("rad"+idx).checked = false;
      document.getElementById("noradio2").value = "";
      document.getElementById("shtgl").value = "";
      document.getElementById("shsubjek").value = "";
      document.getElementById("shkonten").value = "";
    }
  }
}

function checkall(stats,id,tablename,cekname){
  if(stats==1){
    document.getElementById(id).click();
  }
  var tbl = document.getElementById(tablename);
  var baris = tbl.rows.length;
  var cek = false;
  if(document.getElementById(id).checked){
    cek = true;
  }
  for(var i=0;i<baris;i++){
    document.getElementById(cekname+(i+1)).checked = cek;
  }
}
