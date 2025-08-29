<?php
session_start();
session_destroy();
header("Location: ../menu/login2.php");
exit();
?>