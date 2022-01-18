<?php
session_start();
unset($_SESSION['match_id']);
header('Location: /');
