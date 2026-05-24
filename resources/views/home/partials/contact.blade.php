<!-- Contact -->
<section id="footer" x-data class="py-28 md:py-36 border-t border-neutral-900">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center" x-reveal>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-stone-300">Contact</h2>
            <span class="bg-[#ff3047] inline-block h-0.5 rounded-full w-12 mt-4"></span>
            <p class="text-stone-400 text-base mt-3">
                Have a project in mind? Let's talk.
            </p>
        </div>

        <div class="mt-10 max-w-3xl mx-auto" x-reveal>
            <div class="bg-neutral-900 border border-neutral-800 rounded-lg overflow-hidden">
                <div class="bg-neutral-950/50 px-4 py-3 flex items-center gap-2 border-b border-neutral-800">
                    <span class="inline-block size-3 rounded-full bg-[#ff5f57]"></span>
                    <span class="inline-block size-3 rounded-full bg-[#febc2e]"></span>
                    <span class="inline-block size-3 rounded-full bg-[#28c840]"></span>
                </div>
                <div class="p-6 text-sm font-mono">
                    <div class="pb-4 text-neutral-400">
                        <span class="text-green-400">sertxu@eu-south-2</span>
                        <span class="text-neutral-600">:~$</span>
                        <span>contact</span>
                    </div>
                    <form class="space-y-4" id="contact-form" onsubmit="event.preventDefault()">
                        <div class="flex items-center gap-2">
                            <span class="text-green-400 font-medium">▶</span>
                            <input type="text" id="contact-name" placeholder="Name" class="bg-transparent border-0 border-b border-neutral-800 text-sm py-2 w-full transition-all duration-200 focus:border-b-green-400 placeholder:text-neutral-700 outline-none" />
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-green-400 font-medium">▶</span>
                            <input type="email" id="contact-email" placeholder="Email" class="bg-transparent border-0 border-b border-neutral-800 text-sm py-2 w-full transition-all duration-200 focus:border-b-green-400 placeholder:text-neutral-700 outline-none" />
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="text-green-400 font-medium pt-2">▶</span>
                            <textarea id="contact-message" rows="3" placeholder="Message" class="bg-transparent border-0 border-b border-neutral-800 text-sm py-2 w-full transition-all duration-200 focus:border-b-green-400 placeholder:text-neutral-700 outline-none resize-none"></textarea>
                        </div>
                        <div class="pt-4">
                            <button type="submit" id="contact-submit" class="inline-flex items-center gap-1 text-sm font-mono  hover:text-green-500 transition-colors">
                                <div class="pb-4 text-neutral-400">
                                    <span class="text-green-400">sertxu@eu-south-2</span>
                                    <span class="text-neutral-600">:~$</span>
                                    <span>send_message</span>
                                </div>
                            </button>
                        </div>
                    </form>

                    <hr class="border-0 border-b border-neutral-800 my-6" />

                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-neutral-400 text-sm font-mono">
                            <span class="text-green-400">$</span>
                            <span>whoami --connections</span>
                        </div>
                        <div class="space-y-2 pl-5">
                            <a href="https://github.com/sertxudev" target="_blank" rel="noopener noreferrer" class="text-neutral-300 flex items-center gap-2 text-sm font-mono transition-all duration-200 group">
                                <span class="text-green-400">▸</span>
                                <span class="group-hover:text-green-400">GitHub</span>
                                <span class="text-neutral-500">→</span>
                                <span class="text-neutral-500 group-hover:text-green-400">github.com/sertxudev</span>
                            </a>
                            <a href="https://linkedin.com/in/sertxudev" target="_blank" rel="noopener noreferrer" class="text-neutral-300 flex items-center gap-2 text-sm font-mono transition-all duration-200 group">
                                <span class="text-green-400">▸</span>
                                <span class="group-hover:text-green-400">LinkedIn</span>
                                <span class="text-neutral-500">→</span>
                                <span class="text-neutral-500 group-hover:text-green-400">linkedin.com/in/sertxudev</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ─── Contact form ───
        const contactForm = document.getElementById('contact-form');
        const submitText = document.getElementById('submit-text');

        if (contactForm) {
            contactForm.addEventListener('submit', () => {
                submitText.textContent = 'sent ✓';
                setTimeout(() => {
                    submitText.textContent = 'send_message';
                    contactForm.reset();
                }, 2500);
            });
        }
    });
</script>
