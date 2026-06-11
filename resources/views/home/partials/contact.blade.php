<!-- Contact -->
<section id="contact" x-data class="py-28 md:py-36 border-t border-neutral-900">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center" x-reveal>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-stone-300">Contact</h2>
            <span class="bg-coral inline-block h-0.5 rounded-full w-12 mt-4"></span>
            <p class="text-stone-400 text-base mt-3">
                Want to get in touch? Let's talk!
            </p>
        </div>

        <div class="mt-10 max-w-3xl mx-auto" x-reveal>
            <div class="bg-neutral-900 border border-neutral-800 rounded-lg overflow-hidden">
                <div class="bg-neutral-950/50 px-4 py-3 flex items-center gap-2 border-b border-neutral-800">
                    <span class="inline-block size-3 rounded-full bg-red-400"></span>
                    <span class="inline-block size-3 rounded-full bg-amber-400"></span>
                    <span class="inline-block size-3 rounded-full bg-green-500"></span>
                </div>
                <div class="p-6 text-sm font-mono">
                    <div class="pb-4 text-neutral-400">
                        <span class="text-green-400">sertxu@eu-south-2</span>
                        <span class="text-neutral-600">:~$</span>
                        <span>contact</span>
                    </div>

                    <form id="contact-form" method="post" action="{{ route('contact.store') }}">
                        <label for="contact-name" class="flex items-center gap-2 mb-2">
                            <span class="text-neutral-500 text-[10px]">▶</span>
                            <span class="text-neutral-300">What's your name?</span>
                        </label>
                        <input type="text" id="contact-name" name="name" class="bg-transparent border-0 border-b border-neutral-800 text-sm py-2 w-full transition-all duration-200 focus:border-b-coral placeholder:text-neutral-700 outline-none" />

                        <div class="flex items-center gap-2 mt-6 mb-2">
                            <span class="text-neutral-500 text-[10px]">▶</span>
                            <span class="text-neutral-300">What's your email?</span>
                        </div>
                        <input type="email" id="contact-email" name="email" class="bg-transparent border-0 border-b border-neutral-800 text-sm py-2 w-full transition-all duration-200 focus:border-b-coral placeholder:text-neutral-700 outline-none" />

                        <div class="flex items-center gap-2 mt-6 mb-2">
                            <span class="text-neutral-500 text-[10px]">▶</span>
                            <span class="text-neutral-300">What do you want to talk about?</span>
                        </div>
                        <textarea id="contact-message" name="message" rows="3" class="bg-transparent border-0 border-b border-neutral-800 text-sm py-2 w-full transition-all duration-200 focus:border-b-coral placeholder:text-neutral-700 outline-none resize-none"></textarea>

                        <div class="flex items-center gap-2 mt-6 mb-2">
                            <span class="text-neutral-500 text-[10px]">▶</span>
                            <span class="text-neutral-300">Is the information correct? [y/n]</span>
                        </div>
                        <input type="text" id="contact-correct" name="correct" class="bg-transparent border-0 border-b border-neutral-800 text-sm py-2 w-full transition-all duration-200 focus:border-b-coral placeholder:text-neutral-700 outline-none" />

                        <div class="flex justify-between items-start mt-4">
                            <div class="cf-turnstile" data-sitekey="{{ config()->string('services.turnstile.site_key') }}"></div>

                            <button type="submit" class="bg-ocean text-white text-sm transition-all cursor-pointer px-2 py-1 hover:bg-ocean/80">
                                Send message
                            </button>
                        </div>

                        @error('name')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror

                        @error('email')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror

                        @error('message')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror

                        @error('correct')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror

                        @error('cf-turnstile-response')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror

                        @session('success')
                            <div class="text-green-500 text-sm mt-2">{{ session('success') }}</div>
                        @endsession
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-10 max-w-3xl mx-auto" x-reveal>
            <div class="bg-neutral-900 border border-neutral-800 rounded-lg overflow-hidden">
                <div class="bg-neutral-950/50 px-4 py-3 flex items-center gap-2 border-b border-neutral-800">
                    <span class="inline-block size-3 rounded-full bg-red-400"></span>
                    <span class="inline-block size-3 rounded-full bg-amber-400"></span>
                    <span class="inline-block size-3 rounded-full bg-green-500"></span>
                </div>
                <div class="p-6 text-sm font-mono">
                    <div class="pb-4 text-neutral-400">
                        <span class="text-green-400">sertxu@eu-south-2</span>
                        <span class="text-neutral-600">:~$</span>
                        <span>whoami --connections</span>
                    </div>
                    <div class="space-y-2 pl-5">
                        <a href="https://github.com/sertxudev" target="_blank" rel="noopener noreferrer" class="text-neutral-300 flex items-center gap-2 text-sm font-mono *:transition-all duration-200 group">
                            <span class="text-neutral-500 text-[10px]">▶</span>
                            <span>GitHub</span>
                            <span class="text-neutral-500">→</span>
                            <span class="text-neutral-500 group-hover:text-coral">github.com/sertxudev</span>
                        </a>

                        <a href="https://linkedin.com/in/sertxudev" target="_blank" rel="noopener noreferrer" class="text-neutral-300 flex items-center gap-2 text-sm font-mono *:transition-all duration-200 group">
                            <span class="text-neutral-500 text-[10px]">▶</span>
                            <span>LinkedIn</span>
                            <span class="text-neutral-500">→</span>
                            <span class="text-neutral-500 group-hover:text-coral">linkedin.com/in/sertxudev</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
