<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
        <meta name="description" content="Laravel - The PHP Framework For Web Artisans">

        <!-- Favicons -->
        <link rel="icon" sizes="192x192" href="{{ asset('icon@192.png') }}">
        <link rel="icon" sizes="128x128" href="{{ asset('icon@128.png') }}">

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
        <link rel="icon" sizes="any" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <link rel="apple-touch-icon" sizes="76x76"   href="{{ asset('icon@76.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('icon@120.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icon@152.png') }}">
        <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('icon@167.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon@180.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite('resources/css/app.css')
    </head>
    <body class="font-sans antialiased bg-zinc-100">
        <div class="grid grid-cols-4 gap-8 mx-16">
            <div class="h-screen pt-16">
                <div class="w-48 rounded-3xl overflow-hidden mx-auto drop-shadow-lg">
                    <img src="https://gravatar.com/avatar/{{ md5('sergioperis2@gmail.com') }}?s=400&d=blank" alt="Sergio Peris">
                </div>

                <div class="space-y-6 bg-white rounded-3xl pt-32 pb-16 -mt-24">
                    <h1 class="text-3xl font-medium text-center">Sergio Peris</h1>

                    <div class="flex space-x-4 justify-center">
                        <a href="https://x.com/sertxudev" class="bg-gray-100 flex h-10 items-center justify-center rounded-lg w-10 p-2">
                            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.7497 3H20.8179L14.1165 10.624L22 21H15.8288L10.9917 14.7087L5.46371 21H2.39113L9.55758 12.8438L2 3H8.32768L12.6953 8.75048L17.7497 3ZM16.6719 19.174H18.3711L7.402 4.73077H5.57671L16.6719 19.174Z" fill="black"/>
                            </svg>
                        </a>
                        <a href="https://github.com/sertxudev" class="bg-gray-100 flex h-10 items-center justify-center rounded-lg w-10 p-2">
                            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.68952 18.104C8.68952 18.1867 8.59677 18.2529 8.47984 18.2529C8.34677 18.2653 8.25403 18.1991 8.25403 18.104C8.25403 18.0213 8.34677 17.9551 8.46371 17.9551C8.58468 17.9427 8.68952 18.0089 8.68952 18.104ZM7.43548 17.9179C7.40726 18.0006 7.4879 18.0957 7.60887 18.1205C7.71371 18.1619 7.83468 18.1205 7.85887 18.0378C7.88306 17.9551 7.80645 17.86 7.68548 17.8228C7.58064 17.7938 7.46371 17.8352 7.43548 17.9179ZM9.21774 17.8476C9.10081 17.8765 9.02016 17.9551 9.03226 18.0502C9.04435 18.1329 9.14919 18.1867 9.27016 18.1577C9.3871 18.1288 9.46774 18.0502 9.45564 17.9675C9.44355 17.8889 9.33468 17.8352 9.21774 17.8476ZM11.871 2C6.27823 2 2 6.35477 2 12.0908C2 16.6772 4.81452 20.6019 8.83468 21.9832C9.35081 22.0783 9.53226 21.7516 9.53226 21.4827C9.53226 21.2263 9.52016 19.812 9.52016 18.9435C9.52016 18.9435 6.69758 19.5638 6.10484 17.7111C6.10484 17.7111 5.64516 16.5076 4.98387 16.1975C4.98387 16.1975 4.06048 15.5482 5.04839 15.5606C5.04839 15.5606 6.05242 15.6433 6.60484 16.6276C7.4879 18.2239 8.96774 17.7649 9.54435 17.4919C9.6371 16.8302 9.89919 16.3712 10.1895 16.0982C7.93548 15.8418 5.66129 15.5068 5.66129 11.5284C5.66129 10.3911 5.96774 9.82039 6.6129 9.09253C6.50806 8.82372 6.16532 7.71538 6.71774 6.28447C7.56048 6.01565 9.5 7.40108 9.5 7.40108C10.3065 7.16948 11.1734 7.04955 12.0323 7.04955C12.8911 7.04955 13.7581 7.16948 14.5645 7.40108C14.5645 7.40108 16.504 6.01152 17.3468 6.28447C17.8992 7.71951 17.5565 8.82372 17.4516 9.09253C18.0968 9.82453 18.4919 10.3952 18.4919 11.5284C18.4919 15.5192 16.1169 15.8377 13.8629 16.0982C14.2339 16.4249 14.5484 17.0453 14.5484 18.0171C14.5484 19.4108 14.5363 21.1354 14.5363 21.4745C14.5363 21.7433 14.7218 22.07 15.2339 21.9749C19.2661 20.6019 22 16.6772 22 12.0908C22 6.35477 17.4637 2 11.871 2ZM5.91935 16.2636C5.86694 16.305 5.87903 16.4001 5.94758 16.4787C6.0121 16.5449 6.10484 16.5738 6.15726 16.52C6.20968 16.4787 6.19758 16.3836 6.12903 16.305C6.06452 16.2388 5.97177 16.2099 5.91935 16.2636ZM5.48387 15.9287C5.45565 15.9824 5.49597 16.0486 5.57661 16.0899C5.64113 16.1313 5.72177 16.1189 5.75 16.061C5.77823 16.0072 5.7379 15.9411 5.65726 15.8997C5.57661 15.8749 5.5121 15.8873 5.48387 15.9287ZM6.79032 17.4009C6.72581 17.4547 6.75 17.5788 6.84274 17.6573C6.93548 17.7524 7.05242 17.7649 7.10484 17.6987C7.15726 17.6449 7.13306 17.5209 7.05242 17.4423C6.96371 17.3472 6.84274 17.3348 6.79032 17.4009ZM6.33065 16.793C6.26613 16.8343 6.26613 16.9419 6.33065 17.037C6.39516 17.1321 6.50403 17.1735 6.55645 17.1321C6.62097 17.0783 6.62097 16.9708 6.55645 16.8757C6.5 16.7806 6.39516 16.7392 6.33065 16.793Z" fill="black"/>
                            </svg>
                        </a>
                        <a href="https://linkedin.com/in/sertxudev" class="bg-gray-100 flex h-10 items-center justify-center rounded-lg w-10 p-2">
                            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.47679 22H2.33036V8.64702H6.47679V22ZM4.40134 6.82555C3.07545 6.82555 2 5.72732 2 4.40139C2 3.7645 2.253 3.1537 2.70334 2.70335C3.15367 2.253 3.76446 2 4.40134 2C5.03821 2 5.649 2.253 6.09934 2.70335C6.54968 3.1537 6.80268 3.7645 6.80268 4.40139C6.80268 5.72732 5.72679 6.82555 4.40134 6.82555ZM21.9955 22H17.858V15.4999C17.858 13.9507 17.8268 11.9641 15.7022 11.9641C13.5464 11.9641 13.2161 13.6471 13.2161 15.3882V22H9.07411V8.64702H13.0509V10.4685H13.1089C13.6625 9.41936 15.0147 8.31219 17.0321 8.31219C21.2286 8.31219 22 11.0756 22 14.665V22H21.9955Z" fill="black"/>
                            </svg>
                        </a>
                    </div>


                </div>
            </div>
            <div class="h-screen col-span-3 bg-blue-100">
                <h2>About me</h2>

                <p>

                </p>
            </div>
        </div>
    </body>
</html>
