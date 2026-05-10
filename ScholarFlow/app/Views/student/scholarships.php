<?php
/**
 * Scholarship Listing — Student
 *
 * @var array   $auth          Current user
 * @var array[] $scholarships  Each row has keys: id, name, description, amount, deadline,
 *                             allows_multiple, status, already_applied, locked
 * @var array   $flash         Flash messages
 */
$pageTitle = 'Scholarships';
$bodyClass  = 'app-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Scholarships</h2>
                <span>Find and apply for scholarships</span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput"
                           placeholder="Search scholarships…"
                           class="search-input">
                </div>
                <div class="filter-badges">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="exclusive">Exclusive</button>
                    <button class="filter-btn" data-filter="open">Open</button>
                </div>
            </div>

            <!-- Scholarship Grid -->
            <div class="scholarship-grid" id="scholarshipGrid">
                <?php if (empty($scholarships)): ?>
                    <div class="empty-state full-width">
                        <i class="bi bi-trophy"></i>
                        <p>No scholarships available at this time.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($scholarships as $s): ?>
                        <div class="scholarship-card
                                    <?= $s['already_applied'] ? 'applied'  : '' ?>
                                    <?= $s['locked']          ? 'locked'   : '' ?>"
                             data-name="<?= htmlspecialchars(strtolower($s['name'])) ?>"
                             data-type="<?= $s['allows_multiple'] ? 'open' : 'exclusive' ?>">

                            <div class="schol-card-header">
                                <div class="schol-type-badge
                                            <?= $s['allows_multiple'] ? 'type-open' : 'type-exclusive' ?>">
                                    <i class="bi bi-<?= $s['allows_multiple'] ? 'infinity' : 'star-fill' ?>"></i>
                                    <?= $s['allows_multiple'] ? 'Multiple Allowed' : 'Exclusive' ?>
                                </div>
                                <div class="schol-amount-tag">
                                    ₱<?= number_format($s['amount']) ?>
                                </div>
                            </div>

                            <div class="schol-card-body">
                                <h4><?= htmlspecialchars($s['name']) ?></h4>
                                <p><?= htmlspecialchars(substr($s['description'], 0, 120)) ?>…</p>

                                <?php if (!empty($s['requirements'])): ?>
                                    <div class="schol-requirements">
                                        <strong><i class="bi bi-list-check"></i> Requirements:</strong>
                                        <p><?= htmlspecialchars(substr($s['requirements'], 0, 100)) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="schol-card-footer">
                                <span class="deadline-badge">
                                    <i class="bi bi-clock"></i>
                                    Deadline: <?= date('M j, Y', strtotime($s['deadline'])) ?>
                                </span>

                                <?php if ($s['already_applied']): ?>
                                    <span class="btn-applied">
                                        <i class="bi bi-check2"></i> Applied
                                    </span>
                                <?php elseif ($s['locked']): ?>
                                    <span class="btn-locked"
                                          title="You have an approved exclusive scholarship">
                                        <i class="bi bi-lock-fill"></i> Locked
                                    </span>
                                <?php else: ?>
                                    <a href="<?= APP_URL ?>/scholarships/<?= $s['id'] ?>"
                                       class="btn-view">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div><!-- /.scholarship-grid -->
        </main>
    </div>
</div>

<script>
const searchInput  = document.getElementById('searchInput');
const cards        = document.querySelectorAll('.scholarship-card');
const filterBtns   = document.querySelectorAll('.filter-btn');
let   activeFilter = 'all';

function filterCards() {
    const q = searchInput.value.toLowerCase();
    cards.forEach(card => {
        const matchSearch = card.dataset.name.includes(q);
        const matchFilter = activeFilter === 'all' || card.dataset.type === activeFilter;
        card.style.display = matchSearch && matchFilter ? '' : 'none';
    });
}

searchInput.addEventListener('input', filterCards);
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.dataset.filter;
        filterCards();
    });
});
</script>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>