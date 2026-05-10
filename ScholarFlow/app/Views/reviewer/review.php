<?php
/**
 * Reviewer — Review & Decide on an Application
 *
 * @var array   $auth       Current user (reviewer)
 * @var array   $app        Application row with joined scholarship + applicant + reviewer data
 * @var array[] $documents  Uploaded documents for this application
 * @var string  $csrf       CSRF token
 * @var array   $flash      Flash messages
 */
$pageTitle = 'Review Application';
$bodyClass  = 'app-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Review Application</h2>
                <span>Application #<?= str_pad($app['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <a href="<?= APP_URL ?>/reviewer/applications" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Applications
            </a>

            <div class="detail-layout">
                <!-- ── Main content ──────────────────────────────── -->
                <div class="detail-main">

                    <!-- Applicant Profile Card -->
                    <div class="detail-card reviewer-applicant-card">
                        <div class="applicant-profile">
                            <div class="applicant-avatar-lg">
                                <?= strtoupper(substr($app['applicant_name'], 0, 2)) ?>
                            </div>

                            <div class="applicant-profile-info">
                                <h4><?= htmlspecialchars($app['applicant_name']) ?></h4>
                                <p><?= htmlspecialchars($app['applicant_email']) ?></p>
                                <?php if (!empty($app['phone'])): ?>
                                    <p>
                                        <i class="bi bi-telephone"></i>
                                        <?= htmlspecialchars($app['phone']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($app['address'])): ?>
                                    <p>
                                        <i class="bi bi-geo-alt"></i>
                                        <?= htmlspecialchars($app['address']) ?>
                                    </p>
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
                                        <span class="gpa-highlight">
                                            <?= htmlspecialchars($app['gpa']) ?>
                                        </span>
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

                    <!-- Scholarship Applied For -->
                    <div class="detail-card">
                        <h5 class="detail-section-title">
                            <i class="bi bi-award"></i> Applied For
                        </h5>
                        <div class="detail-info-grid">
                            <div class="detail-info-item">
                                <span class="di-label">Scholarship</span>
                                <span class="di-value">
                                    <?= htmlspecialchars($app['scholarship_name']) ?>
                                </span>
                            </div>
                            <div class="detail-info-item">
                                <span class="di-label">Amount</span>
                                <span class="di-value amount-highlight">
                                    ₱<?= number_format($app['amount']) ?>
                                </span>
                            </div>
                            <div class="detail-info-item">
                                <span class="di-label">Type</span>
                                <span class="di-value">
                                    <?= $app['allows_multiple']
                                        ? 'Open (Multiple Allowed)'
                                        : 'Exclusive' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Statement -->
                    <div class="detail-card">
                        <h5 class="detail-section-title">
                            <i class="bi bi-chat-quote"></i> Personal Statement
                        </h5>
                        <div class="essay-box">
                            <?= nl2br(htmlspecialchars($app['essay'])) ?>
                        </div>
                    </div>

                    <!-- Documents -->
                    <?php if (!empty($documents)): ?>
                        <div class="detail-card">
                            <h5 class="detail-section-title">
                                <i class="bi bi-paperclip"></i> Submitted Documents
                            </h5>
                            <div class="doc-list">
                                <?php foreach ($documents as $doc): ?>
                                    <?php
                                    $ext = strtolower(
                                        pathinfo($doc['file_path'], PATHINFO_EXTENSION)
                                    );
                                    ?>
                                    <a href="<?= APP_URL ?>/uploads/<?= htmlspecialchars($doc['file_path']) ?>"
                                       target="_blank"
                                       class="doc-item">
                                        <div class="doc-icon">
                                            <i class="bi bi-<?= $ext === 'pdf'
                                                ? 'file-earmark-pdf'
                                                : 'file-earmark-image' ?>"></i>
                                        </div>
                                        <div class="doc-info">
                                            <strong>
                                                <?= ucwords(str_replace('_', ' ', $doc['doc_type'])) ?>
                                            </strong>
                                            <small>
                                                <?= htmlspecialchars($doc['original_name']) ?>
                                            </small>
                                        </div>
                                        <i class="bi bi-box-arrow-up-right doc-download"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Decision Form (pending only) -->
                    <?php if ($app['status'] === 'pending'): ?>
                        <div class="detail-card decision-card">
                            <h5 class="detail-section-title">
                                <i class="bi bi-gavel"></i> Make Decision
                            </h5>
                            <form method="POST"
                                  action="<?= APP_URL ?>/reviewer/applications/<?= $app['id'] ?>">
                                <input type="hidden" name="_token" value="<?= $csrf ?>">

                                <div class="form-group">
                                    <label class="form-label">Review Notes</label>
                                    <textarea name="review_notes"
                                              class="form-control"
                                              rows="4"
                                              placeholder="Add notes about your decision (visible to the applicant)…"></textarea>
                                </div>

                                <div class="decision-buttons">
                                    <button type="submit"
                                            name="status"
                                            value="rejected"
                                            class="btn-reject"
                                            onclick="return confirm('Reject this application?')">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                    <button type="submit"
                                            name="status"
                                            value="approved"
                                            class="btn-approve"
                                            onclick="return confirm('Approve this application?')">
                                        <i class="bi bi-check-circle"></i> Approve
                                    </button>
                                </div>
                            </form>
                        </div>

                    <?php else: ?>
                        <!-- Already decided -->
                        <div class="detail-card">
                            <div class="already-decided status-banner-<?= $app['status'] ?>">
                                <i class="bi bi-<?= $app['status'] === 'approved'
                                    ? 'patch-check-fill'
                                    : 'x-circle-fill' ?>"></i>
                                This application was
                                <strong><?= $app['status'] ?></strong>
                                <?php if (!empty($app['reviewer_name'])): ?>
                                    by <?= htmlspecialchars($app['reviewer_name']) ?>
                                <?php endif; ?>
                                on <?= date('F j, Y', strtotime($app['reviewed_at'] ?? 'now')) ?>.
                            </div>
                            <?php if (!empty($app['review_notes'])): ?>
                                <div class="review-notes-box mt-3">
                                    <?= nl2br(htmlspecialchars($app['review_notes'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ── Meta Sidebar ──────────────────────────────── -->
                <div class="detail-sidebar">
                    <div class="detail-meta-card">
                        <h5>Application Info</h5>

                        <div class="meta-item">
                            <span class="meta-label">Reference</span>
                            <span class="meta-value mono">
                                #<?= str_pad($app['id'], 6, '0', STR_PAD_LEFT) ?>
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="status-badge status-<?= $app['status'] ?>">
                                <span class="status-dot"></span>
                                <?= ucfirst($app['status']) ?>
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Applied</span>
                            <span class="meta-value">
                                <?= date('M j, Y g:i A', strtotime($app['created_at'])) ?>
                            </span>
                        </div>
                        <?php if (!empty($app['reviewed_at'])): ?>
                            <div class="meta-item">
                                <span class="meta-label">Reviewed</span>
                                <span class="meta-value">
                                    <?= date('M j, Y', strtotime($app['reviewed_at'])) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="meta-item">
                            <span class="meta-label">Documents</span>
                            <span class="meta-value"><?= count($documents) ?> file(s)</span>
                        </div>
                    </div>
                </div>
            </div><!-- /.detail-layout -->
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>