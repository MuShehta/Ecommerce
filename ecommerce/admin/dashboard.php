<?php 
session_start();
$pagetitle = "Dashbord";
if (isset($_SESSION["username"])) {

    include "ini.php";
    
    ?>
     <!-- start dashboard  -->
     <h1 class="text-center">Dashbaord</h1>
     <div class="container text-center statues">
        <div class="row">
            <div class="col col-md-3">
                <a href="member.php">
                    <div class="stat" style="background: #3498db;">
                        Total Member<span><?php echo countitem("user_id" , "users"); ?></span>
                    </div>
                </a>
            </div>
            <div class="col col-md-3">
                <a href="member.php?pand">
                    <div class="stat" style="background: #c0392b;">
                        Pending Member<span><?php echo checkitem("reg_status" , "users" , "0"); ?></span>
                    </div>
                </a>
            </div>
            <div class="col col-md-3">
                <a href="items.php">
                    <div class="stat" style="background: #d35400;">
                        Total Items<span><?php echo countitem("item_id" , "items"); ?></span>
                    </div>
                </a>
            </div>
            <div class="col col-md-3">
                <a href="comments.php">
                    <div class="stat" style="background: #8e44ad;">
                        Total Comments<span><?php echo countitem("c_id" , "comments"); ?></span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- end dashboard -->

<?php
    include "include/template/footer.php";

}else {
    header ("Location: index.php");
    exit();
};


?>

