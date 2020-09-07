<?php
    
session_start();
$pagetitle = "Edit member";
if (isset($_SESSION["username"])) {

    include "ini.php";
    $query = '';
    if (isset($_GET["pand"])) {
        $query = "AND reg_status = 0";
    }
    $do = isset($_GET["do"]) ? $_GET["do"] : 'manage';

    if($do == 'manage') { 
            $stmt = $con->prepare("select * from users where group_id = 0 $query");
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?>
        
        <h1 class= "text-center">Manage Member</h1>
        <div class="container">
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Registers Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($rows as $row) {
                            echo "<tr>";
                                echo "<td>" . $row['user_id'] . "</td>";
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
                        }

                    ?>
                    
                </tbody>
            </table>
            <a class="btn btn-primary" href='member.php?do=add'>+ Add New Member</a>
            </div>
        
        
    <?php }elseif ($do=="active") {

        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        $user = (isset($_GET["user_id"]) && is_numeric($_GET["user_id"])) ? intval($_GET["user_id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from users where user_id = ?");
        $stmt -> execute(array($user));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("update users set reg_status = 1 where user_id = :user");
            $stmt->bindparam(":user" , $user);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Member Activate</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No such ID</div>";
        }
        echo "</div>";


    }elseif ($do == "add") {?> 
        <h1 class="text-center memberh1">Add New Member</h1>
            <div class="container">
                <form action="?do=insert" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">User Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" autocomplete="new-password" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="full" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" class="btn btn-primary" value="Add Member">
                        </div>
                    </div>
                </form>
            
            </div>

        <?php
    }elseif ($do == 'insert') {
        echo "<h1 class='text-center'>Insert Member</h1>";
        echo "<div class='container'>";
        if($_SERVER["REQUEST_METHOD"] == "POST") {
           $password = $_POST["password"];
           $user = $_POST["username"];
           $email = $_POST["email"];
           $name = $_POST["full"];
               
           //validate form
           $check = checkitem("user_name" , "users" , $user);
           $errors = array();
            if (empty($user)) {$errors[] = "User Name can't be empty"; }
            if (empty($email)) {$errors[] = "Email can't be empty"; }
            if (empty($name)) {$errors[] = "Full Name can't be empty"; }
            if ($check > 0) {$errors[] = "User Name has Taking"; }

            foreach ($errors as $error) {
                echo "<div class= 'alert alert-danger'>" . $error . "</div>";
            }

            if (empty($errors)) {
                $stmt = $con -> prepare("insert into users (user_name, full_name, email, password , reg_status) values (?,?,?,?,1)");
                $stmt -> execute(array($user , $name , $email , sha1($password)));
                echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";     
            }

           

            echo "</div>";
        } else {
            "NO";
        }


    }
    elseif ($do == 'edit') {
        $user = (isset($_GET["user_id"]) && is_numeric($_GET["user_id"])) ? intval($_GET["user_id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from users where user_id = ?");
        $stmt -> execute(array($user));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) { ?>
            <h1 class="text-center memberh1">Edit Member</h1>
            <div class="container">
                <form action="?do=update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $user ?>">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">User Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username" value="<?php echo $row["user_name"] ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                        <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" >
                            <input type="password" class="form-control" name="newpassword" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" value="<?php echo $row["email"] ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="full" value="<?php echo $row["full_name"] ?>" required="required">
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
        } else {
            echo "NO Such ID";
        }
    } elseif ($do == 'update') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Update</h1>";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
           $id = $_POST["userid"];
           $user = $_POST["username"];
           $email = $_POST["email"];
           $name = $_POST["full"];
           
           $stmt1 = $con->prepare("select user_name from users Where user_name = ? and user_id != ?");
           $stmt1 -> execute(array($user , $id));
           $count = $stmt1 -> rowCount();
          
           // check password
           $pass = (empty ($_POST["newpassword"])) ? $_POST["oldpassword"] : $pass = sha1($_POST["newpassword"]);    
            
           //validate form
            
           $errors = array();
           if ($count > 0) {$errors[] = "User Name has been taken"; }
            if (empty($user)) {$errors[] = "User Name can't be empty"; }
            if (empty($email)) {$errors[] = "Email can't be empty"; }
            if (empty($name)) {$errors[] = "Full Name can't be empty"; }

            foreach ($errors as $error) {
                echo "<div class= 'alert alert-danger'>" . $error . "</div>";
            }

            if (empty($errors)) {
                $stmt = $con -> prepare("update users set user_name = ? , full_name = ? , email = ? , password = ? where user_id = ?");
                $stmt -> execute(array($user , $name , $email , $pass , $id));
                echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Update </div>";     
            }

           

            echo "</div>";
        } else {
            "NO";
        }


    }elseif ($do == "delet") {

        echo "<h1 class='text-center'>Delete Record</h1>";
        echo "<div class='container'>";
        $user = (isset($_GET["user_id"]) && is_numeric($_GET["user_id"])) ? intval($_GET["user_id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from users where user_id = ?");
        $stmt -> execute(array($user));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("delete from users where user_id = :user");
            $stmt->bindparam(":user" , $user);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Record Delete</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No such ID</div>";
        }
        echo "</div>";

    }

    

    include "include/template/footer.php";

}else {
    header ("Location: index.php");
    exit();
};


?>