<?php
session_start();
include('config/config.php');
include('config/code-generator.php');

//login 
if (isset($_POST['addCustomer'])) {
    //Prevent Posting Blank Values
    if (empty($_POST["student_phone"]) || empty($_POST["student_name"]) || empty($_POST['student_email'])) {
      

        $err = "Blank Values Not Accepted";
    } else {
        $student_name = $_POST['student_name'];
        $student_phone = $_POST['student_phone'];
        $student_email = $_POST['student_email'];
        //$student_password = sha1(md5($_POST['student_password'])); //Hash This 
        //$student_id = $_POST['student_id'];

        //Insert Captured information to a database table
        $postQuery = "INSERT INTO student (student_name, student_phone, student_email) VALUES(?,?,?)";
        $postStmt = $mysqli->prepare($postQuery);

        
        //bind paramaters
        $rc = $postStmt->bind_param('sss', $_POST["student_name"], $_POST["student_phone"], $_POST["student_email"]);
        $postStmt->execute();
        //declare a varible which will be passed to alert function
        if ($postStmt) {
            $success = "Student subscription added" && header("refresh:1; url=dashboard.php");
        } else {
            $err = "Please Try Again Or Try Later";
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
              <h3>Please Fill All Fields</h3>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Student Phone Number</label>
                    <input type="text" name="student_phone" class="form-control" value="" >
                  </div>
                  <div class="col-md-6">
                    <label>Student Name</label>
                    <input type="text" name="student_name" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Student Email</label>
                    <input type="email" name="student_email" class="form-control" value="">
                  </div>
                  <!-- <div class="col-md-6">
                    <label>cashier Password</label>
                    <input type="password" name="cashier_password" class="form-control" value="">
                  </div> -->
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                  <button type="submit" name="addCustomer" class="btn btn-primary my-4">Add Student</button>
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