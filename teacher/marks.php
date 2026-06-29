<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: ../index.php");
    exit();
}
include '../config/db.php';

$message = "";
$teacher_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_mark'])) {
        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        $marks_obtained = $_POST['marks_obtained'];
        $total_marks = $_POST['total_marks'];

        $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, marks_obtained, total_marks) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iidi", $student_id, $subject_id, $marks_obtained, $total_marks);
        
        if ($stmt->execute()) {
            $message = "Marks added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch Marks (Only for subjects taught by this teacher?)
// Technically filtering by teacher's subjects is better.
$sql = "SELECT m.id, u.name as student_name, s.name as subject_name, m.marks_obtained, m.total_marks 
        FROM marks m 
        JOIN users u ON m.student_id = u.id 
        JOIN subjects s ON m.subject_id = s.id 
        WHERE s.teacher_id = $teacher_id
        ORDER BY m.id DESC LIMIT 50";
$result = $conn->query($sql);

// Fetch Students
$students = $conn->query("SELECT id, name FROM users WHERE role = 'student'");
// Fetch My Subjects
$subjects = $conn->query("SELECT id, name FROM subjects WHERE teacher_id = $teacher_id");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Student Marks</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMarkModal">
            <i class="fas fa-plus"></i> Add Marks
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
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Marks</th>
                        <th>Total</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php $percentage = ($row['marks_obtained'] / $row['total_marks']) * 100; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td><?php echo $row['marks_obtained']; ?></td>
                                <td><?php echo $row['total_marks']; ?></td>
                                <td><?php echo number_format($percentage, 2); ?>%</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No marks records found or you haven't assigned any yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Mark Modal -->
<div class="modal fade" id="addMarkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Marks</h5>
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
                        <label class="form-label">Subject</label>
                        <select class="form-select form-control" name="subject_id" required>
                            <option value="">Select Subject</option>
                            <?php if ($subjects->num_rows > 0): ?>
                                <?php while($sub = $subjects->fetch_assoc()): ?>
                                    <option value="<?php echo $sub['id']; ?>"><?php echo htmlspecialchars($sub['name']); ?></option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">No subjects assigned to you</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marks Obtained</label>
                            <input type="number" step="0.01" class="form-control" name="marks_obtained" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="number" class="form-control" name="total_marks" value="100" required>
                        </div>
                    </div>
                    <button type="submit" name="add_mark" class="btn btn-primary w-100">Add Marks</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
