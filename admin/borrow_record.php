<?php
session_start();
include('config/config.php');

// Cancel Order
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM  borrow  WHERE  borrow_id = ?";
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
        $success = "Deleted" && header("refresh:1; url=borrow_record.php");
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
                            <a href="borrow.php" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> <i class="fas fa-utensils"></i>
                                Borrow A New Book
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Student</th>
                                        <th scope="col">Book</th>
                                        <th scope="col">Author</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM borrow WHERE borrow_status ='borrowed' ORDER BY `created_at` DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($borrow = $res->fetch_object()) {
                                        // Retrieve book details
                                        $book_id = $borrow->book_id;
                                        $book_query = "SELECT book_name, author_name FROM books WHERE book_id = ?";
                                        $book_stmt = $mysqli->prepare($book_query);
                                        $book_stmt->bind_param('i', $book_id);
                                        $book_stmt->execute();
                                        $book_result = $book_stmt->get_result();
                                        $book = $book_result->fetch_object();
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $borrow->borrow_code; ?></th>
                                            <td><?php echo $borrow->student_name; ?></td>
                                            <td><?php echo $book->book_name; ?></td>
                                            <td><?php echo $book->author_name; ?></td>
                                            
                                            <td>
                                                <a href="return_book.php?borrow_code=<?php echo $borrow->borrow_code;?>&student_name=<?php echo $borrow->student_name;?>&borrow_status=Returned">
                                                    <button class="btn btn-sm btn-success">
                                                        <i class="fas fa-handshake"></i>
                                                        Return Book
                                                    </button>
                                                </a>

                                                <a href="borrow_record.php?cancel=<?php echo $borrow->borrow_id; ?>">
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fas fa-window-close"></i>
                                                        Cancel Borrow
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
