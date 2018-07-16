<?php
include_once 'header.php';
?>
<section class="main_container">
  <div class="main_wrapper">

    <h2>Signup</h2>
    <form class="signup_form" action="includes/signup.inc.php" method="POST">
    <input type="text" name="first" placeholder="First Name">
    <input type="text" name="last" placeholder="Last Name">
    <input type="text" name="email" placeholder="Email">
    <input type="text" name="uid" placeholder="User Name">
    <input type="password" name="pwd" placeholder="Password">
    <button type="submit" name="submit">Sign Up</button>
    </form>
  </div>
</section>
<?php
include_once 'footer.php';
?>