<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Семейное образование ОАНО СОШ "ПЕНАТЫ"</title>
        <meta name="description" content="Онлайн-платформа для сопровождения промежуточной аттестации, ГИА, сертификации и взаимодействия между школой, родителями и учениками.">
        <meta name="keywords" content="тесты, школа, сертификаты, обучение, Пенаты, семейное образование">
        <meta name="author" content="ОАНО СОШ 'ПЕНАТЫ'">

        <!-- OpenGraph -->
        <meta property="og:title" content="Семейное образование ОАНО СОШ 'ПЕНАТЫ'">
        <meta name="title" content="Семейное образование ОАНО СОШ 'ПЕНАТЫ'">
        <meta property="og:description" content="Онлайн-платформа для сопровождения промежуточной аттестации, ГИА, сертификации и взаимодействия между школой, родителями и учениками.">
        <meta name="description" content="Онлайн-платформа для сопровождения промежуточной аттестации, ГИА, сертификации и взаимодействия между школой, родителями и учениками.">
        <meta property="og:image" content="/meta.png">
        <meta name="image" content="/meta.png">
        <meta property="og:url" content="https://new.so-penaty.ru">
        <meta name="url" content="https://new.so-penaty.ru">
        <meta property="og:type" content="website">
        <meta name="type" content="website">


        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="robots" href="/robots.txt" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="icon" type="image/png" href="/favicon.ico">
        <link rel="manifest" href="/site.webmanifest">
        
        <!-- Scripts -->
        @verbatim
        <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "ОАНО СОШ 'ПЕНАТЫ' Семейное образование",
        "url": "https://new.so-penaty.ru",
        "logo": "https://new.so-penaty.ru/logo.png",
        "sameAs": [
            "https://t.me/penaty_school"
        ]
        }
        </script>
        @endverbatim
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
{{--        @vite(['resources/js/app.js', 'resources/css/app.css'])--}}
        @inertiaHead

         
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
