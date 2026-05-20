import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import intersect from '@alpinejs/intersect'

Alpine.plugin(intersect)

Alpine.directive('reveal', (el) => {
    // el.style.opacity = '0'
    // el.style.transform = 'translateY(28px)'
    // el.style.transition = 'opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1)'

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                el.style.opacity = '1'
                el.style.transform = 'translateY(0)'
                observer.unobserve(el)
            }
        })
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' })

    observer.observe(el)
})

Livewire.start()
