<?php
$pagetitle="LogIn";
include "ini.php";

// if (isset($_SESSION["user"])) {
//       header('Location: index.php');
//   }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {

    $user_name = $_POST["user"];
    $password = $_POST["pass"];
    $hash_password = sha1($password);
    
    //check if user is correct
    $stmt = $con->prepare("select user_name , password , user_id from users where user_name = ? and password = ?");
    $stmt -> execute(array($user_name , $hash_password));
    $row = $stmt -> fetch();
    $count = $stmt->rowCount();
    
    if ($count > 0)
    {
        $_SESSION["user"] = $user_name;
        $_SESSION["id"] = $row["user_id"];
        header('Location: index.php');
       
    }

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    $formerrors = array();
    $username = $_POST["user"];
    $password = $_POST["pass"];
    $email = $_POST["email"];
 
    if (isset($_POST["user"])) {
        $filteruser = filter_var($_POST["user"] , FILTER_SANITIZE_STRING);
        if (strlen($filteruser) < 4) {
            $formerrors[] = "User Must Be Greater Than 4";
        }
    }
    $check = checkitem("user_name" , "users" , $username);
    if (isset($_POST["email"])) {
        $filteremail = filter_var($_POST["email"] , FILTER_SANITIZE_EMAIL);
        if (filter_var($filteremail , FILTER_VALIDATE_EMAIL) != True) {
            $formerrors[] = "This email is not valid";
        }
        if ($check > 0) {$formerrors[] = "User Name has Taking"; }
        
    }
    if (empty($formerrors)) {
        $stmt = $con -> prepare("insert into users (user_name, email, password , reg_status) values (?,?,?,0)");
        $stmt -> execute(array($filteruser , $filteremail , sha1($password)));
        header("LOCATION:index.php");
    }
}




?>
<div class="container loginform">
    <h1 class="text-center"><span class="active" data-class="login">LogIn</span> | 
    <span data-class="signup">SingUp</span> </h1>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input class="form-control" type="text" name="user" autocomplete="off" placeholder="User Name" required="required">    
        <input class="form-control" type="password" name="pass" autocomplete="new-password" placeholder="Password" required="required">
        <input class="btn btn-primary btn-block" type="submit" value="Login" name="login">

    </form>
    <form class="signup hidden" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input class="form-control" type="text" name="user" autocomplete="off" placeholder="User Name" required="required">    
        <input class="form-control" type="password" name="pass" autocomplete="new-password" placeholder="Password" required="required">
        <input class="form-control" type="email" name="email" placeholder="E-mail" required="required">
        <input class="btn btn-primary btn-block" type="submit" value="Signup"  name="signup">

    </form>
    <div class="error text-center">
        <?php 
            if (!empty($formerrors)) {
                foreach ($formerrors as $row) {
                    echo $row . "<br>";
                }
            }
        ?>
    </div>
</div>




 <?php
include "include/template/footer.php";

 ?>