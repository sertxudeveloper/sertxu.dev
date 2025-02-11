<section class="bg-dark-200 py-24">
    <div class="max-w-(--breakpoint-xl) mx-auto">
        <h2 class="text-3xl text-neutral-200 text-center font-medium uppercase font-heading">My latest posts</h2>
        <div class="border-b-2 border-ocean w-32 mx-auto mt-2 mb-10"></div>
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 px-4 lg:px-0">
            @foreach($posts as $post)
                <x-post :post="$post" />
            @endforeach
        </ul>

        <x-see-more-button :href="route('posts.index')" class="mt-10"  />
    </div>
</section>
