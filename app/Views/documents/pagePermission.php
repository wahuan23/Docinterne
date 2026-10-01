<?= $this->include('templates/header', ['title' => 'Gestion des permissions', 'css' => 'accueiladmin']) ?>

<?php
$formErrors = session()->getFlashdata('errors') ?? [];
$selectedUser = old('user_id', $selectedUserId ?? '');
$selectedPermission = $selectedPermission ?? [];
?>
<section class="admin-page permission-page">
    <div class="container">
        <div class="page-heading">
            <div>
                <span class="permission-eyebrow">Accès au document</span>
                <h1>Gérer les permissions</h1>
            </div>
        </div>

        <div class="permission-panel">
            <?php if ($success = session()->getFlashdata('success')): ?>
                <p class="permission-message permission-message--success" role="status"><?= esc($success) ?></p>
            <?php endif; ?>

            <?php if (!empty($formErrors)): ?>
                <div class="permission-message permission-message--error" role="alert">
                    <?php foreach ($formErrors as $error): ?>
                        <p><?= esc($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('docinterne/documents/pagePermission/' . $documentId) ?>" class="permission-form">
                <?= csrf_field() ?>

                <div class="permission-field">
                    <label for="permission-user">Utilisateur</label>
                    <select id="permission-user" name="user_id" required>
                        <option value="">Choisir un utilisateur</option>
                        <?php foreach ($utilisateurs as $utilisateur): ?>
                            <option value="<?= esc($utilisateur['id']) ?>" <?= (string) $selectedUser === (string) $utilisateur['id'] ? 'selected' : '' ?>>
                                <?= esc($utilisateur['nom']) ?> (<?= esc($utilisateur['matricule']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <fieldset class="permission-fieldset">
                    <legend>Autorisations</legend>
                    <div class="permission-options">
                        <label class="permission-option">
                            <input type="hidden" name="can_view" value="0">
                            <input type="checkbox" name="can_view" value="1" <?= old('can_view', !empty($selectedPermission) ? ((bool) $selectedPermission['can_view'] ? '1' : '0') : '1') === '1' ? 'checked' : '' ?>>
                            <span><strong>Voir</strong><small>Consulter le document</small></span>
                        </label>
                        <label class="permission-option">
                            <input type="hidden" name="can_edit" value="0">
                            <input type="checkbox" name="can_edit" value="1" <?= old('can_edit', !empty($selectedPermission) && $selectedPermission['can_edit'] ? '1' : '0') === '1' ? 'checked' : '' ?>>
                            <span><strong>Modifier</strong><small>Mettre à jour le document</small></span>
                        </label>
                        <label class="permission-option">
                            <input type="hidden" name="can_delete" value="0">
                            <input type="checkbox" name="can_delete" value="1" <?= old('can_delete', !empty($selectedPermission) && $selectedPermission['can_delete'] ? '1' : '0') === '1' ? 'checked' : '' ?>>
                            <span><strong>Supprimer</strong><small>Supprimer le document</small></span>
                        </label>
                    </div>
                </fieldset>

                <div class="permission-form-actions">
                    <button class="permission-submit" type="submit">Enregistrer les permissions</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.getElementById('permission-user').addEventListener('change', (event) => {
    const userId = event.target.value;
    const query = userId ? `?user_id=${encodeURIComponent(userId)}` : '';
    window.location.search = query;
});
</script>
