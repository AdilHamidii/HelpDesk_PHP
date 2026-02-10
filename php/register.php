<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config.php';

    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        setFlashMessage('error', 'Tous les champs sont requis.');
        redirect('../html/register.html');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlashMessage('error', 'Adresse e-mail invalide.');
        redirect('../html/register.html');
    }

    if ($password !== $confirm_password) {
        setFlashMessage('error', 'Les mots de passe ne correspondent pas.');
        redirect('../html/register.html');
    }

    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        setFlashMessage('error', 'Erreur de base de données: ' . $conn->error);
        redirect('../html/register.html');
    }

    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        setFlashMessage('error', 'Le nom d\'utilisateur ou l\'e-mail existe déjà.');
        redirect('../html/register.html');
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        setFlashMessage('error', 'Erreur de base de données: ' . $conn->error);
        redirect('../html/register.html');
    }

    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        setFlashMessage('success', 'Inscription réussie! Vous pouvez maintenant vous connecter.');
        redirect('../html/login.html');
    } else {
        setFlashMessage('error', 'Erreur lors de l\'inscription: ' . $stmt->error);
        redirect('../html/register.html');
    }

    $stmt->close();
    $conn->close();
} else {
    redirect('../html/register.html');
}

?>