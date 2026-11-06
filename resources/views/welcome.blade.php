<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Warehouse AMTPM System') }}</title>
    <meta name="description" content="Warehouse AMTPM System — Autonomous Maintenance Total Productive Maintenance untuk operasional gudang yang efisien.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --amtpm-red: #e60012;
            --amtpm-red-dark: #b8000e;
            --amtpm-ink: #111827;
            --amtpm-muted: #6b7280;
            --amtpm-bg: #0a0a0a;
            --amtpm-surface: #1a1a1a;
            --amtpm-glass: rgba(255, 255, 255, 0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; scroll-padding-top: 88px; }

        body {
            font-family: Inter, system-ui, sans-serif;
            color: var(--amtpm-ink);
            background: var(--amtpm-bg);
            overflow-x: hidden;
        }

        img { max-width: 100%; display: block; }

        a { text-decoration: none; color: inherit; }

        .page-wrap { min-height: 100vh; }

        /* Header */
        .site-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1001;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            letter-spacing: -.02em;
            color: #fff;
        }

        .brand-mark {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e60012, #ff4d4d);
            color: #fff;
            display: grid;
            place-items: center;
            box-shadow: 0 8px 32px rgba(230, 0, 18, 0.4);
        }

        .brand small {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 0;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            padding: 10px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            padding: 12px 20px;
            border: 1.5px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--amtpm-red);
            color: #fff;
            box-shadow: 0 4px 20px rgba(230, 0, 18, 0.3);
        }

        .btn-primary:hover {
            background: var(--amtpm-red-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(230, 0, 18, 0.4);
        }

        .btn-ghost {
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .btn-ghost:hover {
            border-color: var(--amtpm-red);
            background: rgba(230, 0, 18, 0.1);
            color: #fff;
        }

        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-outline-light:hover {
            background: #fff;
            color: var(--amtpm-red);
            border-color: #fff;
        }

        .menu-toggle {
            display: none;
            width: 44px; height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            color: #fff;
        }

        /* Hero / carousel with layered parallax */
        .hero {
            position: relative;
            min-height: 100vh;
            overflow: visible;
            color: #fff;
            z-index: 1;
        }

        .hero.sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            height: 100vh;
        }

        .hero.sticky .carousel-nav {
            position: fixed;
            z-index: 60;
        }

        .hero.sticky .carousel-indicators {
            position: fixed;
            z-index: 60;
        }

        #hero-spacer {
            height: 50vh;
            pointer-events: none;
        }

        .hero-background {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-gradient {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(230, 0, 18, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(127, 29, 29, 0.25) 0%, transparent 50%),
                linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
        }

        .hero-particles {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(2px 2px at 20px 30px, rgba(255, 255, 255, 0.1), transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255, 255, 255, 0.08), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(255, 255, 255, 0.12), transparent),
                radial-gradient(2px 2px at 130px 80px, rgba(255, 255, 255, 0.09), transparent),
                radial-gradient(1px 1px at 160px 120px, rgba(255, 255, 255, 0.07), transparent);
            background-size: 200px 200px;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(0) translateX(0); }
            100% { transform: translateY(-200px) translateX(-200px); }
        }

        .carousel-inner {
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        .carousel-item {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: 1;
        }

        .carousel-item-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            transform: scale(1.1);
        }

        .carousel-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            z-index: 2;
        }

        .carousel-item.active {
            opacity: 1;
            visibility: visible;
        }

        .carousel-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10, 10, 10, 0.4) 0%, rgba(10, 10, 10, 0.8) 100%);
        }

        .hero-fallback {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 30%, rgba(230, 0, 18, 0.25) 0%, transparent 50%),
                linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
        }

        .hero-content {
            position: relative;
            z-index: 20;
            max-width: 1400px;
            margin: 0 auto;
            padding: 160px 24px 120px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .hero-content-layer {
            transform: translateY(60px);
            opacity: 0;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }

        .eyebrow i {
            color: var(--amtpm-red);
        }

        .hero h1 {
            font-size: clamp(36px, 6vw, 72px);
            line-height: 1.05;
            letter-spacing: -.04em;
            max-width: 14ch;
            margin-bottom: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p.lead {
            font-size: clamp(16px, 2vw, 20px);
            max-width: 54ch;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .carousel-nav {
            position: absolute;
            z-index: 20;
            top: 50%;
            transform: translateY(-50%);
            width: 50px; height: 50px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-nav:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .carousel-nav.prev { left: 24px; }
        .carousel-nav.next { right: 24px; }

        .carousel-indicators {
            position: absolute;
            z-index: 20;
            left: 50%;
            bottom: 40px;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
        }

        .carousel-indicator {
            width: 12px; height: 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.3);
            border: 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-indicator.active {
            background: #fff;
            width: 36px;
        }

        .caption-top .hero-content { justify-content: flex-start; padding-top: 180px; }
        .caption-left .hero-content { align-items: flex-start; }
        .caption-right .hero-content { align-items: flex-end; text-align: right; }
        .caption-right .hero p.lead { margin-left: auto; }

        /* Stats section */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            position: relative;
            z-index: 30;
            padding: 20px 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.98), rgba(17, 17, 17, 0.98));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 32px 28px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            transition: all 0.4s ease;
            overflow: hidden;
            opacity: 1 !important;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: rgba(230, 0, 18, 0.3);
            box-shadow: 0 30px 80px rgba(230, 0, 18, 0.15);
        }

        .stat-card strong {
            display: block;
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, rgba(255, 255, 255, 0.9));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card span {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            font-weight: 600;
        }

        .stat-card i {
            color: var(--amtpm-red);
            font-size: 28px;
            margin-bottom: 16px;
            display: inline-block;
        }

        /* Sections with stacking card effect */
        .section {
            padding: 120px 24px;
            position: relative;
            overflow: visible;
            z-index: 10;
            transform-style: preserve-3d;
            perspective: 2000px;
            margin-top: -60px;
            padding-top: 180px;
            padding-bottom: 140px;
            border-radius: 32px;
            background: #0a0a0a;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            border-radius: 32px;
            overflow: hidden;
            background: #0a0a0a;
        }

        .section:first-of-type {
            margin-top: 0;
            padding-top: 100px;
        }

        .section#stats {
            margin-top: 0;
            padding-top: 60px;
            padding-bottom: 80px;
            border-radius: 32px 32px 0 0;
            background: #0a0a0a;
            width: 100%;
            max-width: 100%;
        }

        .section#stats .section-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            border-radius: 32px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
        }

        .section-inner {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-kicker {
            color: var(--amtpm-red);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .section h2 {
            font-size: clamp(32px, 4vw, 48px);
            letter-spacing: -.03em;
            margin-bottom: 16px;
            font-weight: 800;
            color: #fff;
        }

        .section-lead {
            color: rgba(255, 255, 255, 0.85);
            font-size: 18px;
            line-height: 1.8;
            max-width: 68ch;
            margin-bottom: 48px;
        }

        /* About section */
        .about-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 48px;
            align-items: center;
        }

        .about-copy {
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.9;
            font-size: 17px;
        }

        .about-panel {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.9), rgba(127, 29, 29, 0.3));
            backdrop-filter: blur(20px);
            color: #fff;
            border-radius: 24px;
            padding: 40px;
            min-height: 320px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .about-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(17, 24, 39, 0.2), rgba(230, 0, 18, 0.3));
        }

        .about-panel-inner {
            position: relative;
            z-index: 1;
        }

        /* 3D Carousel for 4 Pillars */
        .pillars-container {
            position: relative;
            height: 480px;
            perspective: 1200px;
            margin: 60px 0;
        }

        .pillars-carousel {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
        }

        .pillar-card {
            position: absolute;
            width: 280px;
            height: 420px;
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.98), rgba(17, 17, 17, 0.98));
            backdrop-filter: blur(20px);
            color: #fff;
            border-radius: 24px;
            padding: 32px 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
            cursor: pointer;
            left: 50%;
            top: 50%;
            margin-left: -140px;
            margin-top: -210px;
        }

        .pillar-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(230, 0, 18, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .pillar-card:hover::before {
            opacity: 1;
        }

        .pillar-no {
            font-size: 14px;
            font-weight: 800;
            color: #fca5a5;
            margin-bottom: 24px;
            letter-spacing: 0.1em;
        }

        .pillar-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: rgba(230, 0, 18, 0.15);
            display: grid;
            place-items: center;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .pillar-icon img { width: 40px; height: 40px; object-fit: contain; }
        .pillar-icon i { color: #fecaca; font-size: 28px; }

        .pillar-card h3 {
            font-size: 22px;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .pillar-card .sub {
            color: #fca5a5;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: 0.05em;
        }

        .pillar-card p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
            line-height: 1.7;
        }

        .carousel-nav-3d {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 56px; height: 56px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 30;
        }

        .carousel-nav-3d:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .carousel-nav-3d.prev { left: -80px; }
        .carousel-nav-3d.next { right: -80px; }

        /* Flow process */
        .flow-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
        }

        .flow-card {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.98), rgba(17, 17, 17, 0.98));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            opacity: 1 !important;
        }

        .flow-card:hover {
            transform: translateY(-10px);
            border-color: rgba(230, 0, 18, 0.4);
            box-shadow: 0 30px 60px rgba(230, 0, 18, 0.15);
        }

        .flow-media {
            height: 180px;
            background: linear-gradient(135deg, rgba(230, 0, 18, 0.25), rgba(230, 0, 18, 0.1));
            display: grid;
            place-items: center;
            font-size: 48px;
            font-weight: 800;
            color: #fff;
            position: relative;
        }

        .flow-media img {
            position: absolute;
            inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .flow-body { padding: 24px; }
        .flow-body h3 { font-size: 18px; margin-bottom: 10px; color: #fff; font-weight: 700; }
        .flow-body p { color: rgba(255, 255, 255, 0.85); font-size: 14px; line-height: 1.7; }

        /* Tag filter */
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 32px;
        }

        .filter-btn {
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
            border-radius: 999px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: var(--amtpm-red);
            border-color: var(--amtpm-red);
            color: #fff;
        }

        .tag-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .tag-card {
            background: rgba(26, 26, 26, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .tag-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .tag-card.red { border-left: 4px solid var(--amtpm-red); }
        .tag-card.white { border-left: 4px solid #9ca3af; }

        .tag-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
            margin-bottom: 12px;
        }

        .badge {
            display: inline-flex;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 800;
        }

        .badge-red { background: rgba(230, 0, 18, 0.15); color: #fecaca; }
        .badge-gray { background: rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.7); }

        .tag-card h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #fff;
            font-weight: 700;
        }

        .tag-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            line-height: 1.6;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 24px;
            color: rgba(255, 255, 255, 0.5);
            background: rgba(26, 26, 26, 0.5);
            border: 1px dashed rgba(255, 255, 255, 0.15);
            border-radius: 20px;
        }

        /* CTA section */
        .cta {
            background: linear-gradient(135deg, rgba(230, 0, 18, 0.9), rgba(127, 29, 29, 0.9));
            backdrop-filter: blur(20px);
            color: #fff;
            border-radius: 32px;
            padding: 64px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 32px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cta h2 { margin-bottom: 12px; font-size: 32px; font-weight: 800; }
        .cta p { color: rgba(255, 255, 255, 0.85); font-size: 18px; line-height: 1.6; }

        /* Footer */
        footer {
            background: #0a0a0a;
            color: rgba(255, 255, 255, 0.6);
            padding: 48px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
            font-size: 14px;
        }

        .footer-inner strong {
            color: #fff;
            font-size: 16px;
        }

        /* Scroll animations */
        .scroll-section {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-section.visible {
            opacity: 1;
            transform: none;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .carousel-nav-3d.prev { left: 0; }
            .carousel-nav-3d.next { right: 0; }

            .stats {
                gap: 16px;
            }

            .stat-card {
                padding: 24px 20px;
            }

            .stat-card strong {
                font-size: 32px;
            }

            .stat-card span {
                font-size: 13px;
            }
        }

        @media (max-width: 960px) {
            .stats, .flow-grid, .tag-grid, .about-grid { grid-template-columns: 1fr 1fr; }
            .cta { flex-direction: column; align-items: flex-start; }

            .stat-card {
                padding: 24px 20px;
            }

            .stat-card strong {
                font-size: 32px;
            }

            .stat-card span {
                font-size: 13px;
            }

            .pillars-container {
                height: 420px;
            }

            .pillar-card {
                width: 240px;
                height: 380px;
                margin-left: -120px;
                margin-top: -190px;
            }

            .carousel-nav-3d {
                width: 48px;
                height: 48px;
            }
        }

        @media (max-width: 760px) {
            .menu-toggle { display: grid; place-items: center; }
            .nav-links {
                display: none;
                position: absolute;
                top: 76px; left: 16px; right: 16px;
                background: rgba(26, 26, 26, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                padding: 16px;
                flex-direction: column;
                align-items: stretch;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            }
            .nav-links.open { display: flex; }
            .stats, .flow-grid, .tag-grid, .about-grid { grid-template-columns: 1fr; }
            .hero-content { padding: 140px 20px 100px; min-height: 88vh; }
            .carousel-nav { display: none; }

            .stat-card {
                padding: 24px 20px;
            }

            .stat-card strong {
                font-size: 28px;
            }

            .stat-card span {
                font-size: 14px;
            }

            .pillars-container {
                height: 360px;
            }

            .pillar-card {
                width: 200px;
                height: 340px;
                margin-left: -100px;
                margin-top: -170px;
                padding: 24px 20px;
            }

            .pillar-icon {
                width: 56px;
                height: 56px;
            }

            .pillar-icon i {
                font-size: 24px;
            }

            .carousel-nav-3d {
                display: none;
            }

            .section {
                padding: 60px 20px;
                margin-top: -40px;
                padding-top: 120px;
                padding-bottom: 100px;
            }

            .section#stats {
                padding-top: 50px;
                padding-bottom: 60px;
            }

            .flow-card {
                border: 1px solid rgba(255, 255, 255, 0.15);
            }

            .cta {
                padding: 40px 24px;
            }

            .cta h2 {
                font-size: 24px;
            }

            .cta p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
@php
    $homepageAsset = static function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Always return storage URL for carousel images
        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    };

    $pillarIcons = ['bi-droplet', 'bi-wrench-adjustable', 'bi-moisture', 'bi-search'];
@endphp

<div class="page-wrap">
    <header class="site-header">
        <div class="header-inner">
            <a href="{{ url('/') }}" class="brand">
                <span class="brand-mark"><i class="bi bi-gear-fill"></i></span>
                <span>
                    AMTPM
                    <small>Warehouse System</small>
                </span>
            </a>

            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Menu">
                <i class="bi bi-list" style="font-size: 22px;"></i>
            </button>

            <nav class="nav-links" id="navLinks">
                <a href="#stats">Statistik</a>
                <a href="#tentang">Tentang</a>
                <a href="#pilar">4 Pilar</a>
                <a href="#proses">Proses</a>
                <a href="#maintenance">Maintenance</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <section class="hero" id="beranda">
        <div class="hero-background">
            <div class="hero-gradient"></div>
            <div class="hero-particles"></div>
        </div>

        <div class="carousel-inner">
            @forelse($carouselItems as $index => $item)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }} caption-{{ $item->caption_position ?: 'bottom' }}"
                     data-index="{{ $index }}">
                    @if($homepageAsset($item->image_path))
                        <div class="carousel-item-bg" style="background-image: url('{{ $homepageAsset($item->image_path) }}');"></div>
                    @endif
                    <div class="hero-content">
                        <div class="hero-content-layer">
                            <div class="eyebrow"><i class="bi bi-buildings"></i> Warehouse AMTPM</div>
                            <h1>{{ $item->title ?: 'Warehouse AMTPM System' }}</h1>
                            <p class="lead">{{ $item->description ?: 'Kelola autonomous maintenance, red tag, dan white tag dalam satu sistem.' }}</p>
                            <div class="hero-actions">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Buka Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk Sistem</a>
                                @endauth
                                <a href="#pilar" class="btn btn-outline-light">Lihat 4 Pilar</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-item active hero-fallback">
                    <div class="hero-content">
                        <div class="hero-content-layer">
                            <div class="eyebrow"><i class="bi bi-buildings"></i> Warehouse AMTPM</div>
                            <h1>Warehouse AMTPM System</h1>
                            <p class="lead">Autonomous Maintenance Total Productive Maintenance untuk efisiensi operasional gudang, unit, dan perawatan harian.</p>
                            <div class="hero-actions">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Buka Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk Sistem</a>
                                @endauth
                                <a href="#tentang" class="btn btn-outline-light">Pelajari AMTPM</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($carouselItems->count() > 1)
            <button type="button" class="carousel-nav prev" data-dir="-1" aria-label="Slide sebelumnya"><i class="bi bi-chevron-left"></i></button>
            <button type="button" class="carousel-nav next" data-dir="1" aria-label="Slide berikutnya"><i class="bi bi-chevron-right"></i></button>
            <div class="carousel-indicators">
                @foreach($carouselItems as $index => $item)
                    <button type="button" class="carousel-indicator {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </section>

    <div id="hero-spacer" style="height: 50vh;"></div>

    <section class="section scroll-section" id="stats">
        <div class="section-bg" style="background: #0a0a0a;"></div>
        <div class="section-inner">
            <div class="section-kicker">Ringkasan Data</div>
            <h2>Statistik Sistem</h2>
            <p class="section-lead">Monitoring real-time data red tag, white tag, dan aktivitas maintenance warehouse.</p>

            <div class="stats">
                <div class="stat-card">
                    <i class="bi bi-flag-fill"></i>
                    <strong>{{ $openRedCount }}</strong>
                    <span>Red Tag Terbuka</span>
                </div>
                <div class="stat-card">
                    <i class="bi bi-flag"></i>
                    <strong>{{ $openWhiteCount }}</strong>
                    <span>White Tag Terbuka</span>
                </div>
                <div class="stat-card">
                    <i class="bi bi-layers"></i>
                    <strong>{{ $sliderRotateItems->count() ?: 4 }}</strong>
                    <span>Pilar AMTPM</span>
                </div>
                <div class="stat-card">
                    <i class="bi bi-diagram-3"></i>
                    <strong>{{ $flowProcessItems->count() ?: 4 }}</strong>
                    <span>Langkah Proses</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section scroll-section" id="tentang">
        <div class="section-bg" style="background: #0a0a0a;"></div>
        <div class="section-inner">
            <div class="about-grid">
                <div>
                    <div class="section-kicker">Tentang AMTPM</div>
                    <h2>{{ $amtpmBackground->title ?? 'Apa itu AMTPM?' }}</h2>
                    <div class="about-copy">
                        @if($amtpmBackground?->content)
                            {!! $amtpmBackground->content !!}
                        @else
                            <p>Autonomous Maintenance Total Productive Maintenance adalah pendekatan sistematis untuk pemeliharaan peralatan yang melibatkan operator dalam kegiatan pemeliharaan sehari-hari. Tujuannya meningkatkan efisiensi operasional dan mengurangi downtime.</p>
                        @endif
                    </div>
                </div>
                <div class="about-panel" @if($homepageAsset($amtpmBackground->image_path ?? null)) style="background-image: url('{{ $homepageAsset($amtpmBackground->image_path) }}');" @endif>
                    <div class="about-panel-inner">
                        <div class="section-kicker" style="color:#fecaca;">Fokus operasional</div>
                        <h3 style="font-size: 28px; margin-bottom: 12px;">Cleaning, Tightening, Lubrication, Inspection</h3>
                        <p style="color: rgba(255,255,255,.85); line-height: 1.7;">Empat pilar harian yang menjaga unit warehouse tetap siap pakai.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section scroll-section" id="pilar" style="background: #0a0a0a;">
        <div class="section-bg" style="background: #0a0a0a;"></div>
        <div class="section-inner">
            <div class="section-kicker">Fondasi kerja</div>
            <h2>4 Pilar AMTPM</h2>
            <p class="section-lead">Aktivitas rutin operator untuk menjaga kondisi unit, mencegah kerusakan, dan menekan downtime.</p>

            <div class="pillars-container">
                <button type="button" class="carousel-nav-3d prev" data-dir="-1" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <div class="pillars-carousel" id="pillarsCarousel">
                    @forelse($sliderRotateItems as $index => $item)
                        <article class="pillar-card" data-index="{{ $index }}">
                            <div class="pillar-no">0{{ $item->position ?: $index + 1 }}</div>
                            <div class="pillar-icon">
                                @if($homepageAsset($item->icon_path))
                                    <img src="{{ $homepageAsset($item->icon_path) }}" alt="{{ $item->title }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                    <i class="bi {{ $pillarIcons[$index % 4] }}" style="display:none;"></i>
                                @else
                                    <i class="bi {{ $pillarIcons[$index % 4] }}"></i>
                                @endif
                            </div>
                            <h3>{{ $item->title }}</h3>
                            @if($item->subtitle)
                                <div class="sub">{{ $item->subtitle }}</div>
                            @endif
                            <p>{{ $item->description }}</p>
                        </article>
                    @empty
                        @foreach([
                            ['CLEANING', 'PEMBERSIHAN', 'Membersihkan unit dan area kerja dari debu, kotoran, dan material sisa.'],
                            ['TIGHTENING', 'PENGENCANGAN', 'Mengencangkan baut dan komponen untuk mencegah getaran dan kebocoran.'],
                            ['LUBRICATION', 'PELUMASAN', 'Melumasi bagian bergerak agar tidak aus dan tetap beroperasi halus.'],
                            ['INSPECTION', 'INSPEKSI', 'Memeriksa kondisi peralatan secara rutin untuk deteksi dini masalah.'],
                        ] as $index => $fallback)
                            <article class="pillar-card" data-index="{{ $index }}">
                                <div class="pillar-no">0{{ $index + 1 }}</div>
                                <div class="pillar-icon"><i class="bi {{ $pillarIcons[$index] }}"></i></div>
                                <h3>{{ $fallback[0] }}</h3>
                                <div class="sub">{{ $fallback[1] }}</div>
                                <p>{{ $fallback[2] }}</p>
                            </article>
                        @endforeach
                    @endforelse
                </div>
                <button type="button" class="carousel-nav-3d next" data-dir="1" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <section class="section scroll-section" id="proses" style="background: #0a0a0a;">
        <div class="section-bg" style="background: #0a0a0a;"></div>
        <div class="section-inner">
            <div class="section-kicker">Alur kerja</div>
            <h2>Flow Proses AMTPM</h2>
            <p class="section-lead">Dari perencanaan hingga evaluasi, setiap langkah terdokumentasi agar perawatan tetap terukur.</p>

            <div class="flow-grid">
                @forelse($flowProcessItems as $index => $item)
                    <article class="flow-card">
                        <div class="flow-media">
                            {{ str_pad((string) ($item->order ?: $index + 1), 2, '0', STR_PAD_LEFT) }}
                            @if($homepageAsset($item->image_path))
                                <img src="{{ $homepageAsset($item->image_path) }}" alt="{{ $item->title }}" onerror="this.remove()">
                            @endif
                        </div>
                        <div class="flow-body">
                            <h3>{{ $item->title }}</h3>
                            @if($item->description)
                                <p>{{ $item->description }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    @foreach([
                        ['Perencanaan', 'Rencanakan jadwal maintenance berdasarkan prioritas unit.'],
                        ['Pelaksanaan', 'Lakukan perawatan sesuai standar AMTPM yang ditetapkan.'],
                        ['Pemantauan', 'Pantau hasil, red tag, dan white tag secara berkala.'],
                        ['Evaluasi', 'Evaluasi hasil dan tingkatkan proses perawatan.'],
                    ] as $index => $fallback)
                        <article class="flow-card">
                            <div class="flow-media">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="flow-body">
                                <h3>{{ $fallback[0] }}</h3>
                                <p>{{ $fallback[1] }}</p>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="section scroll-section" id="maintenance" style="background: #0a0a0a;">
        <div class="section-bg" style="background: #0a0a0a;"></div>
        <div class="section-inner">
            <div class="section-kicker">Monitoring</div>
            <h2>{{ $maintenanceDataSection->title ?? 'Data Maintenance' }}</h2>
            @if($maintenanceDataSection?->content)
                <div class="section-lead">{!! $maintenanceDataSection->content !!}</div>
            @else
                <p class="section-lead">Pantau temuan lapangan: Red Tag untuk masalah prioritas tinggi, White Tag untuk temuan yang tetap perlu ditindaklanjuti.</p>
            @endif

            <div class="filter-row">
                <button type="button" class="filter-btn active" data-filter="all">Semua</button>
                <button type="button" class="filter-btn" data-filter="red">Red Tag</button>
                <button type="button" class="filter-btn" data-filter="white">White Tag</button>
            </div>

            <div class="tag-grid" id="tagCards">
                @forelse($redTags as $tag)
                    <article class="tag-card red" data-type="red">
                        <div class="tag-meta">
                            <span class="badge badge-red">Red Tag</span>
                            <span>{{ $tag->created_at?->format('d M Y') }}</span>
                        </div>
                        <h3 style="font-size:16px; margin-bottom:8px;">{{ $tag->unit?->kode_unit ?? $tag->unit?->nama_unit ?? 'Unit' }}</h3>
                        <p style="color:rgba(255,255,255,.6); font-size:14px; line-height:1.6;">{{ \Illuminate\Support\Str::limit($tag->description, 140) }}</p>
                    </article>
                @empty
                @endforelse

                @foreach($whiteTags as $tag)
                    <article class="tag-card white" data-type="white">
                        <div class="tag-meta">
                            <span class="badge badge-gray">White Tag</span>
                            <span>{{ $tag->created_at?->format('d M Y') }}</span>
                        </div>
                        <h3 style="font-size:16px; margin-bottom:8px;">{{ $tag->unit?->kode_unit ?? $tag->unit?->nama_unit ?? 'Unit' }}</h3>
                        <p style="color:rgba(255,255,255,.6); font-size:14px; line-height:1.6;">{{ \Illuminate\Support\Str::limit($tag->description, 140) }}</p>
                    </article>
                @endforeach

                @if($redTags->isEmpty() && $whiteTags->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-inbox" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                        Belum ada red tag atau white tag yang terbuka.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-inner">
            <div class="cta">
                <div>
                    <h2>Siap kelola maintenance warehouse?</h2>
                    <p>Masuk ke sistem untuk memantau unit, checklist, QR scanner, dan status tag.</p>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost">Ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Login sekarang</a>
                @endauth
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-inner">
            <div>
                <strong style="color:#fff;">{{ config('app.name', 'Warehouse AMTPM System') }}</strong>
                <div>Autonomous Maintenance untuk operasional gudang.</div>
            </div>
            <div>&copy; {{ date('Y') }} AMTPM Warehouse. All rights reserved.</div>
        </div>
    </footer>
</div>

<script>
    (function () {
        // Register GSAP plugins
        gsap.registerPlugin(ScrollTrigger);

        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        if (menuToggle && navLinks) {
            menuToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
            navLinks.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => navLinks.classList.remove('open'));
            });
        }

        // Hero carousel with GSAP animations
        const slides = Array.from(document.querySelectorAll('.carousel-item'));
        const indicators = Array.from(document.querySelectorAll('.carousel-indicator'));
        const navButtons = Array.from(document.querySelectorAll('.carousel-nav'));
        let current = 0;
        let timer = null;

        function showSlide(index) {
            if (!slides.length) return;
            const prev = current;
            current = (index + slides.length) % slides.length;

            // Animate out current slide
            gsap.to(slides[prev], {
                opacity: 0,
                duration: 0.5,
                ease: 'power2.inOut'
            });

            // Animate in new slide
            gsap.to(slides[current], {
                opacity: 1,
                duration: 0.5,
                ease: 'power2.inOut',
                onStart: () => {
                    slides[current].classList.add('active');
                    slides[prev].classList.remove('active');
                }
            });

            // Animate content layer
            const contentLayer = slides[current].querySelector('.hero-content-layer');
            if (contentLayer) {
                gsap.fromTo(contentLayer,
                    { y: 60, opacity: 0 },
                    { y: 0, opacity: 1, duration: 0.6, ease: 'power3.out', delay: 0.1 }
                );
            }

            // Update indicators
            indicators.forEach((dot, i) => dot.classList.toggle('active', i === current));
        }

        function startTimer() {
            if (slides.length < 2) return;
            stopTimer();
            timer = setInterval(() => showSlide(current + 1), 6000);
        }

        function stopTimer() {
            if (timer) clearInterval(timer);
        }

        navButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                showSlide(current + Number(btn.dataset.dir));
                startTimer();
            });
        });

        indicators.forEach((dot) => {
            dot.addEventListener('click', () => {
                showSlide(Number(dot.dataset.index));
                startTimer();
            });
        });

        // Initial animation
        if (slides.length > 0) {
            const initialContent = slides[0].querySelector('.hero-content-layer');
            if (initialContent) {
                gsap.to(initialContent, {
                    y: 0,
                    opacity: 1,
                    duration: 0.7,
                    ease: 'power3.out',
                    delay: 0.2
                });
            }
        }

        startTimer();

        // Parallax effect for hero background - simplified
        gsap.to('.hero-gradient', {
            scrollTrigger: {
                trigger: '.hero',
                start: 'top top',
                end: 'bottom top',
            },
            opacity: 0.5,
            duration: 0.5
        });

        // Sticky hero with parallax effect
        const hero = document.querySelector('.hero');
        const heroSpacer = document.querySelector('#hero-spacer');

        if (hero && heroSpacer) {
            // Use GSAP ScrollTrigger to pin hero with parallax
            ScrollTrigger.create({
                trigger: hero,
                start: 'top top',
                endTrigger: heroSpacer,
                end: 'bottom top',
                pin: true,
                pinSpacing: false,
                onUpdate: (self) => {
                    // Parallax effect on background
                    const progress = self.progress;
                    gsap.to('.hero-background', {
                        y: progress * 200,
                        opacity: 1 - (progress * 0.7),
                        duration: 0.1
                    });

                    // Scale carousel images for parallax
                    slides.forEach((slide) => {
                        const bg = slide.querySelector('.carousel-item-bg');
                        if (bg) {
                            gsap.to(bg, {
                                scale: 1 + (progress * 0.3),
                                y: progress * 100,
                                duration: 0.1
                            });
                        }
                    });
                }
            });
        }





        // Section stacking card effect - optimized
        document.querySelectorAll('.section').forEach((section, index) => {
            const bg = section.querySelector('.section-bg');
            const inner = section.querySelector('.section-inner');

            // Set z-index staggering untuk stacking effect
            section.style.zIndex = 10 + index;

            // Skip animation for stats section
            if (section.id === 'stats') return;

            // Section reveal dengan stacking card effect - tanpa scrub
            gsap.fromTo(section,
                {
                    y: 60,
                    opacity: 0,
                    scale: 0.98
                },
                {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    duration: 0.5,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 85%',
                    }
                }
            );
        });

        // Scroll section animations - simplified
        document.querySelectorAll('.scroll-section').forEach((section) => {
            gsap.fromTo(section,
                { y: 40, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 85%',
                    }
                }
            );
        });

        // 3D Carousel for 4 Pillars
        const pillarsCarousel = document.getElementById('pillarsCarousel');
        const pillarCards = Array.from(document.querySelectorAll('.pillar-card'));
        const pillarNavButtons = Array.from(document.querySelectorAll('.carousel-nav-3d'));
        let pillarCurrent = 0;

        function updatePillarCarousel() {
            const total = pillarCards.length;
            const radius = window.innerWidth < 960 ? 240 : 320;
            const angleStep = 360 / total;

            pillarCards.forEach((card, index) => {
                const angle = angleStep * (index - pillarCurrent);
                const radians = angle * (Math.PI / 180);
                const x = Math.sin(radians) * radius;
                const z = Math.cos(radians) * radius - radius;
                const opacity = Math.max(0.6, Math.cos(radians) * 0.4 + 0.6);
                const scale = Math.max(0.8, Math.cos(radians) * 0.2 + 0.8);

                gsap.to(card, {
                    x: x,
                    z: z,
                    rotationY: -angle,
                    opacity: opacity,
                    scale: scale,
                    duration: 0.4,
                    ease: 'power2.out'
                });
            });
        }

        pillarNavButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                pillarCurrent += Number(btn.dataset.dir);
                updatePillarCarousel();
            });
        });

        // Initialize carousel
        if (pillarCards.length > 0) {
            updatePillarCarousel();

            // Auto-rotate
            setInterval(() => {
                pillarCurrent += 1;
                updatePillarCarousel();
            }, 5000);
        }

        // Tag filter
        document.querySelectorAll('.filter-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach((item) => item.classList.remove('active'));
                btn.classList.add('active');
                const filter = btn.dataset.filter;
                const cards = document.querySelectorAll('.tag-card');
                let visible = 0;
                cards.forEach((card) => {
                    const show = filter === 'all' || card.dataset.type === filter;
                    if (show) {
                        gsap.to(card, { display: 'block', opacity: 1, y: 0, duration: 0.2 });
                        visible += 1;
                    } else {
                        gsap.to(card, { opacity: 0, y: 20, duration: 0.2, onComplete: () => card.style.display = 'none' });
                    }
                });
                const empty = document.querySelector('.empty-state');
                if (empty && cards.length) {
                    if (visible) {
                        gsap.to(empty, { display: 'none', duration: 0 });
                    } else {
                        gsap.to(empty, { display: 'block', duration: 0 });
                        empty.textContent = 'Tidak ada data untuk filter ini.';
                    }
                }
            });
        });

        // Flow cards simple animation - no stagger to prevent opacity issues
        gsap.from('.flow-grid', {
            scrollTrigger: {
                trigger: '.flow-grid',
                start: 'top 80%'
            },
            y: 60,
            opacity: 0,
            duration: 0.6,
            ease: 'power3.out'
        });

        // Stats cards stagger animation
        gsap.from('.stat-card', {
            scrollTrigger: {
                trigger: '.stats',
                start: 'top 80%'
            },
            y: 40,
            opacity: 0,
            duration: 0.4,
            stagger: 0.08,
            ease: 'power3.out'
        });

        // CTA section animation
        gsap.from('.cta', {
            scrollTrigger: {
                trigger: '.cta',
                start: 'top 80%'
            },
            y: 40,
            opacity: 0,
            duration: 0.5,
            ease: 'power3.out'
        });

        // Refresh ScrollTrigger on resize
        window.addEventListener('resize', () => {
            ScrollTrigger.refresh();
            if (pillarCards.length > 0) {
                updatePillarCarousel();
            }
        });
    })();
</script>
</body>
</html>
