<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}
include '../config/db.php';

$student_id = $_SESSION['user_id'];

// Fetch Marks
$sql = "SELECT s.name as subject_name, m.marks_obtained, m.total_marks 
        FROM marks m 
        JOIN subjects s ON m.subject_id = s.id 
        WHERE m.student_id = $student_id 
        ORDER BY s.name";
$result = $conn->query($sql);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4">My Marks</h2>
    
    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Total Marks</th>
                        <th>Percentage</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php 
                                $percentage = ($row['marks_obtained'] / $row['total_marks']) * 100; 
                                $result_status = $percentage >= 40 ? "Pass" : "Fail";
                                $badge_class = $result_status == "Pass" ? "success" : "danger";
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td><?php echo $row['marks_obtained']; ?></td>
                                <td><?php echo $row['total_marks']; ?></td>
                                <td><?php echo number_format($percentage, 2); ?>%</td>
                                <td><span class="badge bg-<?php echo $badge_class; ?>"><?php echo $result_status; ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No marks available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
