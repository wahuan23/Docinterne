<?= $this->include('templates/header', ['title' => 'Dossier', 'css' => 'accueiladmin']) ?>

<?php $formErrors = session()->getFlashdata('errors') ?? []; ?>
    <section class="admin-page document-list-page">
    <div class="container">
        <div class="page-heading">
          <h1>Liste des Fichiers</h1>
          <?php echo $userId; ?>
          <button type="button" class="add-link" id="openFolderModal">+ Ajouter un fichier </button>
        </div>
    <?php if (empty($documents)): ?>
        <p class="empty-state">Liste des fichiers </p>
    <?php else: ?>
        <div class="user-list">
            <?php foreach ($documents as $document): ?>
                <div class="user-row">
                    <div> 
                        <div><?= esc($document['document_titre']) ?> </a></div> 
                        <div><?= esc($document['can_view']) ?> </a></div> 
                        <div><?= esc($document['can_edit']) ?> </a></div> 
                        <div><?= esc($document['can_delete']) ?> </a></div> 
                       
                    </div>
                    <div class="user-actions document-actions">
                        <a class="document-action document-action--permissions"
                           href="<?= base_url('docinterne/documents/pagePermission/' . $document['document_id']) ?>"
                           aria-label="Gérer les permissions"
                           title="Gérer les permissions">
                            <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.7 1.7-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.03 1.56V20h-2.4v-.2a1.7 1.7 0 0 0-1.03-1.56 1.7 1.7 0 0 0-1.88.34l-.06.06-1.7-1.7.06-.06A1.7 1.7 0 0 0 8.46 15a1.7 1.7 0 0 0-1.56-1.03H6.7v-2.4h.2A1.7 1.7 0 0 0 8.46 10a1.7 1.7 0 0 0-.34-1.88l-.06-.06 1.7-1.7.06.06a1.7 1.7 0 0 0 1.88.34 1.7 1.7 0 0 0 1.03-1.56V5h2.4v.2a1.7 1.7 0 0 0 1.03 1.56 1.7 1.7 0 0 0 1.88-.34l.06-.06 1.7 1.7-.06.06A1.7 1.7 0 0 0 19.4 10a1.7 1.7 0 0 0 1.56 1.03h.2v2.4h-.2A1.7 1.7 0 0 0 19.4 15Z" />
                            </svg>
                        </a>
                    </div>
                    <div class="user-actions document-actions">
                       <?php if (filter_var($document['can_view'], FILTER_VALIDATE_BOOLEAN)): ?>
                        <a class="document-action document-action--view"
                           href="<?= base_url('docinterne/documents/download/' . $document['document_id']) ?>"
                           aria-label="Consulter le document"
                           title="Consulter le document">
                            <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />
                                <circle cx="12" cy="12" r="2.5" />
                            </svg>
                        </a>
                    <?php endif; ?>

                    <?php if (filter_var($document['can_edit'], FILTER_VALIDATE_BOOLEAN)): ?>
                        <a class="document-action document-action--edit"
                           href="<?= base_url('docinterne/documents/edit/' . $document['document_id']) ?>"
                           aria-label="Modifier le document"
                           title="Modifier le document">
                            <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="m4 16.5-.8 3.3 3.3-.8L18.8 6.7a2.3 2.3 0 0 0-3.3-3.3L4 16.5Z" />
                                <path d="m14.4 4.8 4.8 4.8M4 19.8l3.3-.8" />
                            </svg>
                        </a>
                    <?php endif; ?>
                      <?php if (filter_var($document['can_delete'], FILTER_VALIDATE_BOOLEAN)): ?>
                    <form class="document-delete-form" action="<?= base_url('docinterne/documents/delete/' . $document['document_id']) ?>" method="post" onsubmit="return confirm('Supprimer ce document ?')">
                        <?= csrf_field() ?>
                        <button class="document-action document-action--delete" type="submit" aria-label="Supprimer le document" title="Supprimer le document">
                            <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M4 7h16M10 11v6M14 11v6M6.5 7l.7 13h9.6l.7-13M9 7V4h6v3" />
                            </svg>
                        </button>
                    </form>
    <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
    <?php endif; ?>
                </div>
</section>

<div class="folder-modal-overlay" id="folderModal"   data-open="<?= !empty($formErrors) ? 'true' : 'false' ?>">
    <div class="folder-modal">
        <div class="folder-modal-header">
            <div>
                <h2>Nouveau fichier</h2>
                <p>Créer un nouveau fichier</p>
            </div>
            <button type="button" class="modal-close" id="closeFolderModal">x</button>
        </div>
            
        <form action="<?= base_url('docinterne/documents/store') ?>" method="post" enctype="multipart/form-data">

            <?= csrf_field() ?>
            <input type="hidden" name="dossier_id" value="<?= esc($dossierId) ?>">
            <?php if (!empty($formErrors)): ?>
                <div class="form-errors" role="alert">
                    <?php foreach ($formErrors as $error): ?>
                        <p><?= esc($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="fichier"> Sélectionner un fichier </label>
                <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.jpg,.jpeg,.png" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="cancel-button" id="cancelFolderModal"> Annuler </button>
                <button type="submit" class="create-button"> Créer le fichier </button>
            </div>

        </form>
    </div>
</div>

<script src="<?= base_url('assets/js/documents.js') ?>" defer></script>
