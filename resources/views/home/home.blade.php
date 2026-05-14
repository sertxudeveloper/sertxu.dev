<x-app-layout>
    @include('home.partials.hero')
    @include('home.partials.about')
    @include('home.partials.projects')
    @include('home.partials.posts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ─── Typewriter ───
            const cmd1 = document.getElementById('cmd1');
            const cursor1 = document.getElementById('cursor1');
            const line2 = document.getElementById('line2');
            const line3 = document.getElementById('line3');
            const line4 = document.getElementById('line4');
            const heroContent = document.getElementById('hero-content');

            const text = 'whoami';
            let idx = 0;

            function typeNext() {
                if (idx < text.length) {
                    cmd1.textContent += text[idx];
                    idx++;
                    setTimeout(typeNext, 70);
                } else {
                    //cursor1.classList.remove('cursor-blink');
                    setTimeout(() => {
                        line2.classList.add('visible');
                        setTimeout(() => {
                            line3.classList.add('visible');
                            setTimeout(() => {
                                line4.classList.add('visible');
                                setTimeout(() => {
                                    //cursor1.classList.add('cursor-blink');
                                    heroContent.classList.add('visible');
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
</x-app-layout>
