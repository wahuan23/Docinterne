<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'Gestion des utilisateurs') ?></title>
<nav class="nav" id="nav">
<div class="nav-inner">
  <a href="<?= base_url('docinterne/utilisateurs') ?>" class="nav-logo"><span class="bee-icon"></span> DOC INTERNE</a>
  <ul class="nav-links" id="navLinks">
    <li><a href="<?= base_url('docinterne/utilisateurs') ?>">Utilisateurs</a></li>
    <li><a href="<?= base_url('docinterne/create') ?>">Ajout</a></li>
    <li class="nav-logout">
      <form method="post" action="<?= base_url('docinterne/logout') ?>">
        <?= csrf_field() ?>
        <button class="nav-logout-button" type="submit">Déconnexion</button>
      </form>
    </li>
  </ul>
  <button class="nav-toggle" id="navToggle" aria-label="Menu"><span></span></button>
</div>
</nav>  
<link rel="stylesheet" href="<?= base_url('assets/css/' . ($css ?? 'accueiladmin') . '.css') ?>">
<?php if (($title ?? '') === 'Connexion'): ?>
<link rel="stylesheet" href="<?= base_url('docinterne/assets/css/login.css') ?>">
<?php endif; ?>
</head>
<body class="<?= ($title ?? '') === 'Connexion' ? 'login-body' : '' ?>">
   
   <main>
