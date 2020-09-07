<?php
$no_navbar = "";
$pagetitle = "Home";
session_start();
//  if (isset($_SESSION["username"])) {
//      header('Location: dashboard.php');
//  }
 include "ini.php";


 // if user come from post method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_name = $_POST["user"];
    $password = $_POST["pass"];
    $hash_password = sha1($password);
    
    //check if user is correct
    $stmt = $con->prepare("select user_name , password , user_id from users where user_name = ? and password = ? and group_id = 1");
    $stmt -> execute(array($user_name , $hash_password));
    $row = $stmt -> fetch();
    $count = $stmt->rowCount();
    
    if ($count > 0)
    {
        $_SESSION["username"] = $user_name;
        $_SESSION["userid"] = $row["user_id"];
        header('Location: dashboard.php');
       
    }

}

?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" autocomplete="off" placeholder="User Name">    
    <input class="form-control" type="password" name="pass" autocomplete="new-password" placeholder="Password">
    <input class="btn btn-primary btn-block" type="submit" value="Login">

</form>

<?php include "include/template/footer.php"; ?>