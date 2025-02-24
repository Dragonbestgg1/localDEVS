<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel Login</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @else
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
  @endif

  <style>
    /* Slide in from right and exit to left animation for the hero container */
    @keyframes slideHero {
      0% {
        transform: translate(calc(100vw - 50%), -50%);
        opacity: 0;
      }
      20% {
        transform: translate(-50%, -50%);
        opacity: 1;
      }
      80% {
        transform: translate(-50%, -50%);
        opacity: 1;
      }
      100% {
        transform: translate(calc(-100vw - 50%), -50%);
        opacity: 0;
      }
    }
    .slide-hero {
      animation: slideHero 8s linear;
    }
  </style>
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50 flex flex-col min-h-screen overflow-x-hidden relative">
  <!-- Background Wrapper (with overflow hidden) -->
  <div id="bg-wrapper" class="absolute inset-0 overflow-x-hidden -z-10">
    <!-- Full-screen Background Container -->
    <div id="bg" class="relative w-full min-h-screen bg-no-repeat bg-cover bg-center transition-all duration-700 filter"
         style="background-image: url('{{ asset('images/img1.png') }}');">
      <!-- Dark overlay -->
      <div class="absolute inset-0 bg-black/50 transition-opacity duration-700"></div>
      <!-- <img src="{{ asset('images/tech.png') }}" alt="Tech Overlay 1"
               class="w-64 h-64 object-contain absolute top-64 right-32"
               style="transform: rotate(180deg); z-index: 40;" /> -->
      <!-- Triangles container in the top right -->
      <div class="absolute top-20 right-[-15px] pointer-events-none z-[5]">
        <div class="relative w-20 md:w-32">
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 1"
               class="w-full h-auto object-contain absolute top-0 left-0"
               style="transform: rotate(180deg); z-index: 40;" />
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 2"
               class="w-full h-auto object-contain absolute top-0 left-[20px]"
               style="transform: rotate(180deg); z-index: 30;" />
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 3"
               class="w-full h-auto object-contain absolute top-0 left-[40px]"
               style="transform: rotate(180deg); z-index: 20;" />
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 4"
               class="w-full h-auto object-contain absolute top-0 left-[60px]"
               style="transform: rotate(180deg); z-index: 10;" />
        </div>
      </div>

      <!-- Triangles container in the bottom left -->
      <div class="absolute bottom-64 left-[-60px] pointer-events-none z-[5]">
        <div class="relative w-20 md:w-32">
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 1"
               class="w-full h-auto object-contain absolute top-0 left-0" style="z-index: 40;" />
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 2"
               class="w-full h-auto object-contain absolute top-0 left-[20px]" style="z-index: 30;" />
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 3"
               class="w-full h-auto object-contain absolute top-0 left-[40px]" style="z-index: 20;" />
          <img src="{{ asset('images/triangle.png') }}" alt="Triangle Overlay 4"
               class="w-full h-auto object-contain absolute top-0 left-[60px]" style="z-index: 10;" />
        </div>
      </div>

      <!-- Wires image: smaller on mobile, larger on md screens -->
      <div class="absolute bottom-10 right-0 z-5">
        <img src="{{ asset('images/wires.png') }}" alt="Wires Test"
             class="w-32 md:w-64 h-auto object-contain" />
      </div>
    </div>
  </div>

  <!-- Main Content Container (z-10) -->
  <div id="main-content" class="relative z-10 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="relative w-full h-20 flex items-center shadow-md bg-white overflow-hidden fade-in">
      <div class="absolute top-0 left-0 
                  w-1/3 sm:w-1/4 md:w-60 lg:w-56 xl:w-56 2xl:w-64 
                  h-full bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80] 
                  -skew-x-12 origin-left flex items-center 
                  pl-2 sm:pl-3 md:pl-5 lg:pl-6 xl:pl-8 2xl:pl-10 -ml-2">
        <img src="{{ asset('images/VTDTlogo.png') }}" alt="Logo"
             class="skew-x-12 h-6 sm:h-8 w-auto mr-1 sm:mr-2 hidden md:block" />
        <span class="skew-x-12 text-white font-bold text-sm sm:text-base md:text-xl p-2">
          VTDT DEVELOPERS
        </span>
      </div>
      <div class="ml-auto px-4 sm:px-6 flex items-center">
        @if (Route::has('login'))
          @auth
            <a href="{{ url('/dashboard') }}"
               class="px-2 sm:px-3 py-1 sm:py-2 bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80]
                      text-white font-bold rounded-md ring-1 ring-black hover:opacity-90 transition-colors
                      text-xs sm:text-sm md:text-base">
              Dashboard
            </a>
          @else
            <a href="/auth/google" class="text-black text-2xl flex items-center gap-2">
              <x-entypo-login class="w-8 h-8 text-[#282560] fill-current" />
            </a>
          @endauth
        @endif
      </div>
    </header>

    <!-- Hero Section -->
    <main class="relative flex flex-1">
      <!-- Single Hero Container (stays inside main-content) -->
      <div id="hero-container"
           class="hero slide-hero absolute top-1/2 left-1/2 text-left max-w-full sm:max-w-3xl text-white transition-all duration-700 ease-in-out fade-in z-20 p-4 mx-4">
        <p class="text-xs sm:text-sm md:text-base uppercase font-semibold tracking-widest mb-4">
          Best IT Solution Provider
        </p>
        <h1 id="hero-title" class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-6">
          Excellent IT Services for Your Success
        </h1>
        <p id="hero-text" class="mt-6 text-xs sm:text-sm md:text-base mb-6">
          Providing top-tier solutions with cutting-edge technology and expertise.
        </p>
        <a href="#"
           class="inline-block px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80]
                  text-white font-bold rounded-full shadow-lg hover:opacity-90 transition transform hover:-translate-y-1 text-xs sm:text-sm md:text-base">
          Get Started
        </a>
      </div>
    </main>

    <!-- Dot Indicators -->
    <div class="absolute bottom-6 sm:bottom-10 left-1/2 transform -translate-x-1/2 flex space-x-2 sm:space-x-3 z-10">
      <div class="w-2 sm:w-3 h-2 sm:h-3 bg-white rounded-full cursor-pointer dot active" onclick="changeSlide(0)"></div>
      <div class="w-2 sm:w-3 h-2 sm:h-3 bg-white rounded-full cursor-pointer dot" onclick="changeSlide(1)"></div>
      <div class="w-2 sm:w-3 h-2 sm:h-3 bg-white rounded-full cursor-pointer dot" onclick="changeSlide(2)"></div>
    </div>
  </div>

  <script>
    const slides = [
      {
        text: "Innovative Solutions for Your Business",
        subtext: "Empowering your business with next-gen digital transformation.",
        image: "{{ asset('images/img1.png') }}"
      },
      {
        text: "Your Trusted Technology Partner",
        subtext: "Delivering seamless IT services for a smarter future.",
        image: "{{ asset('images/img2.png') }}"
      },
      {
        text: "Cutting-Edge Digital Solutions",
        subtext: "Bringing your vision to life with advanced technology.",
        image: "{{ asset('images/IMG3.png') }}"
      }
    ];
    let currentIndex = 0;

    function changeSlide(index) {
      const heroContainer = document.getElementById("hero-container");
      if (heroContainer) {
        // Update the inner HTML without removing the container
        heroContainer.innerHTML = `
          <p class="text-xs sm:text-sm md:text-base uppercase font-semibold tracking-widest mb-4">Best IT Solution Provider</p>
          <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-6">
            ${slides[index].text}
          </h1>
          <p class="mt-6 text-xs sm:text-sm md:text-base mb-6">
            ${slides[index].subtext}
          </p>
          <a href="#"
             class="inline-block px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80]
                    text-white font-bold rounded-full shadow-lg hover:opacity-90 transition transform hover:-translate-y-1 text-xs sm:text-sm md:text-base">
            Get Started
          </a>
        `;
        // Re-trigger the slide animation by forcing reflow
        heroContainer.classList.remove("slide-hero");
        void heroContainer.offsetWidth;
        heroContainer.classList.add("slide-hero");
      }

      // Update the background image
      document.getElementById("bg").style.backgroundImage = `url('${slides[index].image}')`;

      // Update dot indicators
      document.querySelectorAll(".dot").forEach((dot, i) => {
        dot.classList.toggle("bg-blue-500", i === index);
        dot.classList.toggle("bg-white", i !== index);
      });

      currentIndex = index;
    }

    // Automatically cycle slides every 8 seconds
    setInterval(() => {
      currentIndex = (currentIndex + 1) % slides.length;
      changeSlide(currentIndex);
    }, 8000);
  </script>
</body>
</html>