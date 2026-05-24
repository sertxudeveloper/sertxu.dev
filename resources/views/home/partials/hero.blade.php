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
                    <span id="cursor1" class="bg-neutral-400 animate-blink w-2 h-5">&nbsp;</span>
                </div>
                <div id="line2" class="opacity-0 transition-all duration-500 flex items-center gap-2 text-neutral-300 mb-1">
                    <span class="text-neutral-500 text-[10px]">▶</span>Sergio Peris <span class="text-neutral-500">(aka. Sertxu)</span>
                </div>
                <div id="line3" class="opacity-0 transition-all duration-500 flex items-center gap-2 text-neutral-300 mb-1">
                    <span class="text-neutral-500 text-[10px]">▶</span>Full-Stack developer <span class="text-neutral-500">&</span> SysAdmin
                </div>
                <div id="line4" class="opacity-0 transition-all duration-500 flex items-center gap-2 text-neutral-300">
                    <span class="text-neutral-500 text-[10px]">▶</span>Xàtiva, València <span class="text-neutral-500">(Spain)</span>
                </div>
            </div>

            <!-- Hero Content -->
            <div id="hero-content" class="opacity-0 transition-all duration-500 translate-y-6">
                <div class="flex flex-wrap gap-4 mt-12">
                    <a href="#projects" class="px-6 py-3 bg-coral text-white rounded-lg text-sm font-medium hover:bg-coral transition-all hover:shadow-[0_0_10px_#FF3047]">
                        View my work
                    </a>
                    <a href="#contact" class="px-6 py-3 border border-neutral-700 text-neutral-200 rounded-lg text-sm font-medium hover:border-neutral-500 transition-all">
                        Get in touch
                    </a>
                </div>
            </div>
        </div>

        <div id="profile-image" class="opacity-0 transition-all duration-500 translate-y-6">
            <img src="/sergio@320.jpg" alt="Sergio Peris" class="size-80 object-cover mt-12 mx-auto md:mx-0 shadow-lg rounded-md">
        </div>
    </div>

    <div class="absolute left-1/2 flex flex-col items-center gap-2 -translate-x-1/2 bottom-6 animate-bounce opacity-40">
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
                cursor1.classList.remove('animate-blink');
                cmd1.textContent += text[idx];
                idx++;
                setTimeout(typeNext, 70);
            } else {
                cursor1.classList.add('animate-blink');
                setTimeout(() => {
                    line2.classList.remove('opacity-0');
                    setTimeout(() => {
                        line3.classList.remove('opacity-0');
                        setTimeout(() => {
                            line4.classList.remove('opacity-0');
                            setTimeout(() => {
                                heroContent.classList.remove('opacity-0', 'translate-y-6');
                                setTimeout(() => {
                                    profileImage.classList.remove('opacity-0', 'translate-y-6');
                                }, 200)
                            }, 400);
                        }, 400);
                    }, 400);
                }, 300);
            }
        }

        setTimeout(typeNext, 600);
    });
</script>
