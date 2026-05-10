/**
 * ScholarFlow — Main JavaScript
 * Sidebar toggle, auto-dismiss toasts, general UX enhancements
 */

document.addEventListener('DOMContentLoaded', () => {

  // ── Sidebar toggle (mobile) ──────────────────────────────
  const sidebar      = document.getElementById('sidebar');
  const openBtn      = document.getElementById('sidebarOpen');
  const closeBtn     = document.getElementById('sidebarClose');

  openBtn?.addEventListener('click', () => sidebar?.classList.add('open'));
  closeBtn?.addEventListener('click', () => sidebar?.classList.remove('open'));

  // Close sidebar when clicking outside (overlay)
  document.addEventListener('click', (e) => {
    if (sidebar?.classList.contains('open') &&
        !sidebar.contains(e.target) &&
        e.target !== openBtn) {
      sidebar.classList.remove('open');
    }
  });

  // ── Auto-dismiss flash toasts ────────────────────────────
  document.querySelectorAll('.alert-toast').forEach(toast => {
    setTimeout(() => {
      toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-8px)';
      setTimeout(() => toast.remove(), 400);
    }, 5000);
  });

  // ── Active nav link highlight ────────────────────────────
  const currentPath = window.location.pathname;
  document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (!href) return;
    // Strip APP_URL base from href for comparison
    const linkPath = new URL(href, window.location.origin).pathname;
    if (linkPath === currentPath ||
        (linkPath.length > 1 && currentPath.startsWith(linkPath))) {
      link.classList.add('active');
    }
  });

  // ── Confirm delete forms ─────────────────────────────────
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', (e) => {
      if (!confirm(form.dataset.confirm || 'Are you sure?')) {
        e.preventDefault();
      }
    });
  });

  // ── Form validation UI ───────────────────────────────────
  document.querySelectorAll('form[novalidate]').forEach(form => {
    form.addEventListener('submit', (e) => {
      let valid = true;
      form.querySelectorAll('[required]').forEach(input => {
        if (!input.value.trim()) {
          input.style.borderColor = 'var(--danger)';
          valid = false;
        } else {
          input.style.borderColor = '';
        }
      });
      if (!valid) {
        e.preventDefault();
        // Scroll to first invalid field
        const first = form.querySelector('[required]:invalid, [required][style*="danger"]');
        first?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });

  // ── Input error state reset ──────────────────────────────
  document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', () => {
      if (input.value.trim()) {
        input.style.borderColor = '';
      }
    });
  });

  // ── Topbar title from sidebar active link ────────────────
  // (already handled server-side via $pageTitle)

  // ── Number formatting in stat cards ──────────────────────
  document.querySelectorAll('.stat-num[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count, 10);
    let current = 0;
    const step = Math.ceil(target / 30);
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = current;
      if (current >= target) clearInterval(timer);
    }, 30);
  });

  // ── Smooth table row hover (already CSS, but add keyboard nav) ──
  document.querySelectorAll('.sf-table tbody tr[data-href]').forEach(row => {
    row.style.cursor = 'pointer';
    row.addEventListener('click', () => {
      window.location.href = row.dataset.href;
    });
  });

});