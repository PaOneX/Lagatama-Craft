<?php

include "connection.php";

$pageno = 0;
$page = $_POST["pg"];
$product = $_POST["p"];
// echo($product);

if (0 != $page) {
    $pageno = $page;
} else {
    $pageno = 1;
}

$q = "SELECT * FROM `stock` INNER JOIN `product` ON `stock`.`product_id` = `product`.`id` WHERE `product`.`name` LIKE '%$product%'";
$rs = Database::search($q);
$num = $rs->num_rows;
// echo($num);

$results_per_page = 8;
$num_of_pages = ceil($num / $results_per_page);
// echo ($num_of_pages);

$page_results = ($pageno - 1) * $results_per_page;

$q2 = $q . " LIMIT $results_per_page OFFSET $page_results ";
$rs2 = Database::search($q2);
$num2 = $rs2->num_rows;
// echo($num2);

if ($num2 == 0) {
    //Not Available Stock
?>
    <div class="d-flex flex-column justify-content-center text-center mt-5">
        <h5>Search No Result</h5>
        <p>We're Sorry, We cannot find any matches for your search term..</p>
    </div>
    <?php
} else {
    // Load Result

    for ($i = 0; $i < $num2; $i++) {
        $d = $rs2->fetch_assoc();
    ?>
   <!-- Card load  -->
   <div class=" col-lg-3 col-md-6 col-12 mt-5 d-flex justify-content-center">
            <div class="card p-2 mt-3 shadow" style="width: 300px;">
                <a href="singleProductView.php?s=<?php echo $d["stock_id"] ?>"><img src="<?php echo $d["path"] ?>" class="card-img-top">
                <div class="card-body mb-0 btn">
                    <div class="border border-3 rounded-4 p-3 shadow text-center">
                        <h2 class="card-text btn fs-2 fw-bolder"><?php echo $d["name"] ?></h2>
                        <p class="card-text btn fs-4 fw-bolder"><?php echo $d["description"] ?></p>
                        <p class="card-text btn fs-4 fw-bold">Rs: <?php echo $d["price"] ?></p>
                        <div class="row mb-0">
                            <div class="mb-2">
                                <button class="btn btn-danger col-12 fw-bold " onclick="buyNow('<?php echo $d['stock_id'] ?>');">Buy</button>
                            </div>
                            <div>
                            </a>
                                <button class="btn btn-warning col-12 fw-bold ">Add to favurite</button>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
        </div>

        <!-- Card load  -->
    <?php
    }
    ?>

    <!-- pagination -->
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" <?php

                                                            if ($pageno <= 1) {
                                                                echo ("#");
                                                            } else {
                                                            ?> onclick="searchProduct( <?php echo ($pageno - 1) ?>);" <?php
                                                                                                                    }

                                                                                                                        ?>>Previous</a></li>
                <?php

                for ($y = 1; $y <= $num_of_pages; $y++) {

                    if ($y == $pageno) {

                ?>
                        <li class="page-item active">
                            <a class="page-link" onclick="searchProduct(<?php echo  $y ?> );"><?php echo $y ?></a>
                        </li>

                    <?php

                    } else {

                    ?>
                        <li class="page-item ">
                            <a class="page-link" onclick="searchProduct(<?php echo  $y ?> );"><?php echo $y ?></a>
                        </li>

                <?php

                    }
                }

                ?>


                <li class="page-item"><a class="page-link" <?php

                                                            if ($pageno >= $num_of_pages) {
                                                                echo ("#");
                                                            } else {
                                                            ?> onclick="loadProduct( <?php echo ($pageno + 1) ?>);" <?php
                                                                                                                }

                                                                                                                    ?>>Next</a></li>
            </ul>
        </nav>
    </div>

    <!-- pagination -->

<?php
}

?>