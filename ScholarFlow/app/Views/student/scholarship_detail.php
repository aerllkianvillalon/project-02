<?php
/**
 * Scholarship Detail — Student
 *
 * @var array $auth         Current user
 * @var array $scholarship  Scholarship row (id, name, description, requirements,
 *                          amount, deadline, allows_multiple, slots, status)
 * @var array $check        ['available' => bool, 'reason' => string, 'scholarship' => array]
 */
$pageTitle = htmlspecialchars($scholarship['name']);
$bodyClass  = 'app-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Scholarship Details</h2>
                <span>
                    <a href="<?= APP_URL ?>/scholarships">Scholarships</a>
                    / <?= htmlspecialchars($scholarship['name']) ?>
                </span>
            </div>
        </header>

        <main class="app-content">
            <a href="<?= APP_URL ?>/scholarships" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Scholarships
            </a>

            <div class="schol-detail-layout">
                <!-- Main Info -->
                <div class="schol-detail-main">
                    <div class="schol-detail-hero">
                        <div class="schol-detail-badge
                                    <?= $scholarship['allows_multiple'] ? 'type-open' : 'type-exclusive' ?>">
                            <i class="bi bi-<?= $scholarship['allows_multiple'] ? 'infinity' : 'star-fill' ?>"></i>
                            <?= $scholarship['allows_multiple']
                                    ? 'Multiple Applications Allowed'
                                    : 'Exclusive Scholarship' ?>
                        </div>
                        <h2><?= htmlspecialchars($scholarship['name']) ?></h2>
                        <div class="schol-detail-amount">
                            <span class="amount-label">Award Amount</span>
                            <span class="amount-big">₱<?= number_format($scholarship['amount']) ?></span>
                        </div>
                    </div>

                    <div class="detail-card">
                        <h5 class="detail-section-title">
                            <i class="bi bi-info-circle"></i> About this Scholarship
                        </h5>
                        <p><?= nl2br(htmlspecialchars($scholarship['description'])) ?></p>
                    </div>

                    <?php if (!empty($scholarship['requirements'])): ?>
                        <div class="detail-card">
                            <h5 class="detail-section-title">
                                <i class="bi bi-list-check"></i> Requirements
                            </h5>
                            <p><?= nl2br(htmlspecialchars($scholarship['requirements'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="detail-card">
                        <h5 class="detail-section-title">
                            <i class="bi bi-paperclip"></i> Required Documents
                        </h5>
                        <ul class="doc-req-list">
                            <li><i class="bi bi-check-circle-fill"></i> Transcript of Records</li>
                            <li><i class="bi bi-check-circle-fill"></i> COE / COR</li>
                            <li><i class="bi bi-check-circle-fill"></i> Good Moral Certificate</li>
                            <li><i class="bi bi-check-circle-fill"></i> Valid Government ID</li>
                            <li><i class="bi bi-dash-circle"></i> Recommendation Letter (optional)</li>
                            <li><i class="bi bi-dash-circle"></i> Other Supporting Documents (optional)</li>
                        </ul>
                    </div>
                </div>

                <!-- Apply Sidebar -->
                <div class="schol-detail-sidebar">
                    <div class="apply-action-card">
                        <?php if ($check['available']): ?>
                            <div class="apply-action-status available">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>You are eligible to apply</span>
                            </div>
                            <a href="<?= APP_URL ?>/apply/<?= $scholarship['id'] ?>"
                               class="btn-apply-full">
                                <i class="bi bi-send-fill"></i> Apply Now
                            </a>
                        <?php else: ?>
                            <div class="apply-action-status unavailable">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span><?= htmlspecialchars($check['reason']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="apply-meta">
                            <div class="apply-meta-item">
                                <i class="bi bi-clock"></i>
                                <span>
                                    Deadline:
                                    <strong><?= date('F j, Y', strtotime($scholarship['deadline'])) ?></strong>
                                </span>
                            </div>
                            <?php if (!empty($scholarship['slots'])): ?>
                                <div class="apply-meta-item">
                                    <i class="bi bi-people"></i>
                                    <span>
                                        <strong><?= $scholarship['slots'] ?></strong> slots available
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>