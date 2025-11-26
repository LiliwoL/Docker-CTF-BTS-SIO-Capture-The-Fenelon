<?php
require 'session.php';
close_session();
header("Location: index.php");
exit;
?>
