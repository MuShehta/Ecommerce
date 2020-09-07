<?php
    
session_start();
$pagetitle = "Items";
if (isset($_SESSION["username"])) {
    include "ini.php";
    $do = isset($_GET["do"]) ? $_GET["do"] : 'manage';

    if ($do == "manage") {
        $query = '';
        if (isset($_GET["pand"])) {
        $query = "AND approve = 0";
        }

        $stmt = $con->prepare("SELECT items.* , users.user_name , categories.name AS cat_name FROM 
        items INNER JOIN users ON items.mem_id = users.user_id 
        INNER JOIN categories ON items.cat_id = categories.id;
        ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
    
    <h1 class= "text-center">Manage Items</h1>
    <div class="container">
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Adding Date</th>
                    <th scope="col">Member</th>
                    <th scope="col">Categories</th>
                    <th scope="col">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($rows as $row) {
                        echo "<tr>";
                            echo "<td>" . $row['item_id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['description'] ."</td>";
                            echo "<td>" . $row['price'] ."$". "</td>";
                            echo "<td>" . $row['add_date'] . "</td>";
                            echo "<td>" . $row['user_name'] . "</td>";
                            echo "<td>" . $row['cat_name'] . "</td>";
                            echo "<td> 
                                <a href='items.php?do=edit&item_id=". $row['item_id'] ."' class='btn btn-success' style='margin-right : 5px;'>Edit</a>'
                                <a href='items.php?do=delete&item_id=". $row['item_id'] ."' class='btn btn-danger confirm'>Delete</a>";
                                if ($row['approve'] == 0) {
                                    echo "<a href='items.php?do=approve&id=". $row['item_id'] ."' class='btn btn-primary' style='margin-left : 5px;'>Approved</a>"; 
                                }   
                            echo "</td>";
                        echo "</tr>";
                    }

                ?>
                
            </tbody>
        </table>
        <a class="btn btn-primary" href='items.php?do=add'>+ Add New Item</a>
        </div>
    <?php

    } elseif ($do == "approve") {
        echo "<h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";
        $id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? intval($_GET["id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from items where item_id = ?");
        $stmt -> execute(array($id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("update items set approve = 1 where item_id = :id");
            $stmt->bindparam(":id" , $id);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Item Approved</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No such Item</div>";
        }
        echo "</div>";


    }
    elseif ($do == "edit") {
        
        $id = (isset($_GET["item_id"]) && is_numeric($_GET["item_id"])) ? intval($_GET["item_id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from items where item_id = ?");
        $stmt -> execute(array($id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) { ?>
            <h1 class="text-center memberh1">Edit Item</h1>
            <div class="container">
                <form action="?do=update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $row["item_id"] ?>">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="<?php echo $row["name"] ?>" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="desc" class="form-control" value="<?php echo $row["description"] ?>" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">price</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="price" value="<?php echo $row["price"] ?>" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Member</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="member">
                                <option value='0'>...</option>
                                <?php 
                                    $stmt = $con->prepare("select * from users");
                                    $stmt -> execute();
                                    $rows = $stmt -> fetchAll();
                                    foreach ($rows as $row) {
                                        echo "<option value='" . $row["user_id"] . "'>" . $row["user_name"] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Categories</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="cat">
                                <option value='0'>...</option>
                                <?php 
                                    $stmt = $con->prepare("select * from categories");
                                    $stmt -> execute();
                                    $rows = $stmt -> fetchAll();
                                    foreach ($rows as $row) {
                                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="file">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </form>
            
            </div>
            
            <?php

            
            
                $id = (isset($_GET["item_id"]) && is_numeric($_GET["item_id"])) ? intval($_GET["item_id"]) : 0;
                $stmt = $con->prepare("SELECT comments.* , users.user_name , items.name AS item_name FROM 
                comments INNER JOIN users ON comments.mem_id = users.user_id 
                INNER JOIN items ON comments.item_id = items.item_id where comments.item_id = ?");
                $stmt->execute(array($id));
                 $item = $stmt->fetchAll();
        
        if (!empty ($item)) { ?> 
        <h1 class= "text-center">Manage [ <?php echo $item[0]['item_name'] ?> ] Comments</h1>
        <div class="container">
            <table class="table table-bordered text-center">
                <thead class="thead-dark">
                        <tr>
                            <th scope="col">Comment</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Adding Date</th>
                            <th scope="col">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($item as $row) {
                                echo "<tr>";
                                    echo "<td>" . $row['comment'] . "</td>";
                                    echo "<td>" . $row['user_name'] . "</td>";
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
        }

        }
    } elseif ($do == "update") {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Update</h1>";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
           $id = $_POST["id"];
           $name = $_POST["name"];
           $desc = $_POST["desc"];
           $price = $_POST["price"];
           $member = $_POST["member"];
           $cat = $_POST["cat"];

           $image = $_FILES["file"];
            $image_name = $image["name"];
            $image_size = $image["size"];
            $image_temp = $image["tmp_name"];
            $image_type = $image["type"];

            $img = $id . $image_name;

            move_uploaded_file($image_temp , "upload\\" . $img);
            
           //validate form
            
           $errors = array();
            if (empty($name)) {$errors[] = "Name can't be empty"; }
            if (empty($price)) {$errors[] = "Price can't be empty"; }
            if ($member == 0) {$errors[] = "Member can't be empty"; }
            if ($cat == 0) {$errors[] = "Categories can't be empty"; }

            foreach ($errors as $error) {
                echo "<div class= 'alert alert-danger'>" . $error . "</div>";
            }

            if (empty($errors)) {
                $stmt = $con -> prepare("update items set name = ? , description = ? , price = ? , mem_id = ? , cat_id = ? , image = ? where item_id = ?");
                $stmt -> execute(array($name , $desc , $price , $member , $cat , $img , $id));
                echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Update </div>";     
            }

           

            echo "</div>";
        } else {
            "NO";
        }

    }elseif ($do == "delete") {
        echo "<h1 class='text-center'>Delete Record</h1>";
        echo "<div class='container'>";
        $id = (isset($_GET["item_id"]) && is_numeric($_GET["item_id"])) ? intval($_GET["item_id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from items where item_id = ?");
        $stmt -> execute(array($id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("delete from items where item_id = :id");
            $stmt->bindparam(":id" , $id);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Item Deleted</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No such Item</div>";
        }
        echo "</div>";

    }
    elseif ($do == "add") { ?>
        <h1 class="text-center memberh1">Add New Item</h1>
            <div class="container">
                <form action="?do=insert" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="desc">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="price" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Country Of Made</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="country">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Statues</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="statue">
                                <option value='0'>...</option>
                                <option value='1'>New</option>
                                <option value='2'>Like New</option>
                                <option value='3'>Used</option>
                                <option value='4'>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Member</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="member">
                                <option value='0'>...</option>
                                <?php 
                                    $stmt = $con->prepare("select * from users");
                                    $stmt -> execute();
                                    $rows = $stmt -> fetchAll();
                                    foreach ($rows as $row) {
                                        echo "<option value='" . $row["user_id"] . "'>" . $row["user_name"] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Categories</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="cat">
                                <option value='0'>...</option>
                                <?php 
                                    $stmt = $con->prepare("select * from categories");
                                    $stmt -> execute();
                                    $rows = $stmt -> fetchAll();
                                    foreach ($rows as $row) {
                                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" class="btn btn-primary" value="+ Add Item">
                        </div>
                    </div>
                </form>
            
            </div>

        <?php
    } elseif ($do == "insert") {
        echo "<h1 class='text-center'>Insert Item</h1>";
        echo "<div class='container'>";
        if($_SERVER["REQUEST_METHOD"] == "POST") {
           $name = $_POST["name"];
           $desc = $_POST["desc"];
           $price = $_POST["price"];
           $country = $_POST["country"];
           $statue = $_POST["statue"];
           $member = $_POST["member"];
           $cat = $_POST["cat"];
           
           //validate form
           $errors = array();
            if (empty($name)) {$errors[] = "Name can't be empty"; }
            if (empty($price)) {$errors[] = "Price can't be empty"; }
            if (($statue) == 0) {$errors[] = "Statue can't be empty"; }
            if (($member) == 0) {$errors[] = "Member can't be empty"; }
            if (($cat) == 0) {$errors[] = "Categories can't be empty"; }
            
            foreach ($errors as $error) {
                echo "<div class= 'alert alert-danger'>" . $error . "</div>";
            }

            if (empty($errors)) {
                $stmt = $con -> prepare("insert into items (name, description, price, country_made, statue ,rating,cat_id , mem_id ) values (?,?,?,?,?,0,?,?)");
                $stmt -> execute(array($name , $desc , $price , $country , $statue,$cat,$member));
                echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";     
            }

           

            echo "</div>";
        } else {
            "NO";
        }


    }



    include "include/template/footer.php";

}else {
    header ("Location: index.php");
    exit();
};


?>