<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Projects (AJAX)</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <h2>Projects</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> |
        <a href="logout.php">Logout</a>
    </p>

    <!-- Sort By dropdown -->
    <label>Sort By:
        <select id="sort_by">
            <option value="recent" selected>Recent Projects</option>
            <option value="category_asc">Order By Category Name ASC</option>
            <option value="username_asc">Order By Username ASC</option>
            <option value="title_asc">Order By Project Title ASC</option>
        </select>
    </label>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Project Title</th>
                <th>Username</th>
                <th>Category Name</th>
            </tr>
        </thead>
        <tbody id="projects-body">
            <!-- Filled by AJAX -->
        </tbody>
    </table>

    <div id="pagination" style="margin-top:10px;">
        <!-- Pagination links by AJAX -->
    </div>

    <script>
        function loadProjects(page = 1) {
            const sortBy = $('#sort_by').val();

            $.ajax({
                url: 'fetch_projects.php',
                method: 'GET',
                dataType: 'json',
                data: {
                    page: page,
                    sort_by: sortBy
                },
                success: function (res) {
                    $('#projects-body').html(res.rows_html);
                    $('#pagination').html(res.pagination_html);
                },
                error: function () {
                    alert('Error loading projects.');
                }
            });
        }

        $(document).ready(function () {
            // Default load: page 1, Recent Projects
            loadProjects(1);

            // When sort changes → reload page 1
            $('#sort_by').on('change', function () {
                loadProjects(1);
            });

            // Pagination clicks (event delegation)
            $(document).on('click', '.pagination-link', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                loadProjects(page);
            });
        });
    </script>
</body>

</html>