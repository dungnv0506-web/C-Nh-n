<?php
// student/registrations.php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
require_once __DIR__ . '/../includes/csrf.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

$student = current_student();
$code = $student['student_code'] ?? '';

$enrollments = read_json('enrollments.json', []);
$courses = read_json('courses.json', []);
$courseMap = [];
foreach ($courses as $c) $courseMap[$c['course_code']] = $c;

$myEnroll = array_values(array_filter($enrollments, fn($e) => ($e['student_code'] ?? '') === $code));
$grades = read_json('grades.json', []);

require_once __DIR__ . '/../includes/header.php';
?>
<h1>My Registrations</h1>
<?php if (empty($myEnroll)): ?>
  <p>Chưa đăng ký học phần nào.</p>
<?php else: ?>
  <table class="table">
    <thead><tr><th>Course</th><th>Registered at</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($myEnroll as $e): 
        $c = $courseMap[$e['course_code']] ?? null;
        $hasGrade = false;
        foreach ($grades as $g) {
            if (($g['student_code'] ?? '') === $code && ($g['course_code'] ?? '') === $e['course_code']) {
                $hasGrade = true; break;
            }
        }
    ?>
      <tr>
        <td><?= htmlspecialchars($c['name'] ?? $e['course_code']) ?></td>
        <td><?= htmlspecialchars($e['created_at'] ?? '') ?></td>
        <td>
          <?php if ($hasGrade): ?>
            <span class="text-muted">Has grade — cannot unregister</span>
          <?php else: ?>
            <form method="post" action="/lab05_hw/student/unregister.php" style="display:inline;">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
              <input type="hidden" name="course_code" value="<?= htmlspecialchars($e['course_code']) ?>">
              <button class="btn btn-sm btn-danger" type="submit">Unregister</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>