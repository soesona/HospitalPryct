<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <style>
            /* Forzar colores para modo claro */
            * {
                color: #111827 !important; /* text-gray-900 */
            }
            input, textarea, select {
                color: #111827 !important;
                background-color: white !important;
            }
            /* Asegurar que los links se vean bien */
            a {
                color: #3b82f6 !important; /* blue-500 para links */
            }
            a:hover {
                color: #1d4ed8 !important; /* blue-700 para hover */
            }

            /* Checkbox visible con borde */
            input[type="checkbox"] {
                border: 2px solid #111827 !important;
                background-color: white !important;
            }

            /* Botón celeste */
            button[type="submit"], .flux-button-primary {
                background-color: #06b6d4 !important; /* celeste */
                border-color: #06b6d4 !important;
                color: white !important;
            }
            
            /* Hover del botón celeste */
            button[type="submit"]:hover, .flux-button-primary:hover {
                background-color: #0891b2 !important; /* celeste más oscuro */
                border-color: #0891b2 !important;
            }
            
            /* Asegurar que los links se vean bien */
            a {
                color: #3b82f6 !important; /* blue-500 para links */
            }
            a:hover {
                color: #1d4ed8 !important; /* blue-700 para hover */
            }
        </style>
    </head>
    <body class="min-h-screen bg-white text-gray-900 antialiased">
        <div class="bg-white text-gray-900 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2 text-gray-900">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium text-gray-900" wire:navigate>
                    <span class="flex mb-1 items-center justify-center rounded-md">
                        <img src="{{ asset('img/ClevelandLogo.jpg') }}" alt="{{ config('app.name', 'Mi App') }}" width="100" height="auto" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6 text-gray-900">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>