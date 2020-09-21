<?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  if(isset($_SESSION['pelanggan'])){
    header("Location: pelanggan.php");
  }else{
    $int = $_SESSION["akun"];
    session_destroy();
  }
?>

<html>
   <head>
     <!-- CSS -->
     <style>
       #preloader {
        position:fixed;
        top:0;
        left:0;
        right:0;
        bottom:0;
        background-color:#ffffff; /* change if the mask should have another color then white */
        z-index:99; /* makes sure it stays on top */
       }

       #status {
        width:200px;
        height:200px;
        position:absolute;
        left:50%; /* centers the loading animation horizontally one the screen */
        top:50%; /* centers the loading animation vertically one the screen */
        background-image:url("img/loading.gif"); /* path to your loading animation */
        background-repeat:no-repeat;
        background-position:center;
        margin:-100px 0 0 -100px; /* is width and height divided by two */
       }
     </style>

   </head>
   <body>
     <!-- Loading -->
     <div id="preloader">
      <div id="status"></div>
     </div>

     <!-- Konten web -->
     <?php
        if($int=="setakun"){
          echo '<meta http-equiv="refresh" content="1;url=akun.php">';
        }else{
          echo '<meta http-equiv="refresh" content="1;url=admin.php">';
        }
      ?>
     <!-- jQuery -->
     <script src="javascript/jquery.js"></script>

     <!-- Javascript -->
     <script type="text/javascript">
      $(window).load(function() { // makes sure the whole site is loaded
      $("#status").fadeOut(); // will first fade out the loading animation
      $("#preloader").delay(350).fadeOut("slow"); // will fade out the white DIV that covers the website.
      })
     </script>
   </body>
</html>
