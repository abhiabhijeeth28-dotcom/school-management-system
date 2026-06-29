<?php
session_start();
include 'config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;
            
            if ($role == 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($role == 'teacher') {
                header("Location: teacher/dashboard.php");
            } else {
                header("Location: student/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>

<div class="container login-container">
    <div class="login-card glass-card">
        <div class="text-center mb-4">
            <i class="fas fa-graduation-cap fa-3x" style="color: var(--secondary-color);"></i>
            <h2 class="mt-3 text-white">Welcome Back</h2>
            <p class="text-white">Sign in to continue</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
