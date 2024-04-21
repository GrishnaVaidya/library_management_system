<!-- <?php
session_start();
include('config/config.php');

if (isset($_GET['reservation_id'])) {
    // Get reservation details from the database
    $reservation_id = $_GET['reservation_id'];
    $query = "SELECT * FROM book_reservation WHERE reservation_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $book_id = $row['book_id'];
        $student_name = $row['student_name']; // Fetch student name

        // Redirect to borrow_book.php of the specific book with reservation details
        header("Location: borrow_book.php?book_id=$book_id&auto_select_user=true&student_name=$student_name&reservation_id=$reservation_id");
        exit();
    } else {
        echo "Reservation not found";
    }
} else {
    echo "Reservation ID not provided";
}
?> -->
