<?php
// create_sample_data.php
// Chạy 1 lần (CLI): php create_sample_data.php
// Sinh dữ liệu mẫu và lưu vào data/*.json với password_hash (không lưu plaintext trong JSON)

declare(strict_types=1);

$dir = __DIR__ . '/data';
if (!is_dir($dir)) mkdir($dir, 0777, true);

// Students sample
$students = [
    [
        'student_code' => 'SV001',
        'full_name' => 'Nguyễn Văn Dũng',
        'class_name' => 'DCCNTT.14.1',
        'email' => '20230248@eaut.edu.vn',
        'password_hash' => password_hash('123456', PASSWORD_DEFAULT)
    ],
    [
        'student_code' => 'SV002',
        'full_name' => 'Trần Thị B',
        'class_name' => 'DCCNTT13',
        'email' => 'b@example.com',
        'password_hash' => password_hash('abcdef', PASSWORD_DEFAULT)
    ],
];

$courses = [
    ['course_code' => 'C001', 'name' => 'Lập trình PHP', 'credits' => 3],
    ['course_code' => 'C002', 'name' => 'Cơ sở dữ liệu', 'credits' => 3],
    ['course_code' => 'C003', 'name' => 'Mạng máy tính', 'credits' => 2],
];

$enrollments = [
    // empty sample
];

$grades = [
    // sample: student SV002 has a grade in C002 (so cannot unregister that course)
    [
        'student_code' => 'SV002',
        'course_code' => 'C002',
        'midterm' => 7,
        'final' => 8,
        'total' => 7.5
    ]
];

file_put_contents($dir . '/students.json', json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($dir . '/courses.json', json_encode($courses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($dir . '/enrollments.json', json_encode($enrollments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($dir . '/grades.json', json_encode($grades, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Sample data created in 'data/'\n";
echo "Test accounts:\n - SV001 / 123456\n - SV002 / abcdef\n";