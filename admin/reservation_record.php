<?php
session_start();
include('config/config.php');

// Cancel Order
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM  reserve  WHERE  reservation_id = ?";
    $stmt = $mysqli->prepare($adn);
    if (!$stmt) {
        // Error handling if prepare() fails
        die("Error: " . $mysqli->error);
    }
    $stmt->bind_param('s', $id);
    $stmt->execute();
    if ($stmt->error) {
        // Error handling if execute() fails
        die("Error: " . $stmt->error);
    }
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=payments.php");
    } else {
        $err = "Try Again Later";
    }
}

require_once('partials/_head.php');
?>


<body>
    <!-- Sidenav -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php
        require_once('partials/_topnav.php');
        ?>
        <!-- Header -->
        <div style="background-image: url(../admin/assets/img/theme/hotel.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
        <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <a href="book_reserve.php" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> <i class="fas fa-utensils"></i>
                                Reserve A New Book
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <!-- <th scope="col">Code</th> -->
                                        <th scope="col">Student</th>
                                        <th scope="col">Book</th>
                                        <th scope="col">Author</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM book_reservation WHERE status ='active' ORDER BY `reservation_date`";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($reserve = $res->fetch_object()) {
                                        // $total = ($reserve->prod_price * $reserve->prod_qty);

                                    ?>
                                        <tr>
                                            <!-- <th class="text-success" scope="row"><?php echo $reserve->reservation_id; ?></th> -->
                                            <td><?php echo $reserve->student_name; ?></td>
                                            <td><?php echo $reserve->book_name; ?></td>
                                            <td><?php echo $reserve->author_name; ?></td>
                                            <!-- <td>Rs. <?php echo $total; ?></td> -->
                                            
                                            <td>
                                                <a href="reserve_fulfill.php?reservation_id=<?php echo $reserve->reservation_id;?>&student_name=<?php echo $reserve->student_name;?>&reserve_status=Fulfilled">
                                                    <button class="btn btn-sm btn-success">
                                                        <i class="fas fa-handshake"></i>
                                                        Fulfill reserve Book
                                                    </button>
                                                </a>

                                                <a href="payments.php?cancel=<?php echo $reserve->reservation_id; ?>">
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fas fa-window-close"></i>
                                                        Cancel reserve
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php
            require_once('partials/_footer.php');
            ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>