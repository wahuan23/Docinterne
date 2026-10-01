<?= $this->include('templates/header', ['title' => 'Modifier un utilisateur']) ?>

    <div class="form-page">
        <h1>Modifier un utilisateur</h1>

        <form class="user-form" action="/docinterne/utilisateurs/update/<?= $utilisateur['id'] ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="nom">Nom :</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    value="<?= esc($utilisateur['nom']) ?>"
                    required
                >
            </div>
            <div class="form-field">
                <label for="email">Email :</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= esc($utilisateur['email']) ?>"
                    required
                >
            </div>
            <div class="form-field">
                <label for="role">Rôle :</label>
                <select id="role" name="role" required>
                    <option value="user" <?= $utilisateur['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                    <option value="admin" <?= $utilisateur['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                </select>   
            </div>
            <div class="form-field">        
                <label for="active">Actif :</label>
                <input
                    type="checkbox"
                    id="active"
                    name="active"
                    value="1"
                    <?= $utilisateur['active'] ? 'checked' : '' ?>
                >           
            </div>
            <div class="form-field">    
                <label for="password">Mot de passe :</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                >
                <small>Si vous laissez ce champ vide, le mot de passe actuel sera conservé.</small>

            <div class="form-actions">
                <button class="form-submit" type="submit">Modifier</button>
                <a class="back-link" href="/docinterne/utilisateurs">Retour aux utilisateurs</a>
            </div>
        </form>
    </div>


<?= $this->include('templates/footer') ?>