<?php
$pagetitle="Index";
include "ini.php";

$stmt = $con->prepare("select * from items  where approve = 1");
$stmt->execute();
$items = $stmt->fetchAll();
?>

<div class="container">
    <div class="row">
<?php
foreach ($items as $row) {
    echo '<div class="col-sm-6 col-md-4">';
        echo '<div class="thumbnail box-item">';
            echo '<span class="price-span">' . $row["price"] . '</span>';
            echo '<img src="admin/upload/' . $row["image"] . ' " width="100%" />';
            echo '<div>';
                echo '<h3><a href = "items.php?item_id=' . $row["item_id"] .'">' . $row["name"] . '</a></h3>';
                echo '<p>' . $row["description"] . '</p>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
}
?>
    </div>
</div>


<?php

include "include/template/footer.php"; 
 
 
 ?>