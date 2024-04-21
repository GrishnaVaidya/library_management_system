<?php
session_start();
include('config/config.php');
include('config/code-generator.php');

if (isset($_POST['return'])) {
    //Prevent Posting Blank Values
    if (empty($_POST["return_code"]) || empty($_POST["return_date"])) {

        $err = "Blank Values Not Accepted";
    } else {
        $return_code = $_POST['return_code'];
        $borrow_code = $_GET['borrow_code'];
        $return_id = $_POST['return_id'];
        $borrow_status = $_GET['borrow_status'];

        // Fetch the expected return date from the borrow table
        $stmt = $mysqli->prepare("SELECT expected_return_date FROM borrow WHERE borrow_code = ?");
        $stmt->bind_param("s", $borrow_code);
        $stmt->execute();
        $stmt->bind_result($expected_return_date);
        $stmt->fetch();
        $stmt->close();

        // Get the return date from the form
        $return_date = $_POST['return_date'];

        // Compare return date with expected return date
        if ($return_date > $expected_return_date) {
            // Show a pop-up indicating penalties are required
            echo "<script>alert('Penalties required.');</script>";
            // Redirect to penalties.php
            header("Location: penalties.php");
            exit(); // Exit to prevent further execution of the script
        } else {
            //Insert Captured information to a database table
            $postQuery = "INSERT INTO return_book (return_id, return_code, borrow_code, return_date) VALUES(?,?,?,?)";
            $upQry = "UPDATE borrow SET borrow_status =? WHERE borrow_code =?";

            $postStmt = $mysqli->prepare($postQuery);
            $upStmt = $mysqli->prepare($upQry);
            //bind parameters

            $rc = $postStmt->bind_param('ssss', $return_id, $return_code, $borrow_code, $return_date);
            $rc = $upStmt->bind_param('ss', $borrow_status, $borrow_code);

            $postStmt->execute();
            $upStmt->execute();
            //declare a variable which will be passed to alert function
            if ($upStmt && $postStmt) {
                $updateQuery = "UPDATE books SET quantity = quantity + 1 WHERE book_id = ?";
                $updateStmt = $mysqli->prepare($updateQuery);
                $updateStmt->bind_param('i', $book_id);
                $updateStmt->execute();
                $success = "Returned" && header("refresh:1; url=borrow_reports.php");
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
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
        $borrow_code = $_GET['borrow_code'];
        $ret = "SELECT * FROM borrow WHERE borrow_status ='borrowed' ORDER BY `student_name`"
        ;
        $stmt = $mysqli->prepare($ret);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($order = $res->fetch_object()) {
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
                                <h3>Please Fill All Fields</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Return Code</label>
                                            <input type="text" name="return_code" value="<?php echo $mpesaCode; ?>" class="form-control" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Return Date</label>
                                            <input type="date" name="return_date" class="form-control" value="">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <input type="submit" name="return" value="Return Book" class="btn btn-success" value="">
                                        </div>
                                    </div>
                                </form>
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
    <?php
    }
    ?>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>
