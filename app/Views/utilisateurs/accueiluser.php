<?= $this->include('templates/header', ['title' => 'Dossier', 'css' => 'accueiladmin']) ?>
  <section class="admin-page">
    <div class="container">
      <div class="page-heading">
          <h1>Liste des dossiers</h1>
          <button type="button" class="add-link" id="openFolderModal">
                + Ajouter un dossier
            </button>
          
      </div>
    <?php if (empty($dossiers)): ?>
        <p class="empty-state">Liste des dossiers 
      </p>
    <?php else: ?>
        <div class="user-list">
            <?php foreach ($dossiers as $dossier): 
              ?>
                <div class="user-row">
                    <div> 
                      <div><a href="<?= base_url('docinterne/documents/list' . '/' . $dossier['dossier_id']) ?>"><?= esc($dossier['dossier_nom']) ?> </a></div> 
                    </div>
                    <div class="user-actions">  
                      <?= csrf_field() ?>
                      <button class="delete-button" onclick="return confirm('Voulez-vous vraiment supprimer ce dossier ?')"type="submit">Supprimer</button>     
                     
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
    <?php endif; ?>
                </div>
</section>

<div class="folder-modal-overlay" id="folderModal">

    <div class="folder-modal">

        <div class="folder-modal-header">
            <div>
                <h2>Nouveau dossier</h2>
                <p>Créer un nouveau dossier</p>
            </div>

            <button type="button" class="modal-close" id="closeFolderModal">
                ×
            </button>
        </div>

        <form
            action="<?= base_url('docinterne/dossiers/store') ?>"
            method="post"
        >

            <?= csrf_field() ?>

            <div class="form-group">

                <label for="dossier_nom">
                    Nom du dossier
                </label>

                <input
                    type="text"
                    id="dossier_nom"
                    name="dossier_nom"
                    placeholder="Ex : Documents administratifs"
                    required
                    autocomplete="off"
                >

            </div>

            <div class="modal-actions">

                <button
                    type="button"
                    class="cancel-button"
                    id="cancelFolderModal"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="create-button"
                >
                    Créer le dossier
                </button>

            </div>

        </form>

    </div>

</div>

<script>

    const folderModal = document.getElementById('folderModal');
    const openFolderModal = document.getElementById('openFolderModal');
    const closeFolderModal = document.getElementById('closeFolderModal');
    const cancelFolderModal = document.getElementById('cancelFolderModal');
    const dossierNom = document.getElementById('dossier_nom');
    // Ouvrir le modal
    openFolderModal.addEventListener('click', function () {
        folderModal.classList.add('active');

        // Met automatiquement le curseur dans le champ
        setTimeout(function () {
            dossierNom.focus();
        }, 100);

    });
    // Fermer avec X
    closeFolderModal.addEventListener('click', function () {

        folderModal.classList.remove('active');

    });
    // Fermer avec Annuler
    cancelFolderModal.addEventListener('click', function () {

        folderModal.classList.remove('active');

    });
    // Fermer en cliquant à l'extérieur
    folderModal.addEventListener('click', function (event) {
        if (event.target === folderModal) {
            folderModal.classList.remove('active');
        }
    });

    // Fermer avec la touche Échap
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            folderModal.classList.remove('active');
        }
    });

</script>
 
    
<?= $this->include('templates/footer') ?>