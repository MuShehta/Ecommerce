<?php
$pagetitle="Index";
include "ini.php";
$do = isset($_GET["do"]) ? $_GET["do"] : 'manage';
if ($do == "manage") {
?>
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
}
        if ($do == "insert") {
            echo "<h1 class='text-center'>Insert Item</h1>";
        echo "<div class='container'>";
        if($_SERVER["REQUEST_METHOD"] == "POST") {
           $name = filter_var($_POST["name"] , FILTER_SANITIZE_STRING);
           $desc = filter_var($_POST["desc"] , FILTER_SANITIZE_STRING);
           $price = filter_var($_POST["price"] , FILTER_SANITIZE_NUMBER_INT);
           $country = filter_var($_POST["country"] , FILTER_SANITIZE_STRING);
           $statue = filter_var($_POST["statue"] , FILTER_SANITIZE_NUMBER_INT);
           $cat = filter_var($_POST["cat"] , FILTER_SANITIZE_NUMBER_INT);
           
           //validate form
           $errors = array();
            if (empty($name)) {$errors[] = "Name can't be empty"; }
            if (empty($price)) {$errors[] = "Price can't be empty"; }
            if (($statue) == 0) {$errors[] = "Statue can't be empty"; }
            if (($cat) == 0) {$errors[] = "Categories can't be empty"; }
            
            foreach ($errors as $error) {
                echo "<div class= 'alert alert-danger'>" . $error . "</div>";
            }

            if (empty($errors)) {
                $stmt = $con -> prepare("insert into items (name, description, price, country_made, statue ,rating,cat_id , mem_id ) values (?,?,?,?,?,0,?,?)");
                $stmt -> execute(array($name , $desc , $price , $country , $statue,$cat,$_SESSION["id"]));
                echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";     
            }

           

            echo "</div>";
        } else {
            "NO";
        }


    }

include "include/template/footer.php"; 
 
 
 ?>