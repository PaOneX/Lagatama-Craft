<?php
include "connection.php";

$pageno = 0;
$page = $_POST["pg"];

$color = $_POST["co"];
$cat = $_POST["cat"];
$brand = $_POST["b"];
$size = $_POST["s"];
$minPrice = $_POST["min"];
$maxPrice = $_POST["max"];

// echo ($minPrice);

$status = 0; // No condition

if (0 != $page) {
    $pageno = $page;
} else {
    $pageno = 1;
}

$q = "SELECT * FROM `stock` 
INNER JOIN `product` ON `stock`.`product_id`=`product`.`id` 
INNER JOIN `brand` ON `product`.`brand_id`=`brand`.`brand_id`
INNER JOIN `color` ON `product`.`color_id`=`color`.`color_id` 
INNER JOIN `category` ON `product`.`category_id`=`category`.`cat_id` 
INNER JOIN `size` ON `product`.`size_id`=`size`.`size_id`";

// Search by color------------------------------------------------------------------------

if ($status == 0 && $color != 0) {
    // 1st time search by color (WHERE)
    $q .= " WHERE `color`.`color_id`='" . $color . "'";
    $status = 1;
} elseif ($status != 0 && $color != 0) {
    // 2nd time search by color (AND)
    $q .= " AND `color`.`color_id`='" . $color . "'";
}

// Search by color-------------------------------------------------------------------------




// Search by Category----------------------------------------------------------------------

if ($status == 0 && $cat != 0) {
    // 1st time search by color (WHERE)
    $q .= " WHERE `category`.`cat_id`='" . $cat . "'";
    $status = 1;
} elseif ($status != 0 && $cat != 0) {
    // 2nd time search by color (AND)
    $q .= " AND `category`.`cat_id`='" . $cat . "'";
}

// Search by Category----------------------------------------------------------------------


// Search by Brand-------------------------------------------------------------------------

if ($status == 0 && $brand != 0) {
    // 1st time search by color (WHERE)
    $q .= " WHERE `brand`.`brand_id`='" . $brand . "'";
    $status = 1;
} elseif ($status != 0 && $brand != 0) {
    // 2nd time search by color (AND)
    $q .= " AND `brand`.`brand_id`='" . $brand . "'";
}


// Search by Brand-------------------------------------------------------------------------






// Search bySize---------------------------------------------------------------------------

if ($status == 0 && $size != 0) {
    // 1st time search by color (WHERE)
    $q .= " WHERE `size`.`size_id`='" . $size . "'";
    $status = 1;
} elseif ($status != 0 && $size != 0) {
    // 2nd time search by color (AND)
    $q .= " AND `size`.`size_id`='" . $size . "'";
}

// Search bySize---------------------------------------------------------------------------



// Search by Min Price---------------------------------------------------------------------
if (!empty($minPrice) && empty($maxPrice)) {
    if ($status == 0) {
        $q .= " WHERE `stock`.`price` >= '" . $minPrice . "' ORDER BY `stock`.`price` ASC";
        $status = 1;
    } elseif ($status != 0) {
        $q .= " AND `stock`.`price` >= '" . $minPrice . "' ORDER BY `stock`.`price` ASC";
    }
}
// Search by Min Price---------------------------------------------------------------------



// Search by MaxPrice----------------------------------------------------------------------
if (empty($maxPrice) && !empty($maxPrice)) {
    if ($status == 0) {
        $q .= " WHERE `stock`.`price` <= '" . $maxPrice . "' ORDER BY `stock`.`price` ASC";
        $status = 1;
    } elseif ($status != 0) {
        $q .= " AND `stock`.`price` <= '" . $minPrice . "' ORDER BY `stock`.`price` ASC";
    }
}
// Search by MaxPrice----------------------------------------------------------------------




// Search by Price range-------------------------------------------------------------------
if (!empty($maxPrice) && !empty($maxPrice)) {
    if ($status == 0) {
        $q .= " WHERE `stock`.`price` BETWEEN '" . $minPrice . "' AND '" . $maxPrice . "' ORDER BY `stock`.`price` ASC";
        $status = 1;
    } elseif ($status != 0) {
        $q .= " AND `stock`.`price` BETWEEN '" . $minPrice . "' AND '" . $maxPrice . "' ORDER BY `stock`.`price` ASC";
    }
}

// Search by Price range-------------------------------------------------------------------

$rs = Database::search($q);
$num = $rs->num_rows;

$results_per_page = 12;
$num_of_pages = ceil($num / $results_per_page);
$page_results = ($pageno - 1) * $results_per_page;

$q2 = $q . " LIMIT $results_per_page OFFSET $page_results";
$rs2 = Database::search($q2);
$num2 = $rs2->num_rows;

if ($num2 == 0) {
    # Search No result
?>
    <div class="d-flex align-items-center flex-column mt-5">
        <h5>Search No Results</h5>
        <p>We're Sorry , We cannot find any matches for your search term...</p>
    </div>
    <?php
} else {
    # Load page
    for ($i = 0; $i < $num2; $i++) {
        $d = $rs2->fetch_assoc();

    ?>
       <!-- Card load  -->
       <div class=" col-lg-4 col-md-6 col-12 mt-5 d-flex justify-content-center">
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



    <div class="mt-5 mb-4">

        <!-- pagination -->
        <nav aria-label="Page navigation example ">
            <ul class="pagination justify-content-center">
                <!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li> -->

                <li class="page-item">
                    <a class="page-link" <?php

                                            if ($pageno <= 1) {
                                                echo ("#");
                                            } else {
                                            ?>onclick="advSearchProduct(<?php echo ($pageno - 1) ?>);" <?php
                                                                                                    }
                                                                                                        ?> aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($y = 1; $y <= $num_of_pages; $y++) {

                    if ($y == $pageno) {
                ?>
                        <li class="page-item active">
                            <a class="page-link" onclick="advSearchProduct(<?php echo $y ?>);"><?php echo $y ?></a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="page-item">
                            <a class="page-link" onclick="advSearchProduct(<?php echo $y ?>);"><?php echo $y ?></a>
                        </li>
                <?php
                    }
                }
                ?>



                <li class="page-item">
                    <a class="page-link" <?php

                                            if ($pageno >= $num_of_pages) {
                                                echo ("#");
                                            } else {
                                            ?>onclick="advSearchProduct(<?php echo ($pageno + 1) ?>);" <?php
                                                                                                    }
                                                                                                        ?> aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- pagination -->

    </div>
<?php
}
