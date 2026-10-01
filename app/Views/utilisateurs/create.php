<?= $this->include('templates/header', ['title' => 'Ajouter un utilisateur']) ?>

<div class="form-page">
    <h1>Ajouter un utilisateur</h1>

    <form class="user-form" action="/docinterne/utilisateurs/store" method="post">
        <?= csrf_field() ?>
        <div class="form-field">
            <label for="nom">Nom :</label>
            <input
                type="text"
                id="nom"
                name="nom"
                value="<?= esc(old('nom')) ?>"
                required
            >
        </div>

        <div class="form-field">
            <label for="email">Email :</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= esc(old('email')) ?>"
                required
            >
        </div>

        <div class="form-field">
            <label for="password">Mot de passe :</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >
        </div>

        <div class="form-field">
            <label for="role">Rôle :</label>

            <select id="role" name="role" required>
                <option value="user" <?= old('role') === 'user' ? 'selected' : '' ?>>
                    Utilisateur
                </option>

                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>
                    Administrateur
                </option>
            </select>
        </div>

        <div class="form-field">
            <label for="departement_id">Département :</label>
            <select id="departement_id" name="departement_id" required>
                <?php foreach ($departements as $departement): ?>
                    <option
                        value="<?= esc($departement['id']) ?>"
                        <?= old('departement_id') == $departement['id'] ? 'selected' : '' ?>
                    >
                        <?= esc($departement['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        

        <div class="form-actions">
            <button class="form-submit" type="submit">
                Ajouter
            </button>

            <a class="back-link" href="/docinterne/utilisateurs">
                Retour aux utilisateurs
            </a>
        </div>
    </form>
</div>

<?= $this->include('templates/footer') ?>