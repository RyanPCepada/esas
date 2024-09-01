<?php
    require_once '../../config.php';

    // Retrieve the student ID and password from the form
    $student_id = $_POST['student_id'];
    $pass =  $_POST['password'];

    // Prepare the SQL statement using the $pdo object
    $selectQry = $pdo->prepare("SELECT count(student_id) as cnt, student_id, `password`
        FROM tbl_students WHERE student_id = ? AND `password` = ?");
    $selectQry->execute([$student_id, $pass]);
    $selectQry = $selectQry->fetch(PDO::FETCH_ASSOC);

    // Check if a matching record was found
    if($selectQry['cnt'] > 0 && $selectQry['student_id'] == $student_id && $selectQry['password'] == $pass){
        session_start();
        $_SESSION['student_id'] = $selectQry['student_id'];
        echo 'success';
    }else{
        echo 'Invalid email/password';
    }
?>
