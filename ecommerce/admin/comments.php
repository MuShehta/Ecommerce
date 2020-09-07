<?php
    
session_start();
$pagetitle = "Comments";
if (isset($_SESSION["username"])) {

    include "ini.php";
    $do = isset($_GET["do"]) ? $_GET["do"] : 'manage';

    if($do == 'manage') { 
        $stmt = $con->prepare("SELECT comments.* , users.user_name , items.name AS item_name FROM 
        comments INNER JOIN users ON comments.mem_id = users.user_id 
        INNER JOIN items ON comments.item_id = items.item_id;");
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?>
        
        <h1 class= "text-center">Manage Comments</h1>
     <div class="container">
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Comment</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Adding Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($rows as $row) {
                            echo "<tr>";
                                echo "<td>" . $row['c_id'] . "</td>";
                                echo "<td>" . $row['comment'] . "</td>";
                                echo "<td>" . $row['user_name'] . "</td>";
                                echo "<td>" . $row['item_name'] . "</td>";
                                echo "<td>" . $row['add_date'] . "</td>";
                                echo "<td> 
                                    <a href='comments.php?do=delete&c_id=". $row['c_id'] ."' class='btn btn-danger confirm'>Delete</a>";
                                    if ($row['statue'] == 0) {
                                        echo "<a href='comments.php?do=approve&c_id=". $row['c_id'] ."' class='btn btn-primary' style='margin-left : 5px;'>Approve</a>"; 
                                    }   
                                echo "</td>";
                            echo "</tr>";
                        }

                    ?>
                    
                </tbody>
            </table>
            </div>
        
        
    <?php
    }elseif ($do == "delete") {
        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";
        $c_id = (isset($_GET["c_id"]) && is_numeric($_GET["c_id"])) ? intval($_GET["c_id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from comments where c_id = ?");
        $stmt -> execute(array($c_id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("delete from comments where c_id = :c_id");
            $stmt->bindparam(":c_id" , $c_id);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Comment Deleted</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No such Comment</div>";
        }
        echo "</div>";

    } elseif ($do == "approve") {
        echo "<h1 class='text-center'>Approve Comment</h1>";
        echo "<div class='container'>";
        $c_id = (isset($_GET["c_id"]) && is_numeric($_GET["c_id"])) ? intval($_GET["c_id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from comments where c_id = ?");
        $stmt -> execute(array($c_id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("update comments set statue = 1 where c_id = :c_id");
            $stmt->bindparam(":c_id" , $c_id);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Comment Approved</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No such Comment</div>";
        }
        echo "</div>";
    }






include "include/template/footer.php";

}else {
header ("Location: index.php");
exit();
};


?>