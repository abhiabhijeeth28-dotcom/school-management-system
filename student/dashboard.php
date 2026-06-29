<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}
include '../config/db.php';

$student_id = $_SESSION['user_id'];

// Get Attendance Stats
$total_attendance = $conn->query("SELECT count(*) as count FROM attendance WHERE student_id = $student_id")->fetch_assoc()['count'];
$present_days = $conn->query("SELECT count(*) as count FROM attendance WHERE student_id = $student_id AND status = 'Present'")->fetch_assoc()['count'];
$attendance_percentage = $total_attendance > 0 ? ($present_days / $total_attendance) * 100 : 0;

// Get Marks Stats
$avg_marks_query = $conn->query("SELECT AVG(marks_obtained) as avg FROM marks WHERE student_id = $student_id");
$avg_marks = $avg_marks_query->fetch_assoc()['avg'] ?? 0;

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4">Student Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="glass-card stat-card text-center">
                <i class="fas fa-calendar-check stat-icon mb-3"></i>
                <h3><?php echo number_format($attendance_percentage, 1); ?>%</h3>
                <p class="text-muted">Attendance</p>
                <a href="view_attendance.php" class="btn btn-primary btn-sm">View Details</a>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="glass-card stat-card text-center">
                <i class="fas fa-chart-line stat-icon mb-3"></i>
                <h3><?php echo number_format($avg_marks, 2); ?></h3>
                <p class="text-muted">Average Marks</p>
                <a href="view_marks.php" class="btn btn-primary btn-sm">View Marks</a>
            </div>
        </div>
    </div>

    <div class="row">
         <div class="col-md-12">
             <div class="glass-card">
                 <h4>My Information</h4>
                 <?php
                 $info = $conn->query("SELECT u.name, u.email, s.class, s.roll_no FROM users u LEFT JOIN students_details s ON u.id = s.user_id WHERE u.id = $student_id")->fetch_assoc();
                 ?>
                 <dl class="row mt-3">
                     <dt class="col-sm-3">Name</dt>
                     <dd class="col-sm-9"><?php echo htmlspecialchars($info['name']); ?></dd>
                     
                     <dt class="col-sm-3">Email</dt>
                     <dd class="col-sm-9"><?php echo htmlspecialchars($info['email']); ?></dd>
                     
                     <dt class="col-sm-3">Class</dt>
                     <dd class="col-sm-9"><?php echo htmlspecialchars($info['class'] ?? 'N/A'); ?></dd>
                     
                     <dt class="col-sm-3">Roll No</dt>
                     <dd class="col-sm-9"><?php echo htmlspecialchars($info['roll_no'] ?? 'N/A'); ?></dd>
                 </dl>
             </div>
         </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
