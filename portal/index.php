<?php
include_once("../lib/portallib.php");
if ($_SESSION['loggedin']) {
  header("Location: home.php");
} else {
  header("Location: login.php");
}
?>
