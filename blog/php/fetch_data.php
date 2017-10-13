<?php
require('./php/lib/DB.class.php');
$db = new database();

function fetchAllPosts($db) {
    $sql = 'SELECT * FROM posts ORDER BY posted DESC';
    $posts = $db->fetchFromArray(array(), $sql);
    return $posts;
}

function fetchAllComments($db) {
    $sql = 'SELECT * FROM comments';
    $comments = $db->fetchFromArray(array(), $sql);
    return $comments;
}
