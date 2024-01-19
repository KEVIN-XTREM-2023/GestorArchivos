<nav class="navbar navbar fixed-top " style="padding:0;background-color: #0E6655;"> 
  <div class="container-fluid mt-2 mb-2">
    <div class="col-lg-12">
      <div class="col-md-1 float-left" style="display: flex; ">
        <img width="50" src="assets/logo.png" style="background-color:aliceblue;padding:4px; border-radius:3px">
      </div>
      <div class="col-md-2 mt-2 float-right">
        <a class="text-light" href="ajax.php?action=logout"><?php echo $_SESSION['login_name'] ?> <i class="fa fa-power-off"></i></a>
      </div>
    </div>
  </div>
</nav>