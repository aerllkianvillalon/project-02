<?php $pageTitle = 'Application #' . $app['id']; $bodyClass = 'app-body'; ?>
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
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <!-- Back link -->
            <a href="javascript:history.back()" class="back-link">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <div class="detail-layout">
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

                    <!-- Review Notes -->
                    <?php if (!empty($app['review_notes'])): ?>
                        <div class="detail-card">
                            <h5 class="detail-section-title"><i class="bi bi-chat-left-text"></i> Reviewer Notes</h5>
                            <div class="review-notes-box">
                                <?= nl2br(htmlspecialchars($app['review_notes'])) ?>
                            </div>
                        </div>
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