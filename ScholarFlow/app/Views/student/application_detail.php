<?php
// Prevent undefined-variable warnings in IDEs; $app/$auth are expected from controllers.
$app = $app ?? [];
$documents = $documents ?? [];
$auth = $auth ?? [];
$csrf = $csrf ?? null;

$pageTitle = 'Application #' . (($app['id'] ?? '') ?: '');
$bodyClass = 'app-body';
$isAdmin = (($auth['role'] ?? '') === 'admin');
$isEditing = isset($csrf);
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Application Detail</h2>
                <span>Reference #<?= str_pad($app['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </div>
            <?php if (in_array($auth['role'] ?? '', ['admin', 'reviewer'])): ?>
                <div class="topbar-actions">
                    <?php if (!$isEditing): ?>
                        <?php $editUrl = ($auth['role'] === 'reviewer')
                            ? APP_URL . '/reviewer/applications/' . (int)$app['id'] . '/edit'
                            : APP_URL . '/admin/applications/' . (int)$app['id'] . '/edit'; ?>
                        <a href="<?= $editUrl ?>" class="btn-add-sm" id="editBtn">
                            <i class="bi bi-pencil-fill"></i> Edit Application
                        </a>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </header>


        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <!-- Back link -->
            <a href="javascript:history.back()" class="back-link">
                <i class="bi bi-arrow-left"></i> Back
            </a>

