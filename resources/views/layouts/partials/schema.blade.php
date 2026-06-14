<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "WebSite",
    "url": "{{ url('/') }}",
    "name": "Sergio Peris (sertxu.dev)",
    "description": "{{ $description ?? 'Full-Stack Developer & SysAdmin building reliable systems from Xàtiva, Valencia.' }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('posts.index') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Person",
    "name": "Sergio Peris",
    "alternateName": "Sertxu",
    "jobTitle": "Full-Stack Developer & SysAdmin",
    "url": "{{ url('/') }}",
    "sameAs": [
        "https://github.com/sertxudev",
        "https://linkedin.com/in/sertxudev"
    ]
}
</script>

@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
    <script type="application/ld+json">
        {
        "@@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
        @foreach($breadcrumbs as $index => $crumb)
            {
                "@type": "ListItem",
                "position": {{ $index + 1 }},
            "name": "{{ $crumb['name'] }}",
            "item": "{{ $crumb['url'] }}"
        }@if(!$loop->last),@endif
        @endforeach
        ]
    }
    </script>
@endif

@if(isset($schemaArticle))
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $schemaArticle['headline'] }}",
        "description": "{{ $schemaArticle['description'] }}",
        @isset($schemaArticle['image'])
        "image": "{{ $schemaArticle['image'] }}",
        @endif
        "datePublished": "{{ $schemaArticle['datePublished'] }}",
        "dateModified": "{{ $schemaArticle['dateModified'] }}",
        "author": {
            "@type": "Person",
            "name": "Sergio Peris",
            "url": "{{ url('/') }}"
        },
        "publisher": {
            "@type": "Person",
            "name": "Sergio Peris",
            "url": "{{ url('/') }}"
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ $schemaArticle['url'] }}"
        }
    }
    </script>
@endif
