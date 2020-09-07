<php session_start() ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php"><?php echo lang("home"); ?> <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="categories.php"><?php echo lang("categories") ?></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="items.php"><?php echo lang("items") ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="member.php"><?php echo lang("members") ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="comments.php"><?php echo lang("comment") ?></a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang("statistics") ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang("logs") ?></a>
      </li> -->
    </ul>
    <div class="dropdown nav navbar-nav navbar-right">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Mohamed
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="member.php?do=edit&user_id=<?php echo $_SESSION['userid'] ?>">Edit Profile</a>
          <a class="dropdown-item" href="#">setting</a>
          <a class="dropdown-item" href="../index.php">Show Site</a>
          <a class="dropdown-item" href="logout.php">log out</a>
        </div>
      </div>
  </div>
  </div>
</nav>
    

