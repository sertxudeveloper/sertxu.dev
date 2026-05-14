<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Sergio Peris (sertxu.dev) — Full-Stack Developer & SysAdmin</title>
<meta name="description" content="Sergio Peris, aka sertxu. Full-stack developer & sysadmin based in Xàtiva, Valencia. IT Manager, building reliable systems." />
<meta name="theme-color" content="#0a0a0c" />

<meta property="og:title" content="Sergio Peris (sertxu) — Full-stack Developer & Sysadmin" />
<meta property="og:description" content="Full-stack developer & sysadmin building reliable systems from Xàtiva, Valencia." />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://sertxu.dev" />

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800;900&family=DM+Sans:wght@400;500;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: { red: '#ff3047', blue: '#0035ff' },
                    bg: { base: '#0a0a0c', surface: '#141417', elevated: '#1a1a1f' },
                    border: '#1e1e22',
                    text: { primary: '#f0f0f2', muted: '#8a8a9a' },
                },
                fontFamily: {
                    heading: ['Archivo', 'sans-serif'],
                    body: ['DM Sans', 'sans-serif'],
                    mono: ['DM Mono', 'monospace'],
                },
            },
        },
    }
</script>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    html { scroll-behavior: smooth; }

    body {
        background: #0a0a0c;
        color: #f0f0f2;
        font-family: 'DM Sans', sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }


    ::selection { background: rgba(255, 48, 71, 0.3); color: #fff; }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #0a0a0c; }
    ::-webkit-scrollbar-thumb { background: #1e1e22; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #ff3047; }

    .grain-overlay {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 9999;
        opacity: 0.025;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        background-repeat: repeat;
        background-size: 256px 256px;
    }

    .bg-grid {
        background-image: radial-gradient(circle at center, #1e1e22 0.75px, transparent 0.75px);
        background-size: 32px 32px;
    }

    .glow-top-right {
        position: absolute;
        top: -240px;
        right: -240px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 48, 71, 0.07) 0%, transparent 65%);
        pointer-events: none;
    }

    .glow-bottom-left {
        position: absolute;
        bottom: -200px;
        left: -200px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(0, 53, 255, 0.05) 0%, transparent 65%);
        pointer-events: none;
    }

    /* Scroll reveal */
    .reveal {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.revealed {
        opacity: 1;
        transform: translateY(0);
    }
    .portfolio-grid .reveal:nth-child(1) { transition-delay: 0ms; }
    .portfolio-grid .reveal:nth-child(2) { transition-delay: 120ms; }
    .portfolio-grid .reveal:nth-child(3) { transition-delay: 240ms; }
    .blog-grid .reveal:nth-child(1) { transition-delay: 0ms; }
    .blog-grid .reveal:nth-child(2) { transition-delay: 120ms; }

    /* Hero content reveal */
    #hero-content {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s,
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
    }
    #hero-content.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Terminal lines */
    .terminal-line {
        opacity: 0;
        transition: opacity 0.5s ease-out;
    }
    .terminal-line.visible {
        opacity: 1;
    }

    /* Cursor blink */
    @keyframes cursorBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    .cursor-blink {
        animation: cursorBlink 1s step-end infinite;
    }

    /* Project card */
    .project-card {
        position: relative;
        border-left: 2px solid transparent;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .project-card:hover {
        transform: translateY(-5px);
    }
    .project-card:hover::before {
        opacity: 1;
    }

    /* Link underline */
    .link-underline {
        position: relative;
        text-decoration: none;
    }
    .link-underline::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 0;
        height: 1.5px;
        background: #0035ff;
        transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .link-underline:hover::after {
        width: 100%;
    }

    /* Section accent line */
    .accent-line {
        display: inline-block;
        width: 48px;
        height: 3px;
        background: #ff3047;
        border-radius: 2px;
    }

    /* Scroll indicator */
    .scroll-indicator {
        position: absolute;
        bottom: 32px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        opacity: 0.4;
    }
    @keyframes scrollPulse {
        0%, 100% { transform: translateY(0); opacity: 0.4; }
        50% { transform: translateY(4px); opacity: 1; }
    }
    .scroll-indicator span {
        animation: scrollPulse 2s ease-in-out infinite;
    }

    /* Nav backdrop */
    .nav-scrolled {
        background: rgba(10, 10, 12, 0.85) !important;
        backdrop-filter: blur(12px) !important;
    }
</style>
