<?php
/**
 * Partial : formulaire article (create + edit)
 *
 * Variables attendues :
 *   $article    : null (création) | array (édition)
 *   $categories : array
 *   $statuts    : array
 *   $erreur     : string
 *   $isEdit     : bool
 */

// Valeur d'un champ : POST en priorité (erreur de validation), sinon BDD, sinon défaut
$val = fn(string $key, string $default = '') =>
    htmlspecialchars($_POST[$key] ?? ($article[$key] ?? $default), ENT_QUOTES, 'UTF-8');
?>

<?php if (!empty($erreur)): ?>
    <div class="alert alert-danger alert-dismissible">
        <?= htmlspecialchars($erreur) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" id="articleForm">

    <div class="row g-4">

        <!-- ══════════════════════════════════
             COLONNE PRINCIPALE
        ══════════════════════════════════ -->
        <div class="col-lg-8">

            <!-- Titre -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <label class="form-label fw-semibold" for="titre">Titre <span class="text-danger">*</span></label>
                    <input type="text" id="titre" name="titre" class="form-control form-control-lg"
                           required placeholder="Titre de l'article"
                           value="<?= $val('titre') ?>">
                </div>
            </div>

            <!-- Contenu TinyMCE -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <label class="form-label fw-semibold" for="contenu">
                        Contenu <span class="text-danger">*</span>
                    </label>
                    <!-- IMPORTANT : on n'échappe PAS le contenu HTML ici -->
                    <textarea id="contenu" name="contenu" class="form-control" rows="12"><?= $_POST['contenu'] ?? ($article['contenu'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Meta description -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <label class="form-label fw-semibold" for="meta_description">
                        Meta description
                        <small class="text-muted fw-normal">(max 160 car.)</small>
                    </label>
                    <textarea id="meta_description" name="meta_description"
                              class="form-control" rows="2" maxlength="160"
                              placeholder="Résumé affiché dans Google…"><?= $val('meta_description') ?></textarea>
                    <div class="form-text">
                        <span id="metaCount"><?= strlen($_POST['meta_description'] ?? ($article['meta_description'] ?? '')) ?></span>/160 caractères
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════
             COLONNE SIDEBAR
        ══════════════════════════════════ -->
        <div class="col-lg-4">

            <!-- Publier -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold border-bottom">
                    Publication
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="statut_id">Statut</label>
                        <select id="statut_id" name="statut_id" class="form-select">
                            <?php foreach ($statuts as $s):
                                $sel = ($s['id'] == ($article['statut_id'] ?? 1)) ? 'selected' : '';
                            ?>
                                <option value="<?= $s['id'] ?>" <?= $sel ?>>
                                    <?= htmlspecialchars($s['libelle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="date_publication">Date</label>
                        <input type="date" id="date_publication" name="date_publication"
                               class="form-control"
                               value="<?= $val('date_publication', date('Y-m-d')) ?>">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? '✓ Enregistrer' : '+ Créer l\'article' ?>
                        </button>
                        <a href="<?= adminUrl('articles') ?>" class="btn btn-outline-secondary btn-sm">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>

            <!-- Catégorie -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold border-bottom">Catégorie</div>
                <div class="card-body">
                    <select name="categorie_id" class="form-select" required>
                        <option value="">— Choisir —</option>
                        <?php foreach ($categories as $c):
                            $sel = ($c['id'] == ($article['categorie_id'] ?? '')) ? 'selected' : '';
                        ?>
                            <option value="<?= $c['id'] ?>" <?= $sel ?>>
                                <?= htmlspecialchars($c['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Image principale -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom">Image principale</div>
                <div class="card-body">

                    <?php
                    // Chemin de l'image actuelle (édition uniquement)
                    $imgActuelle = $article['image_principale'] ?? null;
                    $imgUrl      = $imgActuelle
                        ? rtrim(BASE_URL, '/') . '/public/' . ltrim($imgActuelle, '/')
                        : null;
                    ?>

                    <?php if ($isEdit && $imgActuelle): ?>
                        <!-- Aperçu image actuelle -->
                        <div id="currentImg" class="mb-3">
                            <img src="<?= htmlspecialchars($imgUrl) ?>"
                                 alt="<?= htmlspecialchars($article['alt_image'] ?? '') ?>"
                                 class="img-fluid rounded"
                                 style="max-height:140px;width:100%;object-fit:cover;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox"
                                       name="delete_image" id="deleteImg" value="on">
                                <label class="form-check-label text-danger small" for="deleteImg">
                                    Supprimer cette image
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Upload nouvelle image -->
                    <div id="uploadZone" <?= ($isEdit && $imgActuelle) ? 'style="display:none"' : '' ?>>
                        <input type="file" name="image" id="imageInput"
                               class="form-control form-control-sm"
                               accept="image/jpeg,image/png,image/gif,image/webp">
                        <div class="form-text">JPG, PNG, GIF, WebP — max 5 Mo</div>
                        <!-- Prévisualisation avant envoi -->
                        <div id="previewBox" class="mt-2" style="display:none">
                            <img id="previewImg" src="" alt="Aperçu"
                                 class="img-fluid rounded"
                                 style="max-height:120px;width:100%;object-fit:cover;">
                        </div>
                    </div>

                    <!-- Alt image -->
                    <div class="mt-3">
                        <label class="form-label fw-semibold small" for="alt_image">
                            Texte alt <small class="text-muted">(SEO + accessibilité)</small>
                        </label>
                        <input type="text" id="alt_image" name="alt_image"
                               class="form-control form-control-sm"
                               placeholder="Ex: Défilé militaire à Téhéran"
                               value="<?= $val('alt_image') ?>">
                    </div>
                </div>
            </div>

        </div><!-- /col sidebar -->
    </div><!-- /row -->
</form>

<!-- ═══════════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════════ -->

<!-- TinyMCE via CDN Tiny Cloud -->
<script src="https://cdn.tiny.cloud/1/<?= defined('TINYMCE_API_KEY') ? TINYMCE_API_KEY : 'no-api-key' ?>/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#contenu',
    language: 'fr_FR',
    height: 420,
    plugins: 'lists link image table code',
    toolbar: [
        'undo redo | blocks | bold italic underline',
        'bullist numlist | link image table | code'
    ].join(' | '),
    menubar: false,

    // ── Upload image dans TinyMCE ──────────────────────────────
    // Quand l'utilisateur insère une image via le bouton image de MCE,
    // on upload le fichier vers notre serveur et on retourne l'URL.
    images_upload_url: '<?= adminUrl('upload-image') ?>',
    images_upload_credentials: true,
    automatic_uploads: true,
    file_picker_types: 'image',

    // Callback "Parcourir" : ouvre un sélecteur de fichier natif
    file_picker_callback: function(callback, value, meta) {
        if (meta.filetype !== 'image') return;
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/jpeg,image/png,image/gif,image/webp';
        input.addEventListener('change', function() {
            const file = this.files[0];
            const formData = new FormData();
            formData.append('file', file);
            // On envoie vers le même handler d'upload
            fetch('<?= adminUrl('upload-image') ?>', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => {
                if (data.location) {
                    callback(data.location, { title: file.name });
                } else {
                    alert('Erreur upload : ' + (data.error ?? 'inconnue'));
                }
            })
            .catch(() => alert('Erreur réseau lors de l\'upload.'));
        });
        input.click();
    },

    // CSS du site dans l'éditeur pour un rendu WYSIWYG
    content_css: [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
        '<?= rtrim(BASE_URL, '/') ?>/public/css/style.css'
    ],
    body_class: 'container py-3',
    relative_urls: false,
    remove_script_host: false,
    convert_urls: true
});
</script>

<script>
// ── Compteur meta description ────────────────────────────────
const metaArea  = document.getElementById('meta_description');
const metaCount = document.getElementById('metaCount');
metaArea.addEventListener('input', () => {
    metaCount.textContent = metaArea.value.length;
    metaCount.style.color = metaArea.value.length > 140 ? '#dc3545' : 'inherit';
});

// ── Prévisualisation image avant upload ───────────────────────
const imageInput = document.getElementById('imageInput');
const previewBox = document.getElementById('previewBox');
const previewImg = document.getElementById('previewImg');

imageInput?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) { previewBox.style.display = 'none'; return; }
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewBox.style.display = 'block';
    };
    reader.readAsDataURL(file);
});

<?php if ($isEdit && !empty($article['image_principale'] ?? null)): ?>
// ── Gestion suppression / remplacement image (édition) ────────
const deleteCheckbox = document.getElementById('deleteImg');
const uploadZone     = document.getElementById('uploadZone');
const currentImg     = document.getElementById('currentImg');

deleteCheckbox?.addEventListener('change', function () {
    if (this.checked) {
        currentImg.style.opacity = '0.4';
        uploadZone.style.display = 'block';
    } else {
        currentImg.style.opacity = '1';
        uploadZone.style.display = 'none';
        if (imageInput) imageInput.value = '';
        previewBox.style.display = 'none';
    }
});
<?php endif; ?>
</script>