<div class="detail-layout" id="top">
                <!-- Main Info -->
                <div class="detail-main">
                    <!-- Status Banner -->
                    <div class="status-banner status-banner-<?= $app['status'] ?>">
                        <div class="status-banner-icon">
                            <?php if ($app['status'] === 'approved'): ?>
                                <i class="bi bi-patch-check-fill"></i>
                            <?php elseif ($app['status'] === 'rejected'): ?>
                                <i class="bi bi-x-circle-fill"></i>
                            <?php else: ?>
                                <i class="bi bi-hourglass-split"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h4><?= ucfirst($app['status']) ?></h4>
                            <?php if ($app['status'] === 'pending'): ?>
                                <p>Your application is currently under review.</p>
                            <?php elseif ($app['status'] === 'approved'): ?>
                                <p>Congratulations! Your application has been approved.</p>
                            <?php else: ?>
                                <p>Unfortunately, your application was not approved this time.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Applicant Profile -->
                    <div class="detail-card reviewer-applicant-card">
                        <div class="applicant-profile">
                            <?php if (!empty($app['avatar'])): ?>
                                <img class="applicant-avatar-lg"
                                     src="<?= APP_URL . '/uploads/' . htmlspecialchars($app['avatar']) ?>"
                                     alt="<?= htmlspecialchars($app['applicant_name'] ?? 'Applicant') ?>">
                            <?php else: ?>
                                <div class="applicant-avatar-lg">
                                    <?= strtoupper(substr($app['applicant_name'] ?? 'U', 0, 2)) ?>
                                </div>
                            <?php endif; ?>

                            <div class="applicant-profile-info">
                                <h4><?= htmlspecialchars($app['applicant_name']) ?></h4>
                                <p><?= htmlspecialchars($app['applicant_email']) ?></p>
                                <?php if (!empty($app['phone'])): ?>
                                    <p><i class="bi bi-telephone"></i> <?= htmlspecialchars($app['phone']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($app['address'])): ?>
                                    <p><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($app['address']) ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="applicant-academic">
                                <?php if (!empty($app['school'])): ?>
                                    <div class="academic-item">
                                        <span class="aca-label">School</span>
                                        <span><?= htmlspecialchars($app['school']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($app['course'])): ?>
                                    <div class="academic-item">
                                        <span class="aca-label">Course</span>
                                        <span><?= htmlspecialchars($app['course']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($app['gpa'])): ?>
                                    <div class="academic-item">
                                        <span class="aca-label">GPA</span>
                                        <span class="gpa-highlight"><?= htmlspecialchars($app['gpa']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($app['year_level'])): ?>
                                    <div class="academic-item">
                                        <span class="aca-label">Year</span>
                                        <span><?= htmlspecialchars($app['year_level']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Scholarship Info -->
                    <div class="detail-card">
                        <h5 class="detail-section-title"><i class="bi bi-award"></i> Scholarship</h5>
                        <div class="detail-info-grid">
                            <div class="detail-info-item">
                                <span class="di-label">Name</span>
                                <span class="di-value"><?= htmlspecialchars($app['scholarship_name']) ?></span>
                            </div>
                            <div class="detail-info-item">
                                <span class="di-label">Award Amount</span>
                                <span class="di-value amount-highlight">₱<?= number_format($app['amount']) ?></span>
                            </div>
                            <div class="detail-info-item">
                                <span class="di-label">Type</span>
                                <span class="di-value"><?= $app['allows_multiple'] ? 'Multiple Applications Allowed' : 'Exclusive' ?></span>
                            </div>
                        </div>
                        <?php if (!empty($app['scholarship_description'])): ?>
                            <p class="detail-desc"><?= nl2br(htmlspecialchars($app['scholarship_description'])) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Personal Statement -->
                    <div class="detail-card">
                        <h5 class="detail-section-title"><i class="bi bi-chat-quote"></i> Personal Statement</h5>
                        <div class="essay-box">
                            <?= nl2br(htmlspecialchars($app['essay'])) ?>
                        </div>
                    </div>

                    <!-- Documents -->
                    <?php if (!empty($documents)): ?>
                        <div class="detail-card">
                            <h5 class="detail-section-title"><i class="bi bi-paperclip"></i> Submitted Documents</h5>
                            <div class="doc-list">
                                <?php foreach ($documents as $doc): ?>
                                    <a href="<?= APP_URL ?>/uploads/<?= htmlspecialchars($doc['file_path']) ?>"
                                       target="_blank" class="doc-item">
                                        <div class="doc-icon">
                                            <?php $ext = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION)); ?>
                                            <i class="bi bi-<?= $ext === 'pdf' ? 'file-earmark-pdf' : 'file-earmark-image' ?>"></i>
                                        </div>
                                        <div class="doc-info">
                                            <strong><?= ucwords(str_replace('_', ' ', $doc['doc_type'])) ?></strong>
                                            <small><?= htmlspecialchars($doc['original_name']) ?></small>
                                        </div>
                                        <i class="bi bi-download doc-download"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Review / Admin Edit -->
                    <?php if (isset($isEditing) && $isEditing && in_array($auth['role'] ?? '', ['admin', 'reviewer'])): ?>
                        <div class="detail-card decision-card">
                            <h5 class="detail-section-title"><i class="bi bi-pencil-square"></i> Edit Decision</h5>
                                <?php $editAction = ($auth['role'] === 'reviewer')
                                    ? APP_URL . '/reviewer/applications/' . (int)$app['id'] . '/edit'
                                    : APP_URL . '/admin/applications/' . (int)$app['id'] . '/edit'; ?>
                                <form id="decisionForm" method="POST" action="<?= $editAction ?>" onsubmit="return ensureDecisionSelected();">
                                <input type="hidden" name="status" id="decisionStatus" value="">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? $csrf) ?>">

                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <div class="decision-buttons">
                                        <?php
                                        $curStatus = $app['status'] ?? 'pending';
                                        ?>
                                        <button type="button" name="status" value="approved" class="btn-approve" <?= $curStatus === 'approved' ? 'disabled="disabled" aria-disabled="true" tabindex="-1" style="pointer-events:none; opacity:0.65;"' : '' ?> onclick="setDecision('approved'); return false;">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                        <button type="button" name="status" value="rejected" class="btn-reject" <?= $curStatus === 'rejected' ? 'disabled="disabled" aria-disabled="true" tabindex="-1" style="pointer-events:none; opacity:0.65;"' : '' ?> onclick="setDecision('rejected'); return false;">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="form-label">Review Notes</label>
                                    <textarea name="review_notes" class="form-control" rows="4" placeholder="Add notes about your decision..."><?= htmlspecialchars($app['review_notes'] ?? '') ?></textarea>
                                </div>
                                </form>
                        <div class="form-actions">
                            <?php
                            $viewUrl = ($auth['role'] === 'reviewer')
                                ? APP_URL . '/reviewer/applications/' . (int)$app['id'] . '/view'
                                : APP_URL . '/admin/applications/' . (int)$app['id'];
                            ?>
                            <a href="<?= $viewUrl ?>" class="btn-cancel">
                                <i class="bi bi-x-lg"></i> Cancel
                            </a>
                            <button type="submit" form="decisionForm" class="btn-save" onclick="return confirm('Do you want to save changes to this decision?');">
                                <i class="bi bi-check-lg"></i> Save Changes
                            </button>
                        </div>

                        </div>
                        <?php else: ?>
                            <?php if (!empty($app['review_notes'])): ?>
                                <div class="detail-card">
                                    <h5 class="detail-section-title"><i class="bi bi-chat-left-text"></i> Reviewer Notes</h5>
                                    <div class="review-notes-box">
                                        <?= nl2br(htmlspecialchars($app['review_notes'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (($auth['role'] ?? '') === 'student' && in_array($app['status'], ['pending', 'rejected'])): ?>
                                <div class="detail-card" style="display:flex; gap:1rem; flex-wrap:wrap;">
                                    <a href="<?= APP_URL ?>/applications/<?= $app['id'] ?>/edit" class="btn-save">
                                        <i class="bi bi-pencil-fill"></i> Edit & Resubmit
                                    </a>
                                    <form method="POST"
                                        action="<?= APP_URL ?>/applications/<?= $app['id'] ?>/unsubmit"
                                        data-confirm="Are you sure you want to delete this application? This cannot be undone.">
                                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf ?? $_SESSION['csrf_token'] ?? '') ?>">
                                        <button type="submit" class="btn-reject">
                                            <i class="bi bi-trash-fill"></i> Delete Application
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                </div>

                <!-- Sidebar Meta -->
                <div class="detail-sidebar">
                    <div class="detail-meta-card">
                        <h5>Application Info</h5>
                        <div class="meta-item">
                            <span class="meta-label">Reference No.</span>
                            <span class="meta-value mono">#<?= str_pad($app['id'], 6, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Applied On</span>
                            <span class="meta-value"><?= date('M j, Y', strtotime($app['created_at'])) ?></span>
                        </div>
                        <?php if (!empty($app['updated_at'])): ?>
                            <div class="meta-item">
                                <span class="meta-label">Resubmitted</span>
                                <span class="meta-value"><?= date('M j, Y', strtotime($app['updated_at'])) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($auth['role'] !== 'student'): ?>
                            <div class="meta-item">
                                <span class="meta-label">Applicant</span>
                                <span class="meta-value"><?= htmlspecialchars($app['applicant_name']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Email</span>
                                <span class="meta-value"><?= htmlspecialchars($app['applicant_email']) ?></span>
                            </div>
                            <?php if (!empty($app['course'])): ?>
                                <div class="meta-item">
                                    <span class="meta-label">Course</span>
                                    <span class="meta-value"><?= htmlspecialchars($app['course']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($app['gpa'])): ?>
                                <div class="meta-item">
                                    <span class="meta-label">GPA</span>
                                    <span class="meta-value"><?= htmlspecialchars($app['gpa']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($app['reviewer_name']): ?>
                            <div class="meta-item">
                                <span class="meta-label">Reviewed By</span>
                                <span class="meta-value"><?= htmlspecialchars($app['reviewer_name']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Reviewed On</span>
                                <span class="meta-value"><?= date('M j, Y', strtotime($app['reviewed_at'])) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>

<script>
  function setDecision(decision) {
    const el = document.getElementById('decisionStatus');
    if (!el) return;

    el.value = decision;

    const btnReject = document.querySelector('.btn-reject');
    const btnApprove = document.querySelector('.btn-approve');

    // Always re-enable both first (interchangeable behavior)
    if (btnReject) {
      btnReject.disabled = false;
      btnReject.style.pointerEvents = '';
      btnReject.style.opacity = '';
      btnReject.setAttribute('aria-disabled', 'false');
      btnReject.style.filter = '';
    }
    if (btnApprove) {
      btnApprove.disabled = false;
      btnApprove.style.pointerEvents = '';
      btnApprove.style.opacity = '';
      btnApprove.setAttribute('aria-disabled', 'false');
      btnApprove.style.filter = '';
    }

    // Lock only the selected decision button
    if (decision === 'rejected') {
      if (btnReject) {
        btnReject.disabled = true;
        btnReject.setAttribute('aria-disabled', 'true');
        btnReject.style.pointerEvents = 'none';
        btnReject.style.opacity = '0.65';
      }
    }

    if (decision === 'approved') {
      if (btnApprove) {
        btnApprove.disabled = true;
        btnApprove.setAttribute('aria-disabled', 'true');
        btnApprove.style.pointerEvents = 'none';
        btnApprove.style.opacity = '0.65';
      }
    }
  }

  function ensureDecisionSelected() {
    const el = document.getElementById('decisionStatus');
    if (!el) return true;
    if (!el.value || el.value.length === 0) {
      alert('Please select Approve or Reject before saving.');
      return false;
    }
    return true;
  }

  // If editing an already decided application, set UI lock consistently.
  window.addEventListener('load', () => {
    const statusEl = document.getElementById('decisionStatus');
    const formCard = document.querySelector('.decision-card');
    if (!statusEl || !formCard) return;

    // The server disables the selected button already; we still set hidden field so submission validation passes.
    const approvedDisabled = document.querySelector('.btn-approve')?.disabled;
    const rejectedDisabled = document.querySelector('.btn-reject')?.disabled;

    // Only set if server indicates one is disabled due to current status.
    if (!statusEl.value && approvedDisabled) setDecision('approved');
    if (!statusEl.value && rejectedDisabled) setDecision('rejected');
  });
</script>

<?php if ($isEditing): ?>
<script>
  window.addEventListener('load', function () {
    const el = document.querySelector('.decision-card');
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
</script>
<?php endif; ?>
