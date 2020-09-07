<php session_start() ?>

<div class="upper-bar">
  <div class="container">
  <?php if (isset($_SESSION["user"])) {
    echo "Welcome " . $_SESSION["user"];
    echo '<a href="profile.php">My Profile</a>';
    echo '<a href="logout.php">Log Out</a>';
  }else {
 ?>
    <a href="login.php">
      <span class="">Login/SignIn</span>
    </a>
    
  </div>
  <?php } ?>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
  <a class="navbar-brand" href="index.php">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
    <?php
      $stmt = $con->prepare("select * from categories");
      $stmt->execute();
      $rows = $stmt->fetchAll();
      foreach ($rows as $row){
        echo "<li class='nav-item active'>";
          echo '<a class="nav-link" href="categories.php?cat_id='. $row["id"] .'&cat_name='.$row["name"] .'  ">' . $row["name"] . '</a>';
        echo "</li>";
      }
      ?>
      <!-- <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang("statistics") ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang("logs") ?></a>
      </li> -->
    </ul>
  </div>
  </div>
</nav>
    

