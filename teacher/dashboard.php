<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: ../index.php");
    exit();
}
include '../config/db.php';

// Stats
$user_id = $_SESSION['user_id'];
$my_subjects = $conn->query("SELECT count(*) as count FROM subjects WHERE teacher_id = $user_id")->fetch_assoc()['count'];
$total_students = $conn->query("SELECT count(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4">Teacher Dashboard</h2>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="glass-card stat-card text-center">
                <i class="fas fa-book-open stat-icon mb-3"></i>
                <h3><?php echo $my_subjects; ?></h3>
                <p class="text-muted">My Subjects</p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="glass-card stat-card text-center">
                <i class="fas fa-user-graduate stat-icon mb-3"></i>
                <h3><?php echo $total_students; ?></h3>
                <p class="text-muted">Total Students</p>
                <a href="students.php" class="btn btn-primary btn-sm">View Students</a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="glass-card">
                <h4>Quick Actions</h4>
                <div class="d-grid gap-2 mt-3">
                    <a href="attendance.php" class="btn btn-outline-light">Mark Attendance</a>
                    <a href="marks.php" class="btn btn-outline-light">Add Marks</a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
