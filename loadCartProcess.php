<?php

include "connection.php";
session_start();

$user = $_SESSION["u"];
$netTotal = 0;

$rs = Database::search("SELECT * FROM `cart` INNER JOIN `stock` ON `cart`.`stock_stock_id` = `stock`.`stock_id` 
INNER JOIN `product` ON `stock`.`product_id` = `product`.`id` INNER JOIN `color` ON `product`.`color_id` = `color`.`color_id` 
INNER JOIN `size` ON `product`.`size_id` = `size`.`size_id` WHERE `cart`.`user_id` = '" . $user["id"] . "'");

$num = $rs->num_rows;

if ($num > 0) {
    //Load Cart

?>
    <div class="mb-4 mt-5">
        <h3 class="text-center fw-bold">Shopping Cart</h3>
    </div>

    <?php
    for ($i = 0; $i < $num; $i++) {
        $d = $rs->fetch_assoc();

        $total = $d["price"] * $d["cart_qty"];
        $netTotal += $total; //$netTotal = $netTotal + $total

    ?>

<!-- Cart Item -->
<div class="col-lg-12 border border-3 rounded-5 p-2 mb-2 bg-info-subtle">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 d-flex align-items-center">
            <img src="<?php echo $d["path"] ?>" class="rounded-4 border border-1 border-warning" width="200px" alt="Product Image">
            <div class="ms-5">
                <input type="text" class="form-control mt-3" value="Product Name: <?php echo $d["name"]; ?>" disabled>
                <input type="text" class="form-control mt-3" value="Color: <?php echo $d["color_name"]; ?>" disabled>
                <input type="text" class="form-control mt-3" value="Size: <?php echo $d["size_name"]; ?>" disabled>
                <input type="text" class="form-control mt-3" value="Price: Rs <?php echo $d["price"]; ?>.00" disabled>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-light btn-sm me-2" onclick="decrementCartQty('<?php echo $d['cart_id'] ?>');">-</button>
                <input type="number" id="qty<?php echo $d['cart_id'] ?>" class="form-control col-2 text-center" style="max-width: 100px;" value="<?php echo $d["cart_qty"] ?>" disabled />
                <button class="btn btn-light btn-sm ms-2" onclick="incrementCartQty('<?php echo $d['cart_id'] ?>');">+</button>
            </div>
            <div class="d-flex justify-content-center">
                <h4 class="mb-0">Total: <span class="text-warning">Rs <?php echo $total ?></span></h4>
                <button class="btn btn-danger btn-sm ms-3" onclick="removeCart('<?php echo $d['cart_id'] ?>')">X</button>
            </div>
        </div>
    </div>
</div>

       
    <?php

    }
    ?>

    <div class="col-12 mt-4">
        <hr>
    </div>

    <!-- checkout -->
    <div class="d-flex flex-column align-items-end">
        <h6>Number of Items: <span class="text-info"><?php echo $num ?></span> </h6>
        <h5>Delivary Fee: <span class="text-muted">Rs: 500.00</span> </h5>
        <h3>Net Total: <span class="text-warning">Rs: <?php echo ($netTotal + 500) ?>.00</span> </h3>
        <button class="btn btn-success col-3 mt-1 mb-4" onclick="checkOut();">CHECKOUT</button>
    </div>
    <!-- checkout -->
<?php

} else {

?>
    <div class="col-12 text-center  mt-5">
        <h2>Your Cart is Empty!</h2>
        <a href="home.php" class="btn btn-primary mt-3">Start Shopping</a>
    </div>
<?php
}
