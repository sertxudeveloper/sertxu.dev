<section class="relative min-h-screen flex items-center bg-grid overflow-hidden">
    <div class="glow-top-right"></div>
    <div class="glow-bottom-left"></div>

    <div class="max-w-5xl mx-auto px-6 w-full pt-0 pb-16 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="max-w-2xl">
            <!-- Terminal -->
            <div class="font-mono text-sm md:text-base">
                <div class="text-neutral-400 flex items-center gap-1.5 mb-2">
                    <span class="text-green-400 font-medium">sertxu@eu-south-2</span>
                    <span class="text-neutral-600">:~$&nbsp;</span>
                    <span id="cmd1"></span>
                    <span id="cursor1" class="bg-neutral-400 cursor-blink w-2 h-5">&nbsp;</span>
                </div>
                <div id="line2" class="terminal-line text-neutral-300 mb-1">
                    <span class="text-neutral-500 mr-2">▸</span>Sergio Peris <span class="text-neutral-500">(aka. Sertxu)</span>
                </div>
                <div id="line3" class="terminal-line text-neutral-300 mb-1">
                    <span class="text-neutral-500 mr-2">▸</span>Full-Stack developer <span class="text-neutral-500">&</span> SysAdmin
                </div>
                <div id="line4" class="terminal-line text-neutral-300">
                    <span class="text-neutral-500 mr-2">▸</span>Xàtiva, València <span class="text-neutral-500">(Spain)</span>
                </div>
            </div>

            <!-- Hero Content -->
            <div id="hero-content">
                <div class="flex flex-wrap gap-4 mt-12">
                    <a href="#projects" class="px-6 py-3 bg-[#FF3047] text-white rounded-lg text-sm font-medium hover:bg-[#FF3047] transition-all hover:shadow-[0_0_24px_rgba(255,48,71,0.2)]">
                        View my work
                    </a>
                    <a href="#footer" class="px-6 py-3 border border-neutral-700 text-neutral-200 rounded-lg text-sm font-medium hover:border-neutral-500 transition-all">
                        Get in touch
                    </a>
                </div>
            </div>
        </div>

        <div id="profile-image">
            <img src="/sergio.jpg" alt="Sergio Peris" class="size-80 object-cover mt-12 mx-auto md:mx-0 shadow-lg rounded-md">
        </div>
    </div>

    <div class="scroll-indicator">
        <span class="text-neutral-300 text-xs font-mono tracking-widest">SCROLL</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-neutral-300">
            <path d="M12 5v14M5 12l7 7 7-7" />
        </svg>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ─── Typewriter ───
        const cmd1 = document.getElementById('cmd1');
        const cursor1 = document.getElementById('cursor1');
        const line2 = document.getElementById('line2');
        const line3 = document.getElementById('line3');
        const line4 = document.getElementById('line4');
        const heroContent = document.getElementById('hero-content');
        const profileImage = document.getElementById('profile-image');

        const text = 'whoami';
        let idx = 0;

        function typeNext() {
            if (idx < text.length) {
                cursor1.classList.remove('cursor-blink');
                cmd1.textContent += text[idx];
                idx++;
                setTimeout(typeNext, 70);
            } else {
                cursor1.classList.add('cursor-blink');
                setTimeout(() => {
                    line2.classList.add('visible');
                    setTimeout(() => {
                        line3.classList.add('visible');
                        setTimeout(() => {
                            line4.classList.add('visible');
                            setTimeout(() => {
                                heroContent.classList.add('visible');
                                setTimeout(() => {
                                    profileImage.classList.add('visible');
                                }, 200)
                            }, 400);
                        }, 400);
                    }, 400);
                }, 300);
            }
        }

        setTimeout(typeNext, 600);

        // ─── Scroll Reveal ───
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // ─── Nav scroll effect ───
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                navbar.classList.add('nav-scrolled');
                navbar.style.borderBottom = '1px solid rgba(30,30,34,0.5)';
            } else {
                navbar.classList.remove('nav-scrolled');
                navbar.style.borderBottom = '0px solid transparent';
            }
        });

        // ─── Mobile menu ───
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');

        menuBtn.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });

        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            });
        });
    });
</script>
