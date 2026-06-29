<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">ABC School</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_students.php">Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_teachers.php">Teachers</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_marks.php">Marks</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_attendance.php">Attendance</a></li>
                <?php elseif($_SESSION['role'] == 'teacher'): ?>
                    <li class="nav-item"><a class="nav-link" href="../teacher/dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../teacher/attendance.php">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="../teacher/marks.php">Marks</a></li>
                    <li class="nav-item"><a class="nav-link" href="../teacher/students.php">Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="../teacher/manage_subjects.php">Subjects</a></li>
                <?php elseif($_SESSION['role'] == 'student'): ?>
                    <li class="nav-item"><a class="nav-link" href="../student/dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../student/view_attendance.php">My Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="../student/view_marks.php">My Marks</a></li>
                    <li class="nav-item"><a class="nav-link" href="../student/subjects.php">Subjects</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> (<?php echo ucfirst($_SESSION['role']); ?>)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm mt-1" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
