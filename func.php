<?php
// func.php

define('MY_TEAM', 0);
define('OPP_TEAM', 1);

// dbconnect func
// データベース接続 (SQLite3)
function dbConnect()
{
  try {
    $url = parse_url(getenv('DATABASE_URL'));
    $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
    $pdo = new PDO($dsn, $url['user'], $url['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo 'DataBase Connection Error : ' . $e->getMessage();
  }
  return $pdo;
}

// loginCheck func
// ログインチェック
// ログインしていなければログインフォームに飛ばす
function loginCheck()
{
  if (empty($_SESSION['id']) || $_SESSION['time'] + 3600 < time()) {
    logout();
    header('Location: /login/');
    exit();
  } else {
    // ログインしている
    $_SESSION['time'] = time();
    return true;
  }
}

// logout func
// ログアウト
// クッキー・セッションクッキー・セッションをすべて削除
function logout()
{
  session_start();

  setcookie('email', '', time() - 3600, '/');
  setcookie('password', '', time() - 3600, '/');
  setcookie('save', '', time() - 3600, '/');
  setcookie(session_name(), '', time() - 3600, '/');

  // セッションを削除
  $_SESSION = array();
  unset($_SESSION);

  session_destroy();
}

// h func
// HTML特殊文字をそのまま表示
function h($string)
{
  if (!empty($string)) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8', true);
  }
}

function navbar()
{
  include($_SERVER['DOCUMENT_ROOT'] . '/tpl/header_bar.tpl.php');
}

function footer()
{
  include($_SERVER['DOCUMENT_ROOT'] . '/tpl/footer_bar.tpl.php');
}
