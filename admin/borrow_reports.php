<?php
session_start();
include('config/config.php');

require_once('partials/_head.php');
?>

<body>
    
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
        <div style="background-image: url(assets/img/theme/hotel.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
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
                            Borrow Records
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <!-- <th class="text-success" scope="col">Code</th> -->
                                        <th scope="col">User</th>
                                        <th class="text-success" scope="col">Book</th>
                                        <!-- <th scope="col">Unit Price</th> -->
                                        <th class="text-success" scope="col">Author</th>
                                        <!-- <th scope="col">Book Title</th> -->
                                        <th scop="col">Status</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM  borrow ORDER BY `created_at` DESC  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        // Retrieve book details
                                        $book_id = $order->book_id;
                                        $book_query = "SELECT book_name, author_name FROM books WHERE book_id = ?";
                                        $book_stmt = $mysqli->prepare($book_query);
                                        $book_stmt->bind_param('i', $book_id);
                                        $book_stmt->execute();
                                        $book_result = $book_stmt->get_result();
                                        $book = $book_result->fetch_object();
                                    ?>
                                        <tr>
                                            <!-- <th class="text-success" scope="row"><?php echo $order->borrow_code; ?></th> -->
                                            <td><?php echo $order->student_name; ?></td>
                                            <td class="text-success"><?php echo $book->book_name; ?></td>
                                            <!-- <td> <?php echo $order->prod_price; ?></td> -->
                                            <td class="text-success"><?php echo $book->author_name; ?></td>
                                            <!-- <td>Rs. <?php echo $total; ?></td> -->
                                            <td><?php if ($order->borrow_status == 'Borrowed') {
                                                    echo "<span class='badge badge-danger'>Not Returned</span>";
                                                } else {
                                                    echo "<span class='badge badge-success'>$order->borrow_status</span>";
                                                } ?></td>
                                            <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
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
