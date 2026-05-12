<?php $pageTitle = (isset($editMode) && $editMode ? 'Edit — ' : 'Apply — ') . $scholarship['name']; $bodyClass = 'app-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Apply for Scholarship</h2>
                <span><?= isset($editMode) ? 'Edit Application — ' : '' ?><?= htmlspecialchars($scholarship['name']) ?></span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <div class="apply-layout">
                <!-- Scholarship Info Card -->
                <div class="apply-sidebar">
                    <div class="schol-info-card">
                        <div class="schol-info-header">
                            <div class="schol-info-icon"><i class="bi bi-award-fill"></i></div>
                            <h3><?= htmlspecialchars($scholarship['name']) ?></h3>
                        </div>
                        <div class="schol-info-amount">
                            <span class="amount-label">Award Amount</span>
                            <span class="amount-value">₱<?= number_format($scholarship['amount']) ?></span>
                        </div>
                        <div class="schol-info-details">
                            <div class="detail-row">
                                <i class="bi bi-clock"></i>
                                <span>Deadline: <strong><?= date('F j, Y', strtotime($scholarship['deadline'])) ?></strong></span>
                            </div>
                            <div class="detail-row">
                                <i class="bi bi-<?= $scholarship['allows_multiple'] ? 'infinity' : 'star-fill' ?>"></i>
                                <span><?= $scholarship['allows_multiple'] ? 'Multiple applications allowed' : 'Exclusive scholarship' ?></span>
                            </div>
                            <?php if ($scholarship['slots']): ?>
                                <div class="detail-row">
                                    <i class="bi bi-people-fill"></i>
                                    <span><strong><?= $scholarship['slots'] ?></strong> slots available</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="schol-info-desc">
                            <strong>About</strong>
                            <p><?= nl2br(htmlspecialchars($scholarship['description'])) ?></p>
                        </div>
                        <?php if (!empty($scholarship['requirements'])): ?>
                            <div class="schol-info-req">
                                <strong><i class="bi bi-list-check"></i> Requirements</strong>
                                <p><?= nl2br(htmlspecialchars($scholarship['requirements'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Application Form -->
                <div class="apply-form-wrap">
                    <form method="POST" action="<?= isset($editMode) ? APP_URL . '/applications/' . $app['id'] . '/resubmit' : APP_URL . '/apply/' . $scholarship['id'] ?>"
                          enctype="multipart/form-data" class="apply-form" id="applyForm" novalidate>
                        <input type="hidden" name="_token" value="<?= $csrf ?>">

                        <!-- Personal Statement -->
                        <div class="form-section">
                            <div class="section-label">
                                <span class="section-num">01</span>
                                <div>
                                    <h4>Personal Statement</h4>
                                    <p>Tell us about yourself, your goals, and why you deserve this scholarship.</p>
                                </div>
                            </div>
                            <textarea name="essay" class="form-control textarea-lg"
                                      placeholder="Write your personal statement here (minimum 50 characters)..."
                                      rows="8" required><?= htmlspecialchars($essay ?? (isset($editMode) && $editMode ? $app['essay'] : '')) ?></textarea>
                            <div class="char-counter"><span id="charCount">0</span> characters</div>
                        </div>

                        <!-- Documents -->
                        <div class="form-section">
                            <div class="section-label">
                                <span class="section-num">02</span>
                                <div>
                                    <h4>Required Documents</h4>
                                    <p>Upload clear, legible copies. Accepted: PDF, JPG, PNG (max 5MB each)</p>
                                </div>
                            </div>
                            
                            <?php
                            $existingDocs = [];
                            if (!empty($documents)) {
                                foreach ($documents as $doc) $existingDocs[$doc['doc_type']] = $doc;
                            }
                            $docFields = [
                                'transcript'     => ['label' => 'Transcript of Records',   'icon' => 'file-earmark-text', 'required' => true],
                                'coe_cor'        => ['label' => 'COE / COR',               'icon' => 'file-earmark-ruled', 'required' => true],
                                'good_moral'     => ['label' => 'Good Moral Certificate',  'icon' => 'patch-check',       'required' => true],
                                'id_document'    => ['label' => 'Valid ID',                 'icon' => 'person-badge',      'required' => true],
                                'recommendation' => ['label' => 'Recommendation Letter',   'icon' => 'envelope-paper',    'required' => false],
                                'other'          => ['label' => 'Other Document',           'icon' => 'paperclip',         'required' => false],
                            ];
                            ?>
                            <div class="upload-grid">
                                <?php foreach ($docFields as $key => $meta): ?>
                                    <div class="upload-field <?= $meta['required'] ? 'required' : '' ?>">
                                        <label>
                                            <i class="bi bi-<?= $meta['icon'] ?>"></i>
                                            <?= $meta['label'] ?>
                                            <?= $meta['required'] ? '<span class="req-star">*</span>' : '<small>(optional)</small>' ?>
                                        </label>
                                        <?php if (isset($existingDocs[$key])): ?>
                                            <div class="existing-doc-notice">
                                                <i class="bi bi-file-check-fill"></i>
                                                <span>Current: <strong><?= htmlspecialchars($existingDocs[$key]['original_name'] ?? basename($existingDocs[$key]['path'])) ?></strong></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="file-drop-zone" id="drop-<?= $key ?>">
                                            <input type="file" name="<?= $key ?>" accept=".pdf,.jpg,.jpeg,.png"
                                                class="file-input" id="file-<?= $key ?>">
                                            <div class="drop-content">
                                                <i class="bi bi-cloud-upload"></i>
                                                <span><?= isset($existingDocs[$key]) ? 'Upload to <strong>replace</strong>' : 'Drop file here or <strong>browse</strong>' ?></span>
                                                <small>PDF, JPG, PNG — max 5MB</small>
                                            </div>
                                            <div class="file-preview" id="preview-<?= $key ?>"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="apply-submit">
                            <a href="<?= isset($editMode) ? APP_URL . '/applications/' . $app['id'] : APP_URL . '/scholarships' ?>" class="btn-cancel">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn-submit-app" id="submitBtn">
                                <i class="bi bi-send-fill"></i>
                                <?= isset($editMode) ? 'Resubmit Application' : 'Submit Application' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Character counter
const essay = document.querySelector('textarea[name="essay"]');
const counter = document.getElementById('charCount');
essay.addEventListener('input', () => counter.textContent = essay.value.length);

// File upload preview
document.querySelectorAll('.file-drop-zone').forEach(zone => {
    const input = zone.querySelector('.file-input');
    const preview = zone.querySelector('.file-preview');
    const content = zone.querySelector('.drop-content');
    
    zone.addEventListener('click', (e) => {
        if (e.target !== input) {
            input.click();
        }
    });
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');

        if (e.dataTransfer.files.length > 0) {
            input.files = e.dataTransfer.files;
            handleFile(input.files[0]);
        }
    });
    input.addEventListener('change', () => { if (input.files[0]) handleFile(input.files[0]); });

    function handleFile(file) {
        content.style.display = 'none';
        preview.innerHTML = `
            <div class="file-selected">
                <i class="bi bi-file-check-fill"></i>
                <div>
                    <strong>${file.name}</strong>
                    <small>${(file.size / 1024).toFixed(1)} KB</small>
                </div>
                <button type="button" onclick="clearFile(this)" class="remove-file">
                    <i class="bi bi-x"></i>
                </button>
            </div>`;
    }
});

function clearFile(btn) {
    const zone = btn.closest('.file-drop-zone');
    zone.querySelector('.file-input').value = '';
    zone.querySelector('.file-preview').innerHTML = '';
    zone.querySelector('.drop-content').style.display = '';
}

// Confirm on submit
document.getElementById('applyForm').addEventListener('submit', function(e) {
    const requiredInputs = document.querySelectorAll('.upload-field.required .file-input');
    let missingDocs = false;

    requiredInputs.forEach(input => {
        // In edit mode, an existing doc counts as already uploaded
        const hasExisting = !!input.closest('.upload-field').querySelector('.existing-doc-notice');
        if (!input.files.length && !hasExisting) missingDocs = true;
    });

    const essay = document.querySelector('textarea[name="essay"]');
    if (missingDocs || essay.value.trim().length < 50) {
        // Let PHP handle the error message — just don't disable the button
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';
});
</script>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>