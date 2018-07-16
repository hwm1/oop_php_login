<?php
include_once 'dbh.inc.php';

class User extends Dbh
{

    private $first;
    private $last;
    private $email;
    private $uid;
    private $pwd;
    private $hashedPwd;

    public function makeUser($connection)
    {

        $this->first = mysqli_real_escape_string($connection, $_POST['first']);
        $this->last = mysqli_real_escape_string($connection, $_POST['last']);
        $this->email = mysqli_real_escape_string($connection, $_POST['email']);
        $this->uid = mysqli_real_escape_string($connection, $_POST['uid']);
        $this->pwd = mysqli_real_escape_string($connection, $_POST['pwd']);

        //error handlers
        //check for empty fields
        if (empty($this->first) ||
            empty($this->last) ||
            empty($this->email) ||
            empty($this->uid) ||
            empty($this->pwd)) {
            header("Location: ../signup.php?signup=empty");
            exit();
        }
        //check if input characters are valid
        if (!preg_match("/^[a-zA-Z]*$/", $this->first) || (!preg_match("/^[a-zA-Z]*$/", $this->last))) {
            header("Location: ../signup.php?signup=invalid");
            exit();
        }
        //check if email is valid
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../signup.php?signup=email");
            exit();
        }

        //see if have users with user name (uid)
        $sql = "SELECT * FROM users WHERE user_uid='$this->uid'";
        $result = mysqli_query($connection, $sql);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0) {
            header("Location: ../signup.php?signup=usertaken");
            exit();
        }
        //hash the password
        $this->hashedPwd = password_hash($this->pwd, PASSWORD_DEFAULT);
        //insert the user into the database
        $sql = "INSERT INTO users(user_first, user_last,
                        user_email, user_uid, user_pwd) VALUES('$this->first','$this->last',
                        '$this->email','$this->uid','$this->hashedPwd');";

        return $sql;

    }

    public function findUser($connection)
    {

        $this->uid = mysqli_real_escape_string($connection, $_POST['uid']);
        $this->pwd = mysqli_real_escape_string($connection, $_POST['pwd']);
        //error handlers
        //check if inputs are empty

        if (empty($this->uid) || empty($this->pwd)) {
            header("Location: ../index.php?login=empty");
            exit();
        }
        $sql = "SELECT * FROM users WHERE user_uid='$this->uid' OR user_email='$this->uid'";
        $result = mysqli_query($connection, $sql);
        $resultCheck = mysqli_num_rows($result);

        if ($resultCheck < 1) { //user not found in db
            header("Location: ../index.php?login=error");
            exit();
        }
        if ($row = mysqli_fetch_assoc($result)) {
            //dehash pwd
            $hashedPwdCheck = password_verify($this->pwd, $row['user_pwd']);

            if ($hashedPwdCheck == false) {
                header("Location: ../index.php?login=error");
                exit();
            }
            if ($hashedPwdCheck == true) {
                //log in the user here
                $_SESSION['u_id'] = $row['user_id'];
                $_SESSION['u_first'] = $row['user_first'];
                $_SESSION['u_last'] = $row['user_last'];
                $_SESSION['u_email'] = $row['user_email'];
                $_SESSION['u_uid'] = $row['user_uid'];
                header("Location: ../index.php?login=success");
                exit();
            }
        }

    }

}
