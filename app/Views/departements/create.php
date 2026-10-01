<?= $this->include('templates/header', ['title' => 'Ajouter un département']) ?>

<div class="form-page">
    <h1>Ajouter un département</h1>

    <form class="departement-form" action="/docinterne/departements/store" method="post">
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
        <div class="form-actions">
            <button class="form-submit" type="submit">
                Ajouter
            </button>
        </div>
    </form>
</div>

<?= $this->include('templates/footer') ?>