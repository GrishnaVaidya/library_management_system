<?php
session_start();
include('config/config.php');
include('config/code-generator.php');
if (isset($_GET['book_id'])) {
    // 'book_id' is set in the URL, proceed with accessing it
    $book_id = $_GET['book_id'];
    // Now you can use $book_id in your code
} else {
    // 'book_id' is not set in the URL, handle this case (e.g., display an error message)
    echo "Book ID is not provided in the URL";
    // You might want to redirect the user or display an error message and exit the script
    exit(); // Stop further execution of the script
}

// Check if 'book_id' is set in the URL
if(isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    
    if (isset($_POST['make'])) {
        //Prevent Posting Blank Values
        if (empty($_POST["reserve_code"]) || empty($_POST["student_name"])) {
            $err = "Blank Values Not Accepted";
        } else {
            $reservation_id = $_POST['reservation_id'];
            $reserve_code  = $_POST['reserve_code'];
            $student_name = $_POST['student_name'];
            $book_name = ""; // Placeholder for now, will be fetched from the database
            $author_name = ""; // Placeholder for now, will be fetched from the database
            $reservation_date = date("Y-m-d"); // Updated variable name to current date

            // Fetch book details from the database
            $stmt = $mysqli->prepare("SELECT * FROM books WHERE book_id = ?");
            $stmt->bind_param('i', $book_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $book_name = $row['book_name'];
                $author_name = $row['author_name'];

                //Insert Captured information to a database table
                $postQuery = "INSERT INTO book_reservation (reservation_id, reserve_code, student_name, book_id, book_name, author_name, reservation_date) VALUES(?,?,?,?,?,?,?)";
                $postStmt = $mysqli->prepare($postQuery);
                //bind parameters
                $rc = $postStmt->bind_param('sssssss', $reservation_id, $reserve_code, $student_name, $book_id, $book_name, $author_name, $reservation_date); // Updated parameter order
                $postStmt->execute();
                //declare a variable which will be passed to alert function
                if ($postStmt) {
                    $success = "Reservation Success" && header("refresh:1; url=reservation_record.php");
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            } else {
                $err = "Book not found";
            }
        }
    }
} else {
    $err = "Book ID not provided";
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
              <h3>Please Fill All Fields</h3>
            </div>
            <div class="card-body">
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">

                  <div class="col-md-4">
                    <label>User Name</label>
                    <select class="form-control" name="student_name" id="custName" onChange="getCustomer(this.value)">
                      <option value="">Select User Name</option>
                      <?php
                      //Load All Customers
                      $ret = "SELECT * FROM  student";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      while ($cust = $res->fetch_object()) {
                      ?>
                        <option><?php echo $cust->student_name; ?></option>
                      <?php } ?>
                    </select>
                    <input type="hidden" name="reservation_id" value="<?php echo $reserveid; ?>" class="form-control">
                  </div>

                  <div class="col-md-4">
                    <label>Revervation Code</label>
                    <input type="text" name="reserve_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <?php
                 $book_id = $_GET['book_id'];
                 $ret = "SELECT * FROM  books WHERE book_id = '$book_id'";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($prod = $res->fetch_object()) {
                ?>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Author</label>
                      <input type="text" readonly name="author_name" value=" <?php echo $prod->author_name; ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Date</label> <!-- Updated label -->
                      <input type="date" name="date" class="form-control" value=""> <!-- Updated input type -->
                    </div>
                  </div>
                <?php } ?>

                <!-- Add expected return date field -->
                

                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="make" value="Reserve Book" class="btn btn-success" value="">
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
