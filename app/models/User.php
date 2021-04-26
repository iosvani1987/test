<?php

class User{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Find user by email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        // Bind Values
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            # code...
            return true;
        } else {
            # code...
            return false;
        }
    }

    // Register User
    public function register($data)
    {
        $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // El password esta encriptado
        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            //Match password
            return $row;
        } else {
            return false;
        }
    }

    // Change User password
    public function changePassword($email, $password)
    {
        $this->db->query('UPDATE users SET password=:password WHERE email=:email');
        // Bind Values
        $this->db->bind(':password', $password);
        $this->db->bind(':email', $email);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}