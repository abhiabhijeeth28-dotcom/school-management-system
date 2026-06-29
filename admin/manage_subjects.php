<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_subject'])) {
        $name = $_POST['name'];
        $code = $_POST['code'];
        $teacher_id = $_POST['teacher_id'];

        $stmt = $conn->prepare("INSERT INTO subjects (name, code, teacher_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $code, $teacher_id);
        
        if ($stmt->execute()) {
            $message = "Subject added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete_subject'])) {
        $id = $_POST['subject_id'];
        $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Subject deleted successfully.";
        } else {
            $message = "Error deleting subject.";
        }
        $stmt->close();
    }
}

// Fetch Subjects with Teacher Names
$sql = "SELECT s.id, s.name, s.code, u.name as teacher_name FROM subjects s LEFT JOIN users u ON s.teacher_id = u.id";
$result = $conn->query($sql);

// Fetch Teachers for Dropdown
$teachers = $conn->query("SELECT id, name FROM users WHERE role = 'teacher'");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Subjects</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="fas fa-plus"></i> Add Subject
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
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['code']); ?></td>
                                <td><?php echo htmlspecialchars($row['teacher_name'] ?? 'Unassigned'); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="subject_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_subject" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No subjects found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Subject</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Subject Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject Code</label>
                        <input type="text" class="form-control" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign Teacher</label>
                        <select class="form-select form-control" name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            <?php if ($teachers->num_rows > 0): ?>
                                <?php while($teacher = $teachers->fetch_assoc()): ?>
                                    <option value="<?php echo $teacher['id']; ?>"><?php echo htmlspecialchars($teacher['name']); ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_subject" class="btn btn-primary w-100">Add Subject</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
