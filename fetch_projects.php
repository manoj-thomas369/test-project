<?php
// fetch_projects.php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

require 'db.php';

// 1) Read sort_by
$sortBy = $_GET['sort_by'] ?? 'recent';

switch ($sortBy) {
    case 'category_asc':
        $orderBy = 'c.category_name ASC';
        break;
    case 'username_asc':
        $orderBy = 'u.username ASC';
        break;
    case 'title_asc':
        $orderBy = 'p.project_title ASC';
        break;
    case 'recent':
    default:
        $orderBy = 'p.created_at DESC';
        $sortBy = 'recent';
        break;
}

// 2) Pagination
$rowsPerPage = 2;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $rowsPerPage;

// 3) Total records
$countSql = "
    SELECT COUNT(*) AS total
    FROM projects p
    INNER JOIN users u ON p.user_id = u.user_id
    LEFT JOIN categories c ON p.cid = c.cid
";
$total = (int) $pdo->query($countSql)->fetchColumn();
$totalPages = $total > 0 ? ceil($total / $rowsPerPage) : 1;

// 4) Fetch data
$sql = "
    SELECT p.project_title, u.username, c.category_name
    FROM projects p
    INNER JOIN users u ON p.user_id = u.user_id
    LEFT JOIN categories c ON p.cid = c.cid
    ORDER BY $orderBy
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $rowsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

// Build rows HTML
$rowsHtml = '';
if ($rows) {
    foreach ($rows as $row) {
        $title = htmlspecialchars($row['project_title']);
        $username = htmlspecialchars($row['username']);
        $category = htmlspecialchars($row['category_name']);

        $rowsHtml .= "<tr>
            <td>{$title}</td>
            <td>{$username}</td>
            <td>{$category}</td>
        </tr>";
    }
} else {
    $rowsHtml = '<tr><td colspan="3">No projects found.</td></tr>';
}

// Build pagination HTML
$paginationHtml = '';
if ($totalPages > 1) {
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            $paginationHtml .= "<strong>[$i]</strong> ";
        } else {
            $paginationHtml .= "<a href=\"#\" class=\"pagination-link\" data-page=\"$i\">[$i]</a> ";
        }
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode([
    'rows_html' => $rowsHtml,
    'pagination_html' => $paginationHtml
]);