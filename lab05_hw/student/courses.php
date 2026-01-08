<?php
// student/courses.php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
require_once __DIR__ . '/../includes/csrf.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

$student = current_student();
$code = $student['student_code'] ?? '';

$courses = read_json('courses.json', []);
$enrollments = read_json('enrollments.json', []);
$myEnrollCourseCodes = [];
foreach ($enrollments as $e) {
    if (($e['student_code'] ?? '') === $code) $myEnrollCourseCodes[] = $e['course_code'];
}

require_once __DIR__ . '/../includes/header.php';
?>
<h1>Courses</h1>
<table class="table">
  <thead><tr><th>Course</th><th>Credits</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($courses as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= (int)$c['credits'] ?></td>
        <td>
          <?php if (in_array($c['course_code'], $myEnrollCourseCodes)): ?>
            <span class="badge bg-secondary">Already registered</span>
          <?php else: ?>
            <form method="post" action="/lab05_hw/student/register.php" style="display:inline;">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
              <input type="hidden" name="course_code" value="<?= htmlspecialchars($c['course_code']) ?>">
              <button class="btn btn-sm btn-primary" type="submit">Register</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>