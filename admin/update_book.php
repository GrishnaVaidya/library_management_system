<?php
session_start();
include('config/config.php');
include('config/code-generator.php');

if (isset($_POST['UpdateBook'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["book_code"]) || empty($_POST["book_name"]) || empty($_POST['book_desc']) || empty($_POST['author_name'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $update = $_GET['update'];
    $book_code  = $_POST['book_code'];
    $book_name = $_POST['book_name'];
    $book_img = $_FILES['book_img']['name'];
    move_uploaded_file($_FILES["book_img"]["tmp_name"], "assets/img/products/" . $_FILES["book_img"]["name"]);
    $book_desc = $_POST['book_desc'];
    $author_name = $_POST['author_name'];

    //Insert Captured information to a database table
    $postQuery = "UPDATE books SET book_code =?, book_name =?, book_img =?, book_desc =?, author_name =? WHERE book_id = ?";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('ssssss', $book_code, $book_name, $book_img, $book_desc, $author_name, $update);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Book Updated" && header("refresh:1; url=books.php");
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
    $update = $_GET['update'];
    $ret = "SELECT * FROM  books WHERE book_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($prod = $res->fetch_object()) {
    ?>
      <!-- Header -->
      <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
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
                      <label>Book Name</label>
                      <input type="text" value="<?php echo $prod->book_name; ?>" name="book_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Book Code</label>
                      <input type="text" name="book_code" value="<?php echo $prod->book_code; ?>" class="form-control" value="">
                    </div>
                  </div>
                  <hr>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Book Image</label>
                      <input type="file" name="book_img" class="btn btn-outline-success form-control" value="<?php echo $prod->book_img; ?>">
                    </div>
                    <div class="col-md-6">
                      <label>Author Name</label>
                      <input type="text" name="author_name" class="form-control" min="0" required value="<?php echo $prod->author_name; ?>">
                      
 

                    </div>
                  </div>
                  <hr>
                  <div class="form-row">
                    <div class="col-md-12">
                      <label>Book Description</label>
                      <textarea rows="5" name="book_desc" class="form-control" value=""><?php echo $prod->book_desc; ?></textarea>
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="UpdateBook" value="Update Book" class="btn btn-success" value="">
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
    }
      ?>
      </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>

</body>

</html>