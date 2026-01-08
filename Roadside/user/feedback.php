<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

// USER AUTH CHECK
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle feedback submission via AJAX
if(isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $rating     = intval($_POST['rating']);
    $comments   = trim($_POST['comments']);

    // Check if feedback already exists
    $check = mysqli_query($con, "SELECT * FROM feedback WHERE booking_id='$booking_id'");
    if(mysqli_num_rows($check) > 0){
        // Update existing feedback
        $stmt = $con->prepare("UPDATE feedback SET rating=?, comments=? WHERE booking_id=?");
        $stmt->bind_param("isi", $rating, $comments, $booking_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new feedback
        $stmt = $con->prepare("INSERT INTO feedback (booking_id, rating, comments) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $booking_id, $rating, $comments);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode(['success' => true]);
    exit();
}

// Fetch all bookings for the user
$query = "
SELECT 
    b.booking_id,
    s.service_name,
    b.completed_at,
    b.status,
    f.rating,
    f.comments
FROM booking b
LEFT JOIN feedback f ON b.booking_id = f.booking_id
LEFT JOIN services s ON b.service_id = s.service_id
WHERE b.user_id = '$user_id'
ORDER BY b.booking_id DESC
";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Feedback | RSA Nepal</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
/* ===== CONTENT ===== */
.content { margin-left: 260px; margin-top: 60px; padding: 25px; background: #f3f4f6; min-height: 100vh; }
.feedback-card { background: #fff; border-radius: 14px; padding: 25px 30px; box-shadow: 0 8px 24px rgba(0,0,0,0.06); }
.feedback-title { font-size: 20px; font-weight: 600; color: #0f172a; margin-bottom: 20px; }
.feedback-table { width: 100%; border-collapse: collapse; }
.feedback-table thead { background: #f1f5f9; }
.feedback-table th, .feedback-table td { padding: 12px; font-size: 14px; text-align: left; border-bottom: 1px solid #e5e7eb; }
.feedback-table th { font-weight: 600; color: #334155; }
.feedback-table tbody tr:hover { background: #f8fafc; }
.feedback-empty { font-size: 14px; color: #6b7280; padding: 20px 0; }

/* ===== FEEDBACK FORM ===== */
.rating input { display: none; }
.rating label {
    float: right;
    font-size: 24px;
    color: #ccc;
    cursor: pointer;
}
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #f59e0b;
}

.feedback-textarea {
    width: 100%;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    padding: 10px;
    resize: none;
    font-size: 14px;
    margin-top: 5px;
}

.btn-submit {
    background: #38bdf8;
    color: #fff;
    padding: 8px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 5px;
    transition: all 0.3s ease;
}
.btn-submit:hover { background: #0ea5e9; }

/* Disabled message */
.service-pending { color: #6b7280; font-size: 14px; }
</style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="content">
    <div class="feedback-card">
        <h4 class="feedback-title">Your Feedback</h4>

        <?php if(mysqli_num_rows($result) > 0) { ?>
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Service</th>
                    <th>Completed At</th>
                    <th>Rating & Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                    <td><?php echo !empty($row['completed_at']) ? $row['completed_at'] : '-'; ?></td>
                    <td>
                        <?php if($row['status'] == 'inactive') { ?>
                        <form class="feedback-form" data-booking="<?php echo $row['booking_id']; ?>">
                            <div class="rating">
                                <?php for($i=5; $i>=1; $i--) { ?>
                                    <input type="radio" name="rating_<?php echo $row['booking_id']; ?>" value="<?php echo $i; ?>" <?php if($row['rating']==$i) echo 'checked'; ?> id="star<?php echo $i.$row['booking_id']; ?>">
                                    <label for="star<?php echo $i.$row['booking_id']; ?>">★</label>
                                <?php } ?>
                            </div>
                            <textarea name="comments" class="feedback-textarea" rows="2" placeholder="Write your comment..."><?php echo htmlspecialchars($row['comments']); ?></textarea>
                            <button type="submit" class="btn-submit">Submit</button>
                        </form>
                        <?php } else { ?>
                            <span class="service-pending">Service not completed yet.</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
            <p class="feedback-empty">No bookings available.</p>
        <?php } ?>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.feedback-form').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        var booking_id = form.data('booking');
        var rating = form.find('input[name="rating_'+booking_id+'"]:checked').val();
        var comments = form.find('textarea[name="comments"]').val();

        $.post('', {booking_id: booking_id, rating: rating, comments: comments}, function(data){
            alert('Feedback submitted successfully!');
        }, 'json');
    });
});
</script>

</body>
</html>
