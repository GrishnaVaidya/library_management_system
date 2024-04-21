<?php
session_start();
include('config/config.php');
include('config/code-generator.php');

if (isset($_POST['make'])) {
    
    //Prevent Posting Blank Values
    if (empty($_POST["borrow_code"]) || empty($_POST["student_name"]) || empty($_POST['author_name'])) {
        $err = "Blank Values Not Accepted";
    } else {
        // Rest of your code
        $borrow_id = $_POST['borrow_id'];
        $borrow_code  = $_POST['borrow_code'];
        // $customer_id = $_POST['customer_id'];
        $student_name = $_POST['student_name'];
        $book_id  = $_GET['book_id'];
        $book_name = ""; // Initialize book_name
        if(isset($_GET['book_name'])){
            $book_name = $_GET['book_name'];
        }
        $author_name = ""; // Initialize author_name
        if(isset($_GET['author_name'])){
            $author_name = $_GET['author_name'];
        }
        $date = $_POST['date']; // Updated variable name
        $expected_return_date = $_POST['expected_return_date']; // New variable for expected return date

        //Insert Captured information to a database table
        $postQuery = "INSERT INTO borrow (borrow_id, borrow_code, student_name, book_id, book_name, author_name, date, expected_return_date) VALUES(?,?,?,?,?,?,?,?)";
        $postStmt = $mysqli->prepare($postQuery);
        //bind parameters
        $rc = $postStmt->bind_param('ssssssss', $borrow_id, $borrow_code, $student_name, $book_id, $book_name, $author_name, $date, $expected_return_date); // Updated parameter order
        $postStmt->execute();
        //declare a variable which will be passed to alert function
        if ($postStmt) {
            $updateQuery = "UPDATE books SET quantity = quantity - 1 WHERE book_id = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('i', $book_id);
            $updateStmt->execute();
            $_SESSION['success'] = "Borrowing Success";
            header("refresh:1; url=borrow_record.php");
            exit;
        } else {
            $err = "Please Try Again Or Try Later";
        }
        
    }
}
require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>
        <!-- Header -->
        <div style="background-image: url(../admin/assets/img/theme/hotel.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
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
                                    <div class="col-md-4">
                                        <label>Student Name</label>
                                        <?php if (!empty($student_name)) { ?>
                                            <!-- If student name is provided in the URL, display it as readonly input -->
                                            <input type="text" name="student_name" value="<?php echo $student_name; ?>" class="form-control" readonly>
                                        <?php } else { ?>
                                            <!-- Otherwise, display dropdown to select student name -->
                                            <select class="form-control" name="student_name" id="custName" onChange="getCustomer(this.value)">
                                                <option value="">Select Student Name</option>
                                                <?php
                                                //Load All Customers
                                                $ret = "SELECT * FROM student";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                while ($cust = $res->fetch_object()) {
                                                ?>
                                                    <option><?php echo $cust->student_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                        <input type="hidden" name="borrow_id" value="<?php echo $borrowid; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Borrow Code</label>
                                        <input type="text" name="borrow_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" value="">
                                    </div>
                                </div>
                                <hr>
                                <?php
                                $book_id = $_GET['book_id'];
                                $ret = "SELECT * FROM  books WHERE book_id = ?";
                                $stmt = $mysqli->prepare($ret);
                                $stmt->bind_param('i', $book_id);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($prod = $res->fetch_object()) {
                                    $book_name = $prod->book_name;
                                    $author_name = $prod->author_name;
                                ?>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Book</label>
                                            <input type="text" readonly name="book_name" value="<?php echo $book_name; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Author</label>
                                            <input type="text" readonly name="author_name" value=" <?php echo $author_name; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>Date</label> <!-- Updated label -->
                                            <input type="date" name="date" class="form-control" value=""> <!-- Updated input type -->
                                        </div>
                                    </div>
                                <?php } ?>

                                <!-- Add expected return date field -->
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>Expected Return Date</label>
                                        <input type="date" name="expected_return_date" class="form-control" value="">
                                    </div>
                                </div>

                                <br>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <input type="submit" name="make" value="Borrow Book" class="btn btn-success" value="">
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
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>
