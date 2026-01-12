<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===== AJAX SAVE FEEDBACK ===== */
if (isset($_POST['booking_id'], $_POST['rating'])) {
    $booking_id = intval($_POST['booking_id']);
    $rating     = intval($_POST['rating']);
    $comments   = trim($_POST['comments']);

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success'=>false,'msg'=>'Invalid rating']);
        exit();
    }

    // Check if feedback exists
    $check = mysqli_query($con, "SELECT feedback_id FROM feedback WHERE booking_id='$booking_id' AND user_id='$user_id'");
    if (mysqli_num_rows($check) > 0) {
        $stmt = $con->prepare("UPDATE feedback SET rating=?, comments=?, created_at=NOW() WHERE booking_id=? AND user_id=?");
        $stmt->bind_param("isii", $rating, $comments, $booking_id, $user_id);
    } else {
        $stmt = $con->prepare("INSERT INTO feedback (booking_id, user_id, rating, comments, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $booking_id, $user_id, $rating, $comments);
    }
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success'=>true]);
    exit();
}

/* ===== FETCH USER BOOKINGS ===== */
$result = mysqli_query($con, "
SELECT 
    b.booking_id,
    b.status,
    b.completed_at,
    s.service_name,
    f.rating,
    f.comments
FROM booking b
LEFT JOIN services s ON b.service_id = s.service_id
LEFT JOIN feedback f ON b.booking_id = f.booking_id AND f.user_id='$user_id'
WHERE b.user_id = '$user_id'
ORDER BY b.booking_id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>User Feedback</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
.content{margin-left:260px;margin-top:60px;padding:25px;background:#f3f4f6;}
.card{background:#fff;padding:25px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.06);}
table{width:100%;border-collapse:collapse;}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;}
th{background:#f1f5f9;text-align:left;}
.rating label{font-size:22px;color:#d1d5db;cursor:pointer;}
.rating input{display:none;}
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label{color:#f59e0b;}
.readonly label{cursor:default;}
textarea{width:100%;border-radius:8px;border:1px solid #d1d5db;padding:8px;margin-top:4px;}
button{margin-top:6px;background:#0ea5e9;color:#fff;border:none;padding:7px 14px;border-radius:6px;cursor:pointer;}
button:hover{background:#0284c7;}
.pending{color:#6b7280;font-size:14px;}
</style>
</head>

<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="content">
<div class="card">
<h3>Your Feedback</h3>

<table>
<thead>
<tr>
<th>Booking</th>
<th>Service</th>
<th>Completed</th>
<th>Rating & Comment</th>
</tr>
</thead>
<tbody>

<?php if(mysqli_num_rows($result) > 0): ?>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $row['booking_id'] ?></td>
<td><?= htmlspecialchars($row['service_name']) ?></td>
<td><?= $row['completed_at'] ?? '-' ?></td>
<td>

<?php if($row['status'] === 'completed'): ?>
<form class="feedback-form" data-id="<?= $row['booking_id'] ?>">
<div class="rating <?= $row['rating'] ? 'readonly' : '' ?>">
<?php for($i=5;$i>=1;$i--): ?>
<input type="radio"
       id="star<?= $i.$row['booking_id'] ?>"
       name="rating_<?= $row['booking_id'] ?>"
       value="<?= $i ?>"
       <?= ($row['rating']==$i)?'checked':'' ?>
       <?= $row['rating']?'disabled':'' ?>>
<label for="star<?= $i.$row['booking_id'] ?>">★</label>
<?php endfor; ?>
</div>

<textarea <?= $row['rating']?'readonly':'' ?>
name="comments"
placeholder="Write your comment..."><?= htmlspecialchars($row['comments']) ?></textarea>

<?php if(!$row['rating']): ?>
<button type="submit">Submit</button>
<?php else: ?>
<span class="pending">✔ Feedback submitted</span>
<?php endif; ?>
</form>

<?php else: ?>
<span class="pending">Service not completed yet</span>
<?php endif; ?>

</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="4" style="text-align:center;">No bookings available.</td></tr>
<?php endif; ?>

</tbody>
</table>
</div>
</div>

<script>
$('.feedback-form').submit(function(e){
    e.preventDefault();
    let f=$(this);
    let id=f.data('id');
    let rating=f.find('input:checked').val();
    let comments=f.find('textarea').val();

    if(!rating){ alert('Please select rating'); return; }

    $.post('',{booking_id:id,rating:rating,comments:comments},function(res){
        if(res.success){
            location.reload();
        } else {
            alert('Failed to submit feedback');
        }
    },'json');
});
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>
