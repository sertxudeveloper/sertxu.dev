import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import intersect from '@alpinejs/intersect'

Alpine.plugin(intersect)

Alpine.directive('reveal', (el) => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                el.style.opacity = '1'
                el.style.transform = 'translateY(0)'
                observer.unobserve(el)
            }
        })
    }, { threshold: 0, rootMargin: '0px 0px -40px 0px' })

    observer.observe(el)
})

Alpine.directive('reveal-children', (el) => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                for (const child of el.children) {
                    child.style.opacity = '1'
                    child.style.transform = 'translateY(0)'
                }
                observer.unobserve(el)
            }
        })
    }, { threshold: 0, rootMargin: '0px 0px -40px 0px' })

    observer.observe(el)
})

Livewire.start()
