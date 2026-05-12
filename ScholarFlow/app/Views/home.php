<?php
$pageTitle = 'Welcome';
$bodyClass = 'landing-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScholarFlow — Simplifying Scholarship Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* ── Design tokens (mirrors app.css) ── */
        :root {
            --primary:        #5b21b6;
            --primary-light:  #7c3aed;
            --primary-dark:   #4c1d95;
            --primary-soft:   #ede9fe;
            --accent:         #06b6d4;
            --accent-light:   #cffafe;
            --gray-50:   #f8fafc;
            --gray-100:  #f1f5f9;
            --gray-200:  #e2e8f0;
            --gray-300:  #cbd5e1;
            --gray-400:  #94a3b8;
            --gray-500:  #64748b;
            --gray-600:  #475569;
            --gray-700:  #334155;
            --gray-800:  #1e293b;
            --gray-900:  #0f172a;
            --success:      #059669;
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,.06);
            --shadow-md: 0 4px 16px rgba(0,0,0,.08);
            --shadow-lg: 0 8px 32px rgba(0,0,0,.12);
            --font-display: 'Syne', system-ui, sans-serif;
            --font-body:    'DM Sans', system-ui, sans-serif;
            --transition: 0.2s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            color: var(--gray-700);
            background: #fff;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        button { cursor: pointer; border: none; background: none; font-family: inherit; }

        /* ══════════════════════════════════
           NAVBAR
        ══════════════════════════════════ */
        .lp-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 0 2rem;
            height: 68px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226,232,240,0.6);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }
        .lp-nav.scrolled {
            background: rgba(255,255,255,0.97);
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 0.625rem;
            font-family: var(--font-display); font-size: 1.375rem; font-weight: 700;
            color: var(--gray-900); letter-spacing: -0.03em;
        }
        .nav-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1rem;
            box-shadow: 0 4px 12px rgba(91,33,182,0.3);
        }
        .nav-brand strong { color: var(--primary-light); }

        .nav-links {
            display: flex; align-items: center; gap: 0.25rem;
            list-style: none;
        }
        .nav-links a {
            padding: 0.4rem 0.875rem;
            font-size: 0.9rem; font-weight: 500;
            color: var(--gray-600);
            border-radius: var(--radius-md);
            transition: color var(--transition), background var(--transition);
        }
        .nav-links a:hover { color: var(--primary-light); background: var(--primary-soft); }

        .nav-actions { display: flex; align-items: center; gap: 0.625rem; }
        .btn-nav-login {
            padding: 0.5rem 1.125rem;
            font-size: 0.875rem; font-weight: 600;
            color: var(--gray-700);
            border-radius: var(--radius-md);
            transition: color var(--transition), background var(--transition);
        }
        .btn-nav-login:hover { color: var(--primary-light); background: var(--primary-soft); }
        .btn-nav-register {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5rem 1.125rem;
            font-size: 0.875rem; font-weight: 600;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: #fff; border-radius: var(--radius-md);
            transition: all var(--transition);
            box-shadow: 0 4px 12px rgba(91,33,182,0.3);
        }
        .btn-nav-register:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(91,33,182,0.38); }

        .nav-hamburger {
            display: none; padding: 0.5rem;
            color: var(--gray-700); font-size: 1.375rem;
            border-radius: var(--radius-md);
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .btn-nav-login { display: none; }
            .nav-hamburger { display: flex; }
        }

        /* ══════════════════════════════════
           HERO
        ══════════════════════════════════ */
        .hero {
            padding-top: 68px;
            min-height: 100vh;
            display: flex; align-items: center;
            background: linear-gradient(160deg, #fff 0%, #f5f3ff 50%, #e0f2fe 100%);
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 60% 60% at 70% 40%, rgba(124,58,237,0.08) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 30% 70%, rgba(6,182,212,0.07) 0%, transparent 70%);
            pointer-events: none;
        }
        /* Decorative grid dots */
        .hero::after {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(91,33,182,0.08) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none; opacity: 0.5;
            mask-image: radial-gradient(ellipse 80% 80% at 60% 40%, black 0%, transparent 70%);
        }

        .hero-inner {
            max-width: 1200px; margin: 0 auto; padding: 5rem 2rem;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 4rem; align-items: center;
            position: relative; z-index: 1;
            width: 100%;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.375rem 0.875rem;
            background: var(--primary-soft); color: var(--primary-light);
            border-radius: var(--radius-full);
            font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
            border: 1px solid rgba(124,58,237,0.2);
        }
        .hero-badge i { font-size: 0.75rem; }

        .hero-title {
            font-family: var(--font-display);
            font-size: clamp(2.2rem, 4vw, 3.25rem);
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1.12;
            letter-spacing: -0.04em;
            margin-bottom: 1.375rem;
        }
        .hero-title .highlight {
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .hero-sub {
            font-size: 1.0625rem;
            color: var(--gray-500);
            line-height: 1.75;
            margin-bottom: 2.25rem;
            max-width: 500px;
        }

        .hero-actions { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .btn-hero-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.875rem 1.875rem;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: #fff; border-radius: var(--radius-md);
            font-size: 1rem; font-weight: 700; font-family: var(--font-display);
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(91,33,182,0.35);
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(91,33,182,0.45); }

        .btn-hero-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            color: var(--gray-700); border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md); font-size: 1rem; font-weight: 600;
            transition: all var(--transition); background: rgba(255,255,255,0.8);
        }
        .btn-hero-secondary:hover { border-color: var(--primary-light); color: var(--primary-light); background: #fff; }

        .hero-trust {
            display: flex; align-items: center; gap: 0.875rem;
            margin-top: 2rem;
            font-size: 0.8125rem; color: var(--gray-400);
        }
        .trust-avatars { display: flex; }
        .trust-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            border: 2px solid #fff;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 700; color: #fff;
            margin-left: -8px;
        }
        .trust-avatar:first-child { margin-left: 0; }

        /* ── Dashboard Mockup ── */
        .hero-visual { position: relative; }
        .dashboard-mockup {
            background: #fff;
            border-radius: var(--radius-xl);
            box-shadow: 0 24px 80px rgba(91,33,182,0.18), 0 4px 16px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid var(--gray-200);
            transform: perspective(1200px) rotateY(-6deg) rotateX(2deg);
            transition: transform 0.5s ease;
        }
        .dashboard-mockup:hover { transform: perspective(1200px) rotateY(-3deg) rotateX(1deg); }
        .mock-topbar {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            padding: 0.875rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .mock-topbar-brand {
            font-family: var(--font-display); font-size: 0.9rem; font-weight: 700;
            color: #fff; display: flex; align-items: center; gap: 0.5rem;
        }
        .mock-topbar-brand i { opacity: 0.85; }
        .mock-dots { display: flex; gap: 0.375rem; }
        .mock-dot { width: 10px; height: 10px; border-radius: 50%; opacity: 0.7; }
        .mock-dot:nth-child(1) { background: #ff5f57; }
        .mock-dot:nth-child(2) { background: #febc2e; }
        .mock-dot:nth-child(3) { background: #28c840; }

        .mock-body { display: grid; grid-template-columns: 140px 1fr; min-height: 320px; }
        .mock-sidebar-strip {
            background: var(--gray-900);
            padding: 1rem 0.75rem;
            display: flex; flex-direction: column; gap: 0.25rem;
        }
        .mock-nav-item {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 0.625rem;
            border-radius: 8px;
            font-size: 0.72rem; font-weight: 500; color: rgba(255,255,255,0.5);
            transition: all var(--transition);
        }
        .mock-nav-item.active { background: rgba(124,58,237,0.35); color: #fff; }
        .mock-nav-item i { font-size: 0.85rem; }

        .mock-content { padding: 1rem; background: var(--gray-50); }
        .mock-welcome {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            border-radius: 10px; padding: 0.875rem 1rem;
            color: #fff; margin-bottom: 0.875rem;
        }
        .mock-welcome p { font-size: 0.7rem; opacity: 0.7; }
        .mock-welcome strong { font-family: var(--font-display); font-size: 0.875rem; font-weight: 700; }

        .mock-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.875rem; }
        .mock-stat {
            background: #fff; border-radius: 8px; padding: 0.625rem;
            border: 1px solid var(--gray-100);
        }
        .mock-stat-num {
            font-family: var(--font-display); font-size: 1.1rem; font-weight: 800;
            color: var(--primary-light);
        }
        .mock-stat-lbl { font-size: 0.6rem; color: var(--gray-400); font-weight: 500; }

        .mock-card {
            background: #fff; border-radius: 8px; padding: 0.625rem;
            border: 1px solid var(--gray-100);
        }
        .mock-card-title { font-size: 0.65rem; font-weight: 700; color: var(--gray-600); margin-bottom: 0.5rem; }
        .mock-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.375rem 0; border-bottom: 1px solid var(--gray-100);
            font-size: 0.6rem;
        }
        .mock-row:last-child { border-bottom: none; }
        .mock-badge {
            padding: 0.15rem 0.5rem; border-radius: 99px;
            font-size: 0.55rem; font-weight: 700;
        }
        .mock-badge.pending  { background: #fef3c7; color: #b45309; }
        .mock-badge.approved { background: #d1fae5; color: #065f46; }

        /* Floating accents */
        .hero-float-1, .hero-float-2 {
            position: absolute;
            border-radius: var(--radius-lg);
            padding: 0.75rem 1rem;
            display: flex; align-items: center; gap: 0.625rem;
            font-size: 0.8rem; font-weight: 600;
            box-shadow: var(--shadow-lg);
            animation: floatBob 4s ease-in-out infinite;
        }
        .hero-float-1 {
            bottom: -16px; left: -24px;
            background: #fff; color: var(--success);
            border: 1px solid var(--gray-100);
        }
        .hero-float-2 {
            top: -16px; right: -16px;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            color: #fff;
            animation-delay: 1.5s;
        }
        @keyframes floatBob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; text-align: center; gap: 3rem; }
            .hero-sub { margin-left: auto; margin-right: auto; }
            .hero-actions { justify-content: center; }
            .hero-trust { justify-content: center; }
            .dashboard-mockup { transform: none; max-width: 480px; margin: 0 auto; }
            .dashboard-mockup:hover { transform: none; }
            .hero-float-1 { left: -8px; }
            .hero-float-2 { right: -8px; }
        }

        /* ══════════════════════════════════
           SECTION COMMONS
        ══════════════════════════════════ */
        .section { padding: 5rem 2rem; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-eyebrow {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-size: 0.775rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
            color: var(--primary-light); margin-bottom: 0.875rem;
        }
        .section-eyebrow::before {
            content: '';
            width: 20px; height: 2px;
            background: linear-gradient(90deg, var(--primary-light), var(--accent));
            border-radius: 2px;
        }
        .section-title {
            font-family: var(--font-display);
            font-size: clamp(1.625rem, 3vw, 2.25rem);
            font-weight: 800; letter-spacing: -0.035em;
            color: var(--gray-900); margin-bottom: 1rem;
            line-height: 1.18;
        }
        .section-sub {
            font-size: 1.0625rem; color: var(--gray-500);
            max-width: 560px; line-height: 1.7;
        }
        .section-header { margin-bottom: 3.5rem; }
        .section-header.centered { text-align: center; }
        .section-header.centered .section-sub { margin: 0 auto; }
        .section-header.centered .section-eyebrow { justify-content: center; }

        /* ══════════════════════════════════
           FEATURES
        ══════════════════════════════════ */
        .features { background: var(--gray-50); }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        .feature-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 2rem;
            border: 1px solid var(--gray-100);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            position: relative; overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--primary-light), var(--accent));
            opacity: 0; transition: opacity 0.25s ease;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: var(--gray-200); }
        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 52px; height: 52px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.375rem; margin-bottom: 1.25rem;
        }
        .fi-purple { background: var(--primary-soft); color: var(--primary-light); }
        .fi-cyan   { background: #e0f2fe; color: #0284c7; }
        .fi-green  { background: #d1fae5; color: #059669; }
        .fi-orange { background: #fef3c7; color: #d97706; }
        .fi-pink   { background: #fce7f3; color: #be185d; }

        .feature-title {
            font-family: var(--font-display);
            font-size: 1.0625rem; font-weight: 700;
            color: var(--gray-900); margin-bottom: 0.625rem; letter-spacing: -0.02em;
        }
        .feature-desc { font-size: 0.9rem; color: var(--gray-500); line-height: 1.7; }

        @media (max-width: 900px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 580px) { .features-grid { grid-template-columns: 1fr; } }

        /* ══════════════════════════════════
           HOW IT WORKS
        ══════════════════════════════════ */
        .how-it-works { background: #fff; }
        .steps-row {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 0; position: relative;
        }
        .steps-row::before {
            content: '';
            position: absolute; top: 40px; left: calc(16.67% + 40px); right: calc(16.67% + 40px);
            height: 2px;
            background: linear-gradient(90deg, var(--primary-light), var(--accent));
            z-index: 0;
        }
        .step-item { text-align: center; padding: 0 2rem; position: relative; z-index: 1; }
        .step-num {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: #fff; font-family: var(--font-display); font-size: 1.5rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(91,33,182,0.3);
            position: relative;
        }
        .step-num::after {
            content: '';
            position: absolute; inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(124,58,237,0.2);
        }
        .step-icon {
            position: absolute; bottom: -4px; right: -4px;
            width: 28px; height: 28px;
            background: var(--accent); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; color: #fff;
            border: 2px solid #fff;
        }
        .step-title {
            font-family: var(--font-display); font-size: 1.125rem; font-weight: 700;
            color: var(--gray-900); margin-bottom: 0.625rem; letter-spacing: -0.02em;
        }
        .step-desc { font-size: 0.9rem; color: var(--gray-500); line-height: 1.7; }

        @media (max-width: 700px) {
            .steps-row { grid-template-columns: 1fr; gap: 2.5rem; }
            .steps-row::before { display: none; }
        }

        /* ══════════════════════════════════
           STATS
        ══════════════════════════════════ */
        .stats-section {
            background: linear-gradient(135deg, var(--gray-900) 0%, #1e1b4b 60%, var(--primary-dark) 100%);
            position: relative; overflow: hidden;
        }
        .stats-section::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .stats-grid-4 {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 1px; background: rgba(255,255,255,0.08);
            border-radius: var(--radius-xl); overflow: hidden;
            position: relative; z-index: 1;
        }
        .stat-block {
            background: rgba(255,255,255,0.05);
            padding: 2.5rem 2rem; text-align: center;
            backdrop-filter: blur(4px);
            transition: background var(--transition);
        }
        .stat-block:hover { background: rgba(255,255,255,0.1); }
        .stat-block-num {
            font-family: var(--font-display);
            font-size: 2.75rem; font-weight: 800; letter-spacing: -0.04em;
            background: linear-gradient(135deg, #fff, rgba(255,255,255,0.7));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            line-height: 1; margin-bottom: 0.5rem;
        }
        .stat-block-icon {
            font-size: 1.375rem; margin-bottom: 0.875rem;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .stat-block-lbl {
            font-size: 0.875rem; color: rgba(255,255,255,0.5);
            font-weight: 500; letter-spacing: 0.02em;
        }
        @media (max-width: 700px) { .stats-grid-4 { grid-template-columns: 1fr 1fr; } }

        /* ══════════════════════════════════
           TESTIMONIALS
        ══════════════════════════════════ */
        .testimonials { background: var(--gray-50); }
        .testimonials-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;
        }
        .testi-card {
            background: #fff; border-radius: var(--radius-lg);
            padding: 1.75rem; border: 1px solid var(--gray-100);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .testi-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
        .testi-stars { color: #f59e0b; margin-bottom: 1rem; font-size: 0.875rem; letter-spacing: 2px; }
        .testi-quote {
            font-size: 0.9375rem; color: var(--gray-600);
            line-height: 1.75; margin-bottom: 1.375rem;
            font-style: italic;
        }
        .testi-author { display: flex; align-items: center; gap: 0.75rem; }
        .testi-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.875rem; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .testi-name { font-size: 0.875rem; font-weight: 700; color: var(--gray-800); }
        .testi-role { font-size: 0.775rem; color: var(--gray-400); }
        @media (max-width: 900px) { .testimonials-grid { grid-template-columns: 1fr; gap: 1rem; } }
        @media (min-width: 580px) and (max-width: 900px) { .testimonials-grid { grid-template-columns: 1fr 1fr; } }

        /* ══════════════════════════════════
           CTA
        ══════════════════════════════════ */
        .cta-section {
            background: #fff; text-align: center;
        }
        .cta-box {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 50%, #0891b2 100%);
            border-radius: var(--radius-xl);
            padding: 5rem 3rem;
            position: relative; overflow: hidden;
        }
        .cta-box::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .cta-box::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.12), transparent 70%);
            top: -100px; right: -100px; border-radius: 50%;
        }
        .cta-content { position: relative; z-index: 1; }
        .cta-title {
            font-family: var(--font-display);
            font-size: clamp(1.75rem, 3.5vw, 2.75rem);
            font-weight: 800; letter-spacing: -0.04em;
            color: #fff; margin-bottom: 1rem; line-height: 1.15;
        }
        .cta-sub { font-size: 1.0625rem; color: rgba(255,255,255,0.72); margin-bottom: 2.5rem; }
        .cta-actions { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; }
        .btn-cta-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.9375rem 2.25rem;
            background: #fff; color: var(--primary);
            border-radius: var(--radius-md);
            font-size: 1rem; font-weight: 700; font-family: var(--font-display);
            transition: all 0.25s ease;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }
        .btn-cta-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,0.28); }
        .btn-cta-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.9375rem 2.25rem;
            border: 1.5px solid rgba(255,255,255,0.45);
            color: #fff; border-radius: var(--radius-md);
            font-size: 1rem; font-weight: 600;
            transition: all var(--transition);
            backdrop-filter: blur(4px);
        }
        .btn-cta-secondary:hover { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.7); }

        /* ══════════════════════════════════
           FOOTER
        ══════════════════════════════════ */
        .lp-footer {
            background: var(--gray-900);
            color: rgba(255,255,255,0.6);
            padding: 4rem 2rem 2rem;
        }
        .footer-inner {
            max-width: 1200px; margin: 0 auto;
        }
        .footer-top {
            display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 3rem; margin-bottom: 3rem;
        }
        .footer-brand-name {
            font-family: var(--font-display); font-size: 1.25rem; font-weight: 700;
            color: #fff; display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 0.875rem;
        }
        .footer-brand-name strong { color: #a78bfa; }
        .footer-brand-icon {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.875rem; color: #fff;
        }
        .footer-tagline { font-size: 0.875rem; line-height: 1.7; max-width: 240px; }
        .footer-socials { display: flex; gap: 0.625rem; margin-top: 1.375rem; }
        .footer-social {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.07);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.5); font-size: 0.9375rem;
            transition: all var(--transition);
        }
        .footer-social:hover { background: var(--primary-light); color: #fff; }

        .footer-col h5 {
            font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
            color: rgba(255,255,255,0.9); margin-bottom: 1.125rem;
        }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.625rem; }
        .footer-links a {
            font-size: 0.875rem; color: rgba(255,255,255,0.5);
            transition: color var(--transition);
        }
        .footer-links a:hover { color: #fff; }

        .footer-contact-item {
            display: flex; align-items: flex-start; gap: 0.625rem;
            font-size: 0.875rem; margin-bottom: 0.625rem;
        }
        .footer-contact-item i { color: var(--accent); margin-top: 2px; flex-shrink: 0; }

        .footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin-bottom: 1.5rem; }
        .footer-bottom {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 0.8125rem; color: rgba(255,255,255,0.35);
            flex-wrap: wrap; gap: 1rem;
        }
        .footer-bottom a { color: rgba(255,255,255,0.5); }
        .footer-bottom a:hover { color: #fff; }

        @media (max-width: 900px) {
            .footer-top { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }
        @media (max-width: 560px) {
            .footer-top { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
        }

        /* ══════════════════════════════════
           ANIMATIONS
        ══════════════════════════════════ */
        .fade-in-up {
            opacity: 0; transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-in-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .delay-4 { transition-delay: 0.4s; }
    </style>
</head>
<body>

<!-- ── NAVBAR ─────────────────────────────────────────────────── -->
<nav class="lp-nav" id="mainNav">
    <a href="#" class="nav-brand">
        <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        Scholar<strong>Flow</strong>
    </a>

    <ul class="nav-links">
        <li><a href="#features">Features</a></li>
        <li><a href="#how-it-works">How it Works</a></li>
        <li><a href="#testimonials">About</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>

    <div class="nav-actions">
        <a href="<?= APP_URL ?? '#' ?>/login" class="btn-nav-login">Sign In</a>
        <a href="<?= APP_URL ?? '#' ?>/register" class="btn-nav-register">
            Get Started <i class="bi bi-arrow-right"></i>
        </a>
        <button class="nav-hamburger" id="navHamburger">
            <i class="bi bi-list"></i>
        </button>
    </div>
</nav>

<!-- ── HERO ───────────────────────────────────────────────────── -->
<section class="hero" id="home">
    <div class="hero-inner">
        <div class="hero-text">
            <div class="hero-badge fade-in-up">
                <i class="bi bi-stars"></i> Scholarship Management Platform
            </div>
            <h1 class="hero-title fade-in-up delay-1">
                Simplifying<br>Scholarship<br><span class="highlight">Management</span><br>for Everyone
            </h1>
            <p class="hero-sub fade-in-up delay-2">
                A centralized platform where students discover and apply for scholarships, reviewers evaluate with ease, and administrators manage everything — all in one place.
            </p>
            <div class="hero-actions fade-in-up delay-3">
                <a href="<?= APP_URL ?? '#' ?>/register" class="btn-hero-primary">
                    <i class="bi bi-rocket-takeoff-fill"></i> Get Started Free
                </a>
                <a href="#how-it-works" class="btn-hero-secondary">
                    Learn More <i class="bi bi-arrow-down"></i>
                </a>
            </div>
            <div class="hero-trust fade-in-up delay-4">
                <div class="trust-avatars">
                    <div class="trust-avatar">JD</div>
                    <div class="trust-avatar">ML</div>
                    <div class="trust-avatar">AR</div>
                    <div class="trust-avatar">+</div>
                </div>
                <span>Trusted by <strong>1,200+ students</strong> across the Philippines</span>
            </div>
        </div>

        <div class="hero-visual fade-in-up delay-2">
            <div class="hero-float-2">
                <i class="bi bi-patch-check-fill"></i> Application Approved!
            </div>
            <div class="dashboard-mockup">
                <div class="mock-topbar">
                    <div class="mock-topbar-brand"><i class="bi bi-mortarboard-fill"></i> ScholarFlow</div>
                    <div class="mock-dots">
                        <div class="mock-dot"></div>
                        <div class="mock-dot"></div>
                        <div class="mock-dot"></div>
                    </div>
                </div>
                <div class="mock-body">
                    <div class="mock-sidebar-strip">
                        <div class="mock-nav-item active"><i class="bi bi-house-fill"></i> Dashboard</div>
                        <div class="mock-nav-item"><i class="bi bi-award-fill"></i> Scholarships</div>
                        <div class="mock-nav-item"><i class="bi bi-file-text-fill"></i> Applications</div>
                        <div class="mock-nav-item"><i class="bi bi-person-fill"></i> Profile</div>
                    </div>
                    <div class="mock-content">
                        <div class="mock-welcome">
                            <p>Welcome back,</p>
                            <strong>Juan dela Cruz 👋</strong>
                        </div>
                        <div class="mock-stats">
                            <div class="mock-stat">
                                <div class="mock-stat-num">3</div>
                                <div class="mock-stat-lbl">Applications</div>
                            </div>
                            <div class="mock-stat">
                                <div class="mock-stat-num">1</div>
                                <div class="mock-stat-lbl">Approved</div>
                            </div>
                        </div>
                        <div class="mock-card">
                            <div class="mock-card-title">Recent Applications</div>
                            <div class="mock-row">
                                <span>CHED Merit Grant</span>
                                <span class="mock-badge approved">Approved</span>
                            </div>
                            <div class="mock-row">
                                <span>SM Foundation</span>
                                <span class="mock-badge pending">Pending</span>
                            </div>
                            <div class="mock-row">
                                <span>Ayala Scholarship</span>
                                <span class="mock-badge pending">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-float-1">
                <i class="bi bi-trophy-fill"></i> 12 scholarships available
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURES ───────────────────────────────────────────────── -->
<section class="section features" id="features">
    <div class="section-inner">
        <div class="section-header centered">
            <div class="section-eyebrow fade-in-up">Everything You Need</div>
            <h2 class="section-title fade-in-up delay-1">Built for students, reviewers,<br>and administrators</h2>
            <p class="section-sub fade-in-up delay-2">ScholarFlow brings together every tool needed to manage the full scholarship lifecycle — from discovery to decision.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-in-up">
                <div class="feature-icon fi-purple"><i class="bi bi-send-fill"></i></div>
                <h4 class="feature-title">Easy Application Submission</h4>
                <p class="feature-desc">Students fill out a guided form, upload required documents, and submit applications in minutes — no paperwork, no confusion.</p>
            </div>
            <div class="feature-card fade-in-up delay-1">
                <div class="feature-icon fi-cyan"><i class="bi bi-activity"></i></div>
                <h4 class="feature-title">Real-time Application Tracking</h4>
                <p class="feature-desc">Live status updates keep students informed at every stage — from submission to review to final decision, instantly.</p>
            </div>
            <div class="feature-card fade-in-up delay-2">
                <div class="feature-icon fi-green"><i class="bi bi-clipboard2-check-fill"></i></div>
                <h4 class="feature-title">Reviewer Evaluation System</h4>
                <p class="feature-desc">Reviewers access a dedicated dashboard to evaluate, approve, or reject applications with notes and full document access.</p>
            </div>
            <div class="feature-card fade-in-up delay-1">
                <div class="feature-icon fi-orange"><i class="bi bi-file-earmark-arrow-up-fill"></i></div>
                <h4 class="feature-title">Document Upload & Verification</h4>
                <p class="feature-desc">Secure file uploads with drag-and-drop support. Transcripts, IDs, and recommendation letters — organized and accessible.</p>
            </div>
            <div class="feature-card fade-in-up delay-2">
                <div class="feature-icon fi-pink"><i class="bi bi-shield-lock-fill"></i></div>
                <h4 class="feature-title">Secure User Management</h4>
                <p class="feature-desc">Role-based access control keeps students, reviewers, and admins in their own secure lanes with CSRF-protected forms.</p>
            </div>
            <div class="feature-card fade-in-up delay-3">
                <div class="feature-icon fi-purple"><i class="bi bi-trophy-fill"></i></div>
                <h4 class="feature-title">Scholarship Discovery</h4>
                <p class="feature-desc">Browse and filter exclusive and open scholarships. Smart locking prevents double-dipping on exclusive grants automatically.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── HOW IT WORKS ───────────────────────────────────────────── -->
<section class="section how-it-works" id="how-it-works">
    <div class="section-inner">
        <div class="section-header centered">
            <div class="section-eyebrow fade-in-up">Simple Process</div>
            <h2 class="section-title fade-in-up delay-1">Three steps to your scholarship</h2>
            <p class="section-sub fade-in-up delay-2">We've made the process as straightforward as possible, so you can focus on what matters — your education.</p>
        </div>
        <div class="steps-row">
            <div class="step-item fade-in-up">
                <div class="step-num">
                    1
                    <div class="step-icon"><i class="bi bi-pencil-fill"></i></div>
                </div>
                <h4 class="step-title">Apply</h4>
                <p class="step-desc">Browse available scholarships, review requirements, and submit your application with supporting documents through our guided form.</p>
            </div>
            <div class="step-item fade-in-up delay-2">
                <div class="step-num">
                    2
                    <div class="step-icon"><i class="bi bi-search"></i></div>
                </div>
                <h4 class="step-title">Review</h4>
                <p class="step-desc">Our reviewers carefully evaluate every submission, verify documents, and make informed decisions based on merit and eligibility.</p>
            </div>
            <div class="step-item fade-in-up delay-3">
                <div class="step-num">
                    3
                    <div class="step-icon"><i class="bi bi-check-lg"></i></div>
                </div>
                <h4 class="step-title">Track</h4>
                <p class="step-desc">Monitor your application status in real-time from your personal dashboard. Get notified the moment a decision is made.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── STATS ──────────────────────────────────────────────────── -->
<section class="section stats-section">
    <div class="section-inner">
        <div class="stats-grid-4">
            <div class="stat-block fade-in-up">
                <div class="stat-block-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-block-num">1,200+</div>
                <div class="stat-block-lbl">Total Applicants</div>
            </div>
            <div class="stat-block fade-in-up delay-1">
                <div class="stat-block-icon"><i class="bi bi-award-fill"></i></div>
                <div class="stat-block-num">500+</div>
                <div class="stat-block-lbl">Scholarships Available</div>
            </div>
            <div class="stat-block fade-in-up delay-2">
                <div class="stat-block-icon"><i class="bi bi-patch-check-fill"></i></div>
                <div class="stat-block-num">₱2M+</div>
                <div class="stat-block-lbl">Total Awarded</div>
            </div>
            <div class="stat-block fade-in-up delay-3">
                <div class="stat-block-icon"><i class="bi bi-clipboard2-check-fill"></i></div>
                <div class="stat-block-num">48</div>
                <div class="stat-block-lbl">Active Reviewers</div>
            </div>
        </div>
    </div>
</section>

<!-- ── TESTIMONIALS ───────────────────────────────────────────── -->
<section class="section testimonials" id="testimonials">
    <div class="section-inner">
        <div class="section-header centered">
            <div class="section-eyebrow fade-in-up">Student Stories</div>
            <h2 class="section-title fade-in-up delay-1">What our community says</h2>
        </div>
        <div class="testimonials-grid">
            <div class="testi-card fade-in-up">
                <div class="testi-stars">★★★★★</div>
                <p class="testi-quote">"ScholarFlow made the application process so much easier. I tracked my application status in real time and got approved within a week. Highly recommend!"</p>
                <div class="testi-author">
                    <div class="testi-avatar">JD</div>
                    <div>
                        <div class="testi-name">Juan dela Cruz</div>
                        <div class="testi-role">BS Computer Science, 3rd Year</div>
                    </div>
                </div>
            </div>
            <div class="testi-card fade-in-up delay-1">
                <div class="testi-stars">★★★★★</div>
                <p class="testi-quote">"As a reviewer, the dashboard is intuitive and saves me so much time. I can see all documents in one place and make decisions quickly without any back-and-forth."</p>
                <div class="testi-author">
                    <div class="testi-avatar">ML</div>
                    <div>
                        <div class="testi-name">Maria Lim</div>
                        <div class="testi-role">Scholarship Reviewer</div>
                    </div>
                </div>
            </div>
            <div class="testi-card fade-in-up delay-2">
                <div class="testi-stars">★★★★★</div>
                <p class="testi-quote">"I never thought applying for scholarships could be this straightforward. The document upload feature is a game-changer — no more scanning and emailing!"</p>
                <div class="testi-author">
                    <div class="testi-avatar">AR</div>
                    <div>
                        <div class="testi-name">Ana Reyes</div>
                        <div class="testi-role">BS Nursing, 2nd Year</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA ────────────────────────────────────────────────────── -->
<section class="section cta-section">
    <div class="section-inner">
        <div class="cta-box fade-in-up">
            <div class="cta-content">
                <h2 class="cta-title">Start your scholarship<br>journey today.</h2>
                <p class="cta-sub">Join thousands of students who've found funding through ScholarFlow. It's free to sign up.</p>
                <div class="cta-actions">
                    <a href="<?= APP_URL ?? '#' ?>/register" class="btn-cta-primary">
                        <i class="bi bi-mortarboard-fill"></i> Create Free Account
                    </a>
                    <a href="<?= APP_URL ?? '#' ?>/login" class="btn-cta-secondary">
                        Already have an account? Sign in
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── FOOTER ─────────────────────────────────────────────────── -->
<footer class="lp-footer" id="contact">
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-about">
                <div class="footer-brand-name">
                    <div class="footer-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    Scholar<strong>Flow</strong>
                </div>
                <p class="footer-tagline">Connecting Filipino students with the scholarships they deserve. Streamlined, transparent, and efficient.</p>
                <div class="footer-socials">
                    <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h5>Platform</h5>
                <ul class="footer-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How it Works</a></li>
                    <li><a href="<?= APP_URL ?? '#' ?>/register">Create Account</a></li>
                    <li><a href="<?= APP_URL ?? '#' ?>/login">Sign In</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#testimonials">About</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Contact</h5>
                <div class="footer-contact-item">
                    <i class="bi bi-envelope-fill"></i>
                    <span>hello@scholarflow.ph</span>
                </div>
                <div class="footer-contact-item">
                    <i class="bi bi-telephone-fill"></i>
                    <span>+63 (02) 8123-4567</span>
                </div>
                <div class="footer-contact-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Cebu City, Philippines</span>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> ScholarFlow. All rights reserved.</span>
            <span>Made with <i class="bi bi-heart-fill" style="color:#f43f5e;font-size:0.7rem"></i> in the Philippines</span>
            <span><a href="#">Privacy</a> · <a href="#">Terms</a> · <a href="#">Cookies</a></span>
        </div>
    </div>
</footer>

<script>
// Navbar scroll effect
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 20);
});

// Intersection observer for fade-in-up
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
});
</script>
</body>
</html>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>