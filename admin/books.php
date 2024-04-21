<?php
session_start();
include('config/config.php');
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $adn = "DELETE FROM  books  WHERE  book_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();
  if ($stmt) {
    $success = "Deleted" && header("refresh:1; url=books.php");
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
              <a href="add_book.php" class="btn btn-outline-success">
                <i class="fas fa-utensils"></i>
                Add New Book
              </a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Image</th>
                    <th scope="col">book_id</th>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Actions</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM books ";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prod = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td>
                        <?php
                        if ($prod->book_img) {
                          echo "<img src='assets/img/products/$prod->book_img' height='60' width='60 class='img-thumbnail'>";
                        } else {
                          echo "<img src='assets/img/products/default.jpg' height='60' width='60 class='img-thumbnail'>";
                        }

                        ?>
                      </td>
                      <td><?php echo $prod->book_id; ?></td>
                      <td><?php echo $prod->book_name; ?></td>
                      <td><?php echo $prod->author_name; ?></td>
                      <td><?php echo $prod->quantity; ?></td>

                      
                      
                      
                      <td>
                      <a href="borrow.php?borrow=<?php echo $prod->book_id; ?>"
                        >
                        <button class="btn btn-sm btn-primary">
                            Borrow
                          </button>
                        <a href="books.php?delete=<?php echo $prod->book_id; ?>"
                        onclick="return confirm('Are you sure you want to delete this book?')">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>

                        <a href="update_book.php?update=<?php echo $prod->book_id; ?>"
                        onclick="return confirm('Are you sure you want to update this book?')">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                            Update
                          </button>
                        
                        </a>
                        <a href="book_reserve.php?book_id=<?php echo $prod->book_id; ?>"
   onclick="return confirm('Are you sure you want to reserve this book?')">
   <button class="btn btn-sm btn-primary">
       <i class="fas fa-edit"></i>
       Reserve
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