<?php $pageTitle = 'Apply — ' . $scholarship['name']; $bodyClass = 'app-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Apply for Scholarship</h2>
                <span><?= htmlspecialchars($scholarship['name']) ?></span>
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
                    <form method="POST" action="<?= APP_URL ?>/apply/<?= $scholarship['id'] ?>"
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
                                      rows="8" required><?= htmlspecialchars($essay ?? '') ?></textarea>
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

                            <div class="upload-grid">
                                <div class="upload-field required">
                                    <label>
                                        <i class="bi bi-file-earmark-text"></i>
                                        Transcript of Records <span class="req-star">*</span>
                                    </label>
                                    <div class="file-drop-zone" id="drop-transcript">
                                        <input type="file" name="transcript" accept=".pdf,.jpg,.jpeg,.png"
                                               class="file-input" id="file-transcript">
                                        <div class="drop-content">
                                            <i class="bi bi-cloud-upload"></i>
                                            <span>Drop file here or <strong>browse</strong></span>
                                            <small>PDF, JPG, PNG — max 5MB</small>
                                        </div>
                                        <div class="file-preview" id="preview-transcript"></div>
                                    </div>
                                </div>

                                <div class="upload-field required">
                                    <label>
                                        <i class="bi bi-person-badge"></i>
                                        Valid ID <span class="req-star">*</span>
                                    </label>
                                    <div class="file-drop-zone" id="drop-id_document">
                                        <input type="file" name="id_document" accept=".pdf,.jpg,.jpeg,.png"
                                               class="file-input" id="file-id_document">
                                        <div class="drop-content">
                                            <i class="bi bi-cloud-upload"></i>
                                            <span>Drop file here or <strong>browse</strong></span>
                                            <small>PDF, JPG, PNG — max 5MB</small>
                                        </div>
                                        <div class="file-preview" id="preview-id_document"></div>
                                    </div>
                                </div>

                                <div class="upload-field">
                                    <label>
                                        <i class="bi bi-envelope-paper"></i>
                                        Recommendation Letter <small>(optional)</small>
                                    </label>
                                    <div class="file-drop-zone" id="drop-recommendation">
                                        <input type="file" name="recommendation" accept=".pdf,.jpg,.jpeg,.png"
                                               class="file-input" id="file-recommendation">
                                        <div class="drop-content">
                                            <i class="bi bi-cloud-upload"></i>
                                            <span>Drop file here or <strong>browse</strong></span>
                                            <small>PDF, JPG, PNG — max 5MB</small>
                                        </div>
                                        <div class="file-preview" id="preview-recommendation"></div>
                                    </div>
                                </div>

                                <div class="upload-field">
                                    <label>
                                        <i class="bi bi-paperclip"></i>
                                        Other Document <small>(optional)</small>
                                    </label>
                                    <div class="file-drop-zone" id="drop-other">
                                        <input type="file" name="other" accept=".pdf,.jpg,.jpeg,.png"
                                               class="file-input" id="file-other">
                                        <div class="drop-content">
                                            <i class="bi bi-cloud-upload"></i>
                                            <span>Drop file here or <strong>browse</strong></span>
                                            <small>PDF, JPG, PNG — max 5MB</small>
                                        </div>
                                        <div class="file-preview" id="preview-other"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="apply-submit">
                            <a href="<?= APP_URL ?>/scholarships" class="btn-cancel">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn-submit-app" id="submitBtn">
                                <i class="bi bi-send-fill"></i>
                                Submit Application
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

    zone.addEventListener('click', () => input.click());
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        if (e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
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
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';
});
</script>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>