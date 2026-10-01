<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
</head>
<div class="login-shell">
    <section class="login-panel">
        <a class="login-brand" href="/docinterne/">Doc<span>Interne</span></a>
        <div class="login-intro">
            <p class="login-kicker">Espace sécurisé</p>
            <h1>Bon retour</h1>
            <p>Connectez-vous pour accéder à votre espace documentaire.</p>
        </div>

        <form class="user-form" action="<?= base_url('docinterne/auth/authenticate') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="email">Adresse email</label>
                <input
                    type="email"
                    id="email"
                    name="email"         
                    required
                    value="<?= esc(old('email')) ?>"
                >
            </div>
            <div class="form-field">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required
                    value="<?= esc(old('password')) ?>"
                >
            </div>

            <div class="form-actions">
                <button class="form-submit" type="submit">Se connecter</button>
            </div>
        </form>
        <p class="login-note">Vos documents, au même endroit.</p>
    </section>
    <aside class="login-visual" aria-label="Espace collaboratif">
        <div class="visual-topline">
            <span>DocInterne</span>
            <span class="visual-close" aria-hidden="true">&times;</span>
        </div>
        <div class="visual-copy">
            <span class="visual-label">Votre équipe, vos documents</span>
            <strong>Travaillez ensemble<br>plus simplement.</strong>
        </div>
        <div class="visual-card visual-card--yellow">Réunion équipe <small>09:30 - 10:00</small></div>
        <div class="visual-card visual-card--white">Documents partagés <small>12 nouveaux fichiers</small></div>
        <div class="visual-orbit visual-orbit--one"></div>
        <div class="visual-orbit visual-orbit--two"></div>
    </aside>
</div>

