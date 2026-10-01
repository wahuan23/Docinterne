(() => {
    const folderModal = document.getElementById('folderModal');

    if (!folderModal) {
        return;
    }

    const openFolderModal = document.getElementById('openFolderModal');
    const closeFolderModal = document.getElementById('closeFolderModal');
    const cancelFolderModal = document.getElementById('cancelFolderModal');
    const fichier = document.getElementById('fichier');

    openFolderModal?.addEventListener('click', () => {
        folderModal.classList.add('active');
        setTimeout(() => fichier?.focus(), 100);
    });

    closeFolderModal?.addEventListener('click', () => {
        folderModal.classList.remove('active');
    });

    cancelFolderModal?.addEventListener('click', () => {
        folderModal.classList.remove('active');
    });

    folderModal.addEventListener('click', (event) => {
        if (event.target === folderModal) {
            folderModal.classList.remove('active');
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            folderModal.classList.remove('active');
        }
    });

    if (folderModal.dataset.open === 'true') {
        folderModal.classList.add('active');
    }
})();