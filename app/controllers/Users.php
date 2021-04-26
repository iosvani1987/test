<?php

class Users extends Controller
{

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function login()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                # code...
                $data['email_err'] = 'Please enter email';
            } else {
                // Check for user/email
                if (!$this->userModel->findUserByEmail($data['email'])) {
                    // User not found
                    $data['email_err'] = 'No user found';
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                //Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    # Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Wrong Password';

                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }

    public function register()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Name
            if (empty($data['name'])) {
                # code...
                $data['name_err'] = 'Please enter name';
            }

            // Validate Email
            if (empty($data['email'])) {
                # code...
                $data['email_err'] = 'Please enter email';
            } else {
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = "Invalid email format";
                } else {
                    if ($this->userModel->findUserByEmail($data['email'])) {
                        # If exist a email notified 
                        $data['email_err'] = 'Email is already taken';
                    }
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                # code...
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirma Password
            if (empty($data['confirm_password'])) {
                # code...
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Password do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validate
                //die('SUCCESS');

                // Hash Password (Encripta el pass)
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if ($this->userModel->register($data)) {
                    // funcion del helper para que pnga un mensaje
                    flash('register_success', 'You are registered and can login');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('users/register', $data);
        }
    }

    public function createUserSession($user)
    {
        # code...
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('');
    }

    public function logout()
    {
        # code...
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);

        session_destroy();
        redirect('users/login');
    }

    public function changepassword()
    {
        // Check if is loged
        if (isLoggedIn()) {
            // Check for POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Init data
                $data = [
                    'oldpassword' => trim($_POST['oldpassword']),
                    'newpassword' => trim($_POST['newpassword']),
                    'repeatnewpassword' => trim($_POST['repeatnewpassword']),
                    'oldpassword_err' => '',
                    'newpassword_err' => '',
                    'repeatnewpassword_err' => ''
                ];

                // Validate oldpassword
                if (empty($data['oldpassword'])) {
                    # Empty
                    $data['oldpassword_err'] = 'Please enter  old password';
                } else {
                    # Verified that is equal to the old password
                    //Check and set logged in user
                    $loggedInUser = $this->userModel->login($_SESSION['user_email'], $data['oldpassword']);
                    if (!$loggedInUser) {
                        # code...
                        $data['oldpassword_err'] = 'Wrong Password';
                    }
                }

                // Validate new password
                if (empty($data['newpassword'])) {
                    $data['newpassword_err'] = 'Please enter new password';
                } else {
                    # Verified Length
                    if (strlen($data['newpassword']) < 6) {
                        $data['newpassword_err'] = 'Password must be at least 6 characters';
                    }

                    # Verified that new password is different to old password
                    if ($data['oldpassword'] == $data['newpassword']) {
                        $data['newpassword_err'] = 'New Password must be different old one';
                    }
                }

                // Validate repaet new password
                if (empty($data['repeatnewpassword'])) {
                    $data['repeatnewpassword_err'] = 'Please confirm new password';
                } else {
                    # Verified that new password is equals to repeat new password
                    if ($data['repeatnewpassword'] != $data['newpassword']) {
                        $data['repeatnewpassword_err'] = 'Password must be equals';
                    }
                }

                // Make sure errors are empty
                if (empty($data['oldpassword_err']) && empty($data['newpassword_err']) && empty($data['repeatnewpassword_err'])) {
                    //Encripto el password
                    $data['newpassword'] = password_hash($data['newpassword'], PASSWORD_DEFAULT);
                    // Change Password
                    if ($this->userModel->changepassword($_SESSION['user_email'], $data['newpassword'])) {
                        flash('post_message', 'Password Changed Successfuly');
                        redirect('pages/index');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // Load view with errors
                    $this->view('users/changepassword', $data);
                }
            } else {
                // Init data
                $data = [
                    'oldpassword' => '',
                    'newpassword' => '',
                    'repeatnewpassword' => '',
                    'oldpassword_err' => '',
                    'newpassword_err' => '',
                    'repeatnewpassword_err' => ''
                ];

                // Load view
                $this->view('users/changepassword', $data);
            }
        } else {
            redirect('pages/index');
        }
    }
}
