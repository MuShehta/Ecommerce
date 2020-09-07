<?php
$pagetitle="Show Item";
include "ini.php";

$id = (isset($_GET["item_id"]) && is_numeric($_GET["item_id"])) ? intval($_GET["item_id"]) : 0;
if ($id == 0) {
    echo "Fuck You";
    header("Location: index.php");
}
$stmt = $con -> prepare("select * from items where item_id = $id");

$stmt -> execute();

$items = $stmt -> fetch();

$stmt = $con->prepare("SELECT comments.* , users.user_name , items.name AS item_name FROM 
        comments INNER JOIN users ON comments.mem_id = users.user_id 
        INNER JOIN items ON comments.item_id = items.item_id where comments.item_id = $id");
            $stmt->execute();
            $comments = $stmt->fetchAll();
?>

<h1 class="text-center"><?php echo $items["name"] ?> </h1>
<?php
        echo "<div class='container'>";
            echo '<div class="col-sm-6 col-md-4">';
                echo '<div class="thumbnail box-item">';
                    echo '<span class="price-span">' . $items["price"] . '</span>';
                    echo '<img src="test2.jpg" width="100%" />';
                    echo '<div>';
                        echo '<h3>' . $items["name"] . '</h3>';
                        echo '<p>' . $items["description"] . '</p>';
                        echo '<h4>' . $items["country_made"] . '</h4>';
                        echo '<p>' . $items["add_date"] . '</p>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '<hr>';
        ?>
            <h1>Add Comment</h1>
            <form class="add" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            <input class="form-control" type="text" name="comment" autocomplete="off" placeholder="Type Comment" required="required">    
            <input class="btn btn-primary btn-block" type="submit" value="Add" name="add">

    </form>

        <?php
        echo '<hr>';

        foreach ($comments as $row) {
            echo '<div class="row">';
                echo '<div class="col-md-2">'; 
                    echo '<h5>' . $row["user_name"] . ' :</h5>';
                echo '</div>';
                echo '<div class="col-md-6">'; 
                    echo '<p>' . $row["comment"] . '</p>';
                echo '</div>';
            echo '</div>';
        }

        echo '</div>';

        


        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])){
            $comment = $_POST["comment"];
            $id = (isset($_GET["item_id"]) && is_numeric($_GET["item_id"])) ? intval($_GET["item_id"]) : 0;
            $stmt = $con->prepare("insert into comments (comment  , item_id , mem_id , statue) values(?,?,?,1)");
            $stmt -> execute(array($comment ,$id , $_SESSION["id"] ));
            header("Location: items.php?item_id=$id");
        }


include "include/template/footer.php"; 
 
 
 ?>