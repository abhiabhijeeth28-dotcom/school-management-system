<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: ../index.php");
    exit();
}
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_attendance'])) {
        $student_id = $_POST['student_id'];
        $date = $_POST['date'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $student_id, $date, $status);
        
        if ($stmt->execute()) {
            $message = "Attendance marked successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch Attendance (All or just for today? Let's show recent)
$sql = "SELECT a.id, u.name as student_name, a.date, a.status 
        FROM attendance a 
        JOIN users u ON a.student_id = u.id 
        ORDER BY a.date DESC, a.id DESC LIMIT 50";
$result = $conn->query($sql);

// Fetch Students
$students = $conn->query("SELECT id, name FROM users WHERE role = 'student'");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Student Attendance</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
            <i class="fas fa-plus"></i> Mark Attendance
        </button>
    </div>

    <?php if($message): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['status'] == 'Present' ? 'success' : ($row['status'] == 'Absent' ? 'danger' : 'warning'); ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No attendance records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Attendance Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Attendance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select class="form-select form-control" name="student_id" required>
                            <option value="">Select Student</option>
                            <?php if ($students->num_rows > 0): ?>
                                <?php while($std = $students->fetch_assoc()): ?>
                                    <option value="<?php echo $std['id']; ?>"><?php echo htmlspecialchars($std['name']); ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select form-control" name="status" required>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>
                    <button type="submit" name="add_attendance" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
