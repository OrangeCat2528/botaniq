<?php
require_once './helper/auth.php';
isLogin();

$page = basename($_SERVER['PHP_SELF'], ".php");
$headerTitle = "Botaniq SuperApp";
if ($page === "ai") {
  $headerTitle = "Botaniq | AI";
} elseif ($page === "dashboard") {
  $headerTitle = "Botaniq | Home";
} elseif ($page === "articles") {
  $headerTitle = "Botaniq | Articles";
} elseif ($page === "table") {
  $headerTitle = "Botaniq | Data";
} elseif ($page === "charts") {
  $headerTitle = "Botaniq | Charts";
} elseif ($page === "viewarticles") {
  $headerTitle = "Botaniq SuperApp";
} elseif ($page === "profile") {
  $headerTitle = "My Profile";
}
?>

<div class="text-center py-5 mb-5 shadow-md text-gray-600 bg-white">
  <button id="sidebar-toggle" class="absolute left-5 items-center fa fa-user-circle text-gray text-3xl"></button>
  <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="inline-block w-8 h-8" id="header-icon">
  <span class="font-extrabold text-lg ml-1"><?= $headerTitle ?></span>
</div>
