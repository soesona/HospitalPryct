<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cleveland Clinic</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<body class="bg-gray-50 min-h-screen flex flex-col">


 
<header class="fixed top-0 left-0 w-full bg-white shadow-md z-50 py-4 px-6">

  <nav class="max-w-7xl mx-auto flex items-center justify-between">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="{{ asset('img/ClevelandLogo.jpg') }}" alt="Logo" class="h-8 w-auto" />
      <span class="text-xl font-semibold text-gray-900">Hospital Cleveland</span>
    </a>
    @if (Route::has('login'))
      <nav class="flex items-center justify-end gap-4 text-sm">
        @auth
          <a href="{{ url('/dashboard') }}" 
        class="inline-block px-5 py-1.5 border border-green-600 rounded-sm bg-green-600 text-white hover:bg-green-700 leading-normal">
            Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" 
             class="inline-block px-5 py-1.5 border border-transparent rounded-sm text-gray-900 hover:border-gray-300 leading-normal">
            Iniciar sesión
          </a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" 
                class="inline-block px-5 py-1.5 border border-gray-800 rounded-sm bg-gray-800 text-white hover:bg-gray-700 shadow-sm leading-normal">
              Registrarse
            </a>
          @endif
        @endauth
      </nav>
    @endif
  </header>


  <div class="pt-16 flex-grow w-full">

<div class="flex-grow w-full">
  <div class="swiper mySwiper relative h-80 md:h-[500px] overflow-hidden">

      <div class="swiper-wrapper">

 
        <div class="swiper-slide relative">
          <img src="{{ asset('img/Clinica.jpg') }}" class="w-full h-full object-cover" alt="Slide 1">
          <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center text-white px-4">
            <h2 class="text-2xl md:text-4xl font-bold">Bienvenido a Hospital Cleveland</h2>
            <p class="text-base md:text-lg mt-2">Somos el principal centro hospitalario de Choluteca</p>
          </div>
        </div>

  
        <div class="swiper-slide relative">
          <img src="{{ asset('img/Doctores.jpg') }}" class="w-full h-full object-cover" alt="Slide 2">
          <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center text-white px-4">
            <h2 class="text-2xl md:text-4xl font-bold">Excelencia Médica con Calidez Humana</h2>
            <p class="text-base md:text-lg mt-2">Contamos con personal altamente calificado, para atenderte las 24 Horas del día</p>
          </div>
        </div>

  
        <div class="swiper-slide relative">
          <img src="{{ asset('img/CitaMedica.jpg') }}" class="w-full h-full object-cover" alt="Slide 3">
          <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center text-white px-4">
            <h2 class="text-2xl md:text-4xl font-bold">Especialidades Médicas</h2>
            <p class="text-base md:text-lg mt-2">Contamos con los mejores especialistas para atender todas tus necesidades de Salud</p>
          </div>
        </div>
      </div>


      <div class="swiper-button-next text-white"></div>
      <div class="swiper-button-prev text-white"></div>

  
      <div class="swiper-pagination"></div>
    </div>
  </div>

   <div class="container mx-auto my-10 px-4">
    <h1 class="text-3xl font-semibold text-center mb-8">Integrantes del Proyecto</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="text-center">
        <img src="{{ asset('img/Bombon.jpg') }}" alt="Integrante 1" class="rounded-full mx-auto w-36 h-36 object-cover" />
        <h3 class="mt-4 text-xl font-medium">Sthefany Sosa Enamorado</h3>
      </div>
      <div class="text-center">
        <img src="{{ asset('img/Bellota.jpg') }}" alt="Integrante 2" class="rounded-full mx-auto w-36 h-36 object-cover" />
        <h3 class="mt-4 text-xl font-medium">Yessenia Nicolle Baquedano</h3>
      </div>
      <div class="text-center">
        <img src="{{ asset('img/Burbuja.jpg') }}" alt="Integrante 3" class="rounded-full mx-auto w-36 h-36 object-cover" />
        <h3 class="mt-4 text-xl font-medium">Kenny Daniel Arias</h3>
      </div>
    </div>
  </div>
  

<footer class="w-full bg-gray-900 text-gray-100 text-center py-4 mt-auto">
  <p>© 2025 Implementación de Sistemas de Software. Todos los derechos reservados.</p>
</footer>


  <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

  <script>
    const swiper = new Swiper(".mySwiper", {
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>
</body>
</html>