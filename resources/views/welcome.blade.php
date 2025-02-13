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
    /* Fade-in Animation */
    .fade-in {
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50 flex flex-col min-h-screen overflow-hidden">
  <!-- Full-screen Background Container -->
  <div id="bg" class="relative w-full min-h-screen bg-no-repeat bg-cover bg-center transition-all duration-700 filter" style="background-image: url('{{ asset('images/img1.png') }}');">
    <div class="absolute inset-0 bg-black/50 transition-opacity duration-700"></div>
    <div class="relative z-10 flex flex-col min-h-screen">
      <!-- Header with White Background -->
      <header class="relative w-full h-20 flex items-center shadow-md bg-white overflow-hidden fade-in">
        <!-- Blue Gradient Section with Rotation (Responsive; wider on laptop) -->
        <div class="absolute top-0 left-0 
                    w-1/3 sm:w-1/4 md:w-60 lg:w-56 xl:w-56 2xl:w-64 
                    h-full bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80] 
                    -skew-x-12 origin-left flex items-center 
                    pl-2 sm:pl-3 md:pl-5 lg:pl-6 xl:pl-8 2xl:pl-10 
                    -ml-2">
          <!-- Logo image hidden on mobile, text always visible -->
          <img src="{{ asset('images/VTDTlogo.png') }}" alt="Logo" class="skew-x-12 h-6 sm:h-8 w-auto mr-1 sm:mr-2 hidden md:block">
          <span class="skew-x-12 text-white font-bold text-sm sm:text-base md:text-xl p-2">
            VTDT DEVELOPERS
          </span>
        </div>
        <div class="ml-auto px-4 sm:px-6 flex items-center">
          @if (Route::has('login'))
            @auth
              <a href="{{ url('/dashboard') }}" 
                 class="px-2 sm:px-3 py-1 sm:py-2 bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80] text-white font-bold rounded-md ring-1 ring-black hover:opacity-90 transition-colors text-xs sm:text-sm md:text-base">
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
        <!-- Absolutely Positioned Hero Container (Responsive Positioning) -->
        <div id="hero-container" class="hero absolute left-2 sm:left-5 md:left-12 top-1/2 transform -translate-y-1/2 text-left max-w-full sm:max-w-3xl text-white transition-all duration-700 ease-in-out fade-in">
          <p class="text-xs sm:text-sm md:text-base uppercase font-semibold tracking-widest mb-2">Best IT Solution Provider</p>
          <h1 id="hero-title" class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
            Excellent IT Services for Your Success
          </h1>
          <p id="hero-text" class="mt-2 text-sm sm:text-base md:text-xl mb-6">
            Providing top-tier solutions with cutting-edge technology and expertise.
          </p>
          <!-- Call-to-Action Button -->
          <a href="#"
             class="inline-block px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80] text-white font-bold rounded-full shadow-lg hover:opacity-90 transition transform hover:-translate-y-1 text-xs sm:text-sm md:text-base">
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
        image: "{{ asset('images/img3.png') }}"
      }
    ];
    let currentIndex = 0;

    function changeSlide(index) {
      const oldContainer = document.getElementById("hero-container");
      const newContainer = document.createElement("div");
      newContainer.className = oldContainer.className;
      newContainer.classList.remove("translate-x-0", "opacity-100");
      newContainer.classList.add("translate-x-full", "opacity-0");

      newContainer.innerHTML = `
        <p class="text-xs sm:text-sm md:text-base uppercase font-semibold tracking-widest mb-2">Best IT Solution Provider</p>
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">${slides[index].text}</h1>
        <p class="mt-2 text-sm sm:text-base md:text-xl mb-6">${slides[index].subtext}</p>
        <a href="#"
           class="inline-block px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 bg-gradient-to-r from-[#1b1b4d] to-[#3a3a80] text-white font-bold rounded-full shadow-lg hover:opacity-90 transition transform hover:-translate-y-1 text-xs sm:text-sm md:text-base">
          Get Started
        </a>
      `;

      oldContainer.parentNode.appendChild(newContainer);
      newContainer.offsetHeight;
      newContainer.classList.remove("translate-x-full", "opacity-0");
      newContainer.classList.add("translate-x-0", "opacity-100");
      oldContainer.classList.add("-translate-x-full", "opacity-0");
      document.getElementById("bg").style.backgroundImage = `url('${slides[index].image}')`;
      document.querySelectorAll(".dot").forEach((dot, i) => {
        dot.classList.toggle("bg-blue-500", i === index);
        dot.classList.toggle("bg-white", i !== index);
      });
      setTimeout(() => {
        oldContainer.remove();
        newContainer.id = "hero-container";
      }, 700);
      currentIndex = index;
    }

    setInterval(() => {
      currentIndex = (currentIndex + 1) % slides.length;
      changeSlide(currentIndex);
    }, 12000);
  </script>
</body>
</html>