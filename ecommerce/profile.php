<?php
$pagetitle="Profile";
include "ini.php";
?>


<h1 class= "text-center">Manage Data</h1>
        <div class="container">
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                    <tr>
                        <th scope="col">User Name</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Registers Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $myacc = make("*" , "users" , "user_id" , $_SESSION["id"]);
                        foreach($myacc as $row) {
                            echo "<tr>";
                                echo "<td>" . $row['user_name'] . "</td>";
                                echo "<td>" . $row['full_name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['reg_date'] . "</td>";
                                echo "<td> 
                                    <a href='member.php?do=edit&user_id=". $row['user_id'] ."' class='btn btn-success' style='margin-right : 5px;'>Edit</a>'
                                    <a href='member.php?do=delet&user_id=". $row['user_id'] ."' class='btn btn-danger confirm'>Delete</a>";
                                    if ($row['reg_status'] == 0) {
                                        echo "<a href='member.php?do=active&user_id=". $row['user_id'] ."' class='btn btn-primary' style='margin-left : 5px;'>Activate</a>"; 
                                    }   
                                echo "</td>";
                            echo "</tr>";
                        echo "</table>";
                        }



                        //start manage item
        $sess = $_SESSION["id"];
        $stmt = $con->prepare("SELECT items.* , users.user_name , categories.name AS cat_name FROM 
        items INNER JOIN users ON items.mem_id = users.user_id 
        INNER JOIN categories ON items.cat_id = categories.id where mem_id = $sess;
        ");
        $stmt->execute();
        $items = $stmt->fetchAll();


?> 
    <h1 class= "text-center">Manage Items</h1>
    <div class="container">
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Adding Date</th>
                    <th scope="col">Categories</th>
                    <th scope="col">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($items as $row) {
                        echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['description'] ."</td>";
                            echo "<td>" . $row['price'] ."$". "</td>";
                            echo "<td>" . $row['add_date'] . "</td>";
                            echo "<td>" . $row['cat_name'] . "</td>";
                            echo "<td> 
                                <a href='items.php?do=edit&item_id=". $row['item_id'] ."' class='btn btn-success' style='margin-right : 5px;'>Edit</a>'
                                <a href='items.php?do=delete&item_id=". $row['item_id'] ."' class='btn btn-danger confirm'>Delete</a>";   
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                        echo "</table>";
                        echo '<a class="btn btn-primary" href="additem.php">+ Add New Item<a>';

                    // start manage comments
            $sess = $_SESSION["id"];
            $stmt1 = $con->prepare("SELECT comments.* , users.user_name , items.name AS item_name FROM 
            comments INNER JOIN users ON comments.mem_id = users.user_id 
            INNER JOIN items ON comments.item_id = items.item_id where comments.mem_id = $sess;
            ");
            $stmt1->execute();
            $comments = $stmt1->fetchAll();
                ?>
        <h1 class= "text-center">Manage Comments</h1>
     <div class="container">
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                    <tr>
                        <th scope="col">Comment</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Adding Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($comments as $row) {
                            echo "<tr>";
                                echo "<td>" . $row['comment'] . "</td>";
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
include "include/template/footer.php"; 
 
 
 ?>