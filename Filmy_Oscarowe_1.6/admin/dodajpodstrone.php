<?php
require('../cfg.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page_title = $_POST['page_title'] ?? null;
    $page_content = $_POST['page_content'] ?? null;
    $page_is_active = !empty($_POST['page_is_active']) ? 1 : 0;

    if ($page_title && $page_content) {
        $stmt = mysqli_prepare($conn, "INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssi', $page_title, $page_content, $page_is_active);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header('Location: ./admin.php');
    exit;
}