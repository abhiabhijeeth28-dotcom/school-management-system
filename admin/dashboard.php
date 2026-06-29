<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../config/db.php';

// Fetch stats
$student_count = $conn->query("SELECT count(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];
$teacher_count = $conn->query("SELECT count(*) as count FROM users WHERE role='teacher'")->fetch_assoc()['count'];
$subject_count = $conn->query("SELECT count(*) as count FROM subjects")->fetch_assoc()['count'];

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="glass-card stat-card text-center">
                <i class="fas fa-user-graduate stat-icon mb-3"></i>
                <h3><?php echo $student_count; ?></h3>
                <p class="text-muted">Total Students</p>
                <a href="manage_students.php" class="btn btn-primary btn-sm">Manage</a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="glass-card stat-card text-center">
                <i class="fas fa-chalkboard-teacher stat-icon mb-3"></i>
                <h3><?php echo $teacher_count; ?></h3>
                <p class="text-muted">Total Teachers</p>
                <a href="manage_teachers.php" class="btn btn-primary btn-sm">Manage</a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="glass-card stat-card text-center">
                <i class="fas fa-book stat-icon mb-3"></i>
                <h3><?php echo $subject_count; ?></h3>
                <p class="text-muted">Subjects</p>
                <a href="manage_subjects.php" class="btn btn-primary btn-sm">View</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions / Recent Activity could go here -->
    
</div>

<?php include '../includes/footer.php'; ?>
