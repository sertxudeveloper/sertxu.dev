<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <title>{{ $post->title }}</title>

    <!-- Styles -->
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased bg-dark-300 relative" style="width: 640px; height: 360px;">
<svg class="w-full h-full" viewBox="0 0 640 360" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M623.693 331.788C623.443 332.244 622.777 332.241 622.531 331.783L616.718 320.95C616.487 320.519 616.805 320 617.301 320L629.045 320C629.543 320 629.861 320.524 629.625 320.955L623.693 331.788Z" fill="#FF3047"/>
    <path d="M600.011 338.212C600.261 337.756 600.927 337.759 601.173 338.217L606.986 349.05C607.218 349.481 606.9 350 606.404 350H594.66C594.161 350 593.843 349.476 594.08 349.045L600.011 338.212Z" fill="#FF3047"/>
    <path d="M594.134 320.947C593.906 320.516 594.224 320 594.718 320H613.78C614.024 320 614.247 320.132 614.361 320.343L629.922 349.047C630.156 349.479 629.838 350 629.34 350H609.928C609.683 350 609.458 349.866 609.344 349.652L594.134 320.947Z" fill="#0035FF"/>
    <path d="M526 213.02C525.542 213.871 524.319 213.866 523.867 213.011L412.291 1.77246C411.866 0.967767 412.449 -7.23402e-09 413.36 0L638.789 1.79128e-06C639.704 1.79855e-06 640.288 0.976372 639.854 1.7814L526 213.02Z" fill="#FF3047"/>
    <path d="M382.233 0.639737C382.231 0.636973 382.232 0.638307 382.23 0.635552C382.02 0.244225 381.611 0 381.166 0H0V360H574.5C574.5 360 541.947 299.685 521.159 261C466.622 159.511 382.28 0.729418 382.233 0.639737Z" fill="#0035FF"/>
</svg>

<div class="absolute top-0 left-0 h-full w-full flex items-center px-5 max-w-[460px] ">
    <h1 class="text-white text-[2.5rem] font-medium text-left leading-tight line-clamp-6">{{ $post->title }}</h1>
</div>
</body>
</html>
