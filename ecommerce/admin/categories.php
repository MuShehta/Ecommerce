<?php

session_start();
$pagetitle = "Categories";
if (isset($_SESSION["username"])) {

    include "ini.php";
    $do = isset($_GET["do"]) ? $_GET["do"] : 'manage';

    if($do == 'manage') { 
            $stmt = $con->prepare("select * from categories");
            $stmt->execute();
            $rows = $stmt->fetchAll();

        ?>

        <h1 class="text-center">Manage Categories</h1> 
        <div class="container">
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Ordering</th>
                        <th scope="col">Visibility</th>
                        <th scope="col">Allow Comment</th>
                        <th scope="col">Allow ADS</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($rows as $row) {
                            echo "<tr>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td style='width : 50px;'>" . $row['description'] . "</td>";
                                echo "<td>" . $row['ordering'] . "</td>";
                                echo "<td>";
                                    if($row['visibility'] == 1){
                                        echo "<a class='btn btn-primary'>Visibile</a>";
                                    } else {
                                        echo "<a class='btn btn-danger'>UnVisibile</a>";
                                    }
                                echo "</td>";
                                echo "<td>";
                                    if($row['allow_comment'] == 1){
                                        echo "<a class='btn btn-primary'>Allow</a>";
                                    } else {
                                        echo "<a class='btn btn-danger'>Deny</a>";
                                    }
                                echo "</td>";
                                echo "<td>";
                                    if($row['allow_ads'] == 1){
                                        echo "<a class='btn btn-primary'>Allow</a>";
                                    } else {
                                        echo "<a class='btn btn-danger'>Deny</a>";
                                    }
                                echo "</td>";
                                echo "<td> 
                                    <a href='categories.php?do=edit&id=". $row['id'] ."' class='btn btn-success' style='margin-right : 5px;'>Edit</a>'
                                    <a href='categories.php?do=delet&id=". $row['id'] ."' class='btn btn-danger confirm'>Delete</a>";
                   
                                echo "</td>";
                            echo "</tr>";
                        }
                        

                    ?>
                    
                </tbody>
            </table>
            <a href='categories.php?do=add' class='btn btn-primary'>+ Add Categorie</a>
        </div>
        <?php
        

    } elseif ($do == "add") { ?>

            <h1 class="text-center memberh1">Add New Categories</h1>
            <div class="container">
                <form action="?do=insert" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="desc" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Ordering</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="ordering" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Visible</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="vis-yes" type="radio" name="visible" value="1" checked/>
                                <label for="vis-yes">Yes</lable>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visible" value="0"/>
                                <label for="vis-no">No</lable>
                            </div>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Allow Comment</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="com-yes" type="radio" name="comment" value="1" checked/>
                                <label for="com-yes">Yes</lable>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="comment" value="0"/>
                                <label for="com-no">No</lable>
                            </div>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Allow ADS</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="1" checked/>
                                <label for="ads-yes">Yes</lable>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="0"/>
                                <label for="ads-no">No</lable>
                            </div>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" class="btn btn-primary" value="Add Categories">
                        </div>
                    </div>
                </form>
            
            </div>



    <?php
    } elseif ($do == "insert") {
        echo "<h1 class='text-center'>Insert Categories</h1>";
        echo "<div class='container'>";
        if($_SERVER["REQUEST_METHOD"] == "POST") {
           $name = $_POST["name"];
           $desc = $_POST["desc"];
           $order = $_POST["ordering"];
           $visible = $_POST["visible"];
           $comment = $_POST["comment"];
           $ads = $_POST["ads"];
               
           //validate form
           $check = checkitem("name" , "categories" , $name);
           $errors = array();
            
            if (empty($name)) {$errors[] = "Name can't be empty"; }
            if ($check > 0) {$errors[] = "Categories has Taking"; }

            foreach ($errors as $error) {
                echo "<div class= 'alert alert-danger'>" . $error . "</div>";
            }

            if (empty($errors)) {
                $stmt = $con -> prepare("insert into categories (name, description, ordering , visibility , allow_comment , allow_ads) values (?,?,?,?,?,?)");
                $stmt -> execute(array($name , $desc , $order ,$visible ,$comment , $ads ));
                echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";     
            }

           

            echo "</div>";
        } else {
            "NO";
        }


    }elseif ($do == "edit") {
        $id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? intval($_GET["id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from categories where id = ?");
        $stmt -> execute(array($id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) { ?>
            <h1 class="text-center memberh1">Edit Member</h1>
            <div class="container">
                <form action="?do=update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="<?php echo $row['name'] ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="desc"  value="<?php echo $row['description'] ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Ordering</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="ordering"  value="<?php echo $row['ordering'] ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Visible</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="vis-yes" type="radio" name="visible" value="1" <?php if ($row["visibility"] == 1) {echo 'checked';} ?>/>
                                <label for="vis-yes">Yes</lable>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visible" value="0" <?php if ($row["visibility"] == 0) {echo 'checked';} ?>/>
                                <label for="vis-no">No</lable>
                            </div>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Allow Comment</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="com-yes" type="radio" name="comment" value="1" <?php if ($row["allow_comment"] == 1) {echo 'checked';} ?>/>
                                <label for="com-yes">Yes</lable>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="comment" value="0" <?php if ($row["allow_comment"] == 0) {echo 'checked';} ?>/>
                                <label for="com-no">No</lable>
                            </div>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Allow ADS</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="1" <?php if ($row["allow_ads"] == 1) {echo 'checked';} ?>/>
                                <label for="ads-yes">Yes</lable>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="0" <?php if ($row["allow_ads"] == 0) {echo 'checked';} ?>/>
                                <label for="ads-no">No</lable>
                            </div>    
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </form>
            
            </div>
        <?php }
        } elseif ($do == "update") {
                echo "<div class='container'>";
                echo "<h1 class='text-center'>Update</h1>";
        
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                   $id = $_POST["id"];
                   $name = $_POST["name"];
                   $desc = $_POST["desc"];
                   $order = $_POST["ordering"];
                   $visible = $_POST["visible"];
                   $comment = $_POST["comment"];
                   $ads = $_POST["ads"];
                 
                    
                    $stmt = $con -> prepare("update categories set name = ? , description = ? , ordering = ? , visibility= ? , allow_comment = ?  , allow_ads = ? where id = ?");
                    $stmt -> execute(array($name , $desc , $order , $visible , $comment , $ads , $id));
                    echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Update </div>";     
        
                   
        
                    echo "</div>";
        

        } else {
            echo "NO Such ID";
        }
    } elseif ($do = "delete") {

        echo "<h1 class='text-center'>Delete Categories</h1>";
        echo "<div class='container'>";
        $id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? intval($_GET["id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from categories where id = ?");
        $stmt -> execute(array($id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("delete from categories where id = :id");
            $stmt->bindparam(":id" , $id);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Categorie Delete</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No Such Categorie</div>";
        }
        echo "</div>";


    }
    



    include "include/template/footer.php";
} else {
    header ("Location: index.php");
    exit();
};

?>