<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <script>
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.classList.remove('dark');
    } else {
      document.documentElement.classList.add('dark');
    }
  </script>
  <style>
  /* Base scrollbar styling (for non-dark mode) */
  ::-webkit-scrollbar {
    width: 8px;
  }
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }
  ::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
  }

  /* Force dark scrollbar styling when dark mode is active.
     Even though dark mode elsewhere gives white backgrounds (via dark:bg-white),
     we want the scrollbar elements to have dark colors. */
  html.dark ::-webkit-scrollbar-track {
    background: #111827 !important;
  }
  html.dark ::-webkit-scrollbar-thumb {
    background-color: #111827 !important;
  }

  /* Firefox scrollbar styling */
  html {
    scrollbar-width: thin;
    scrollbar-color: #888 #f1f1f1;
  }
  html.dark {
    scrollbar-color: #4a5568 #111827 !important;
  }
</style>
</head>

<body class="font-sans antialiased 
             bg-gray-100 dark:bg-gray-900 
             text-gray-900 dark:text-gray-200">

  <div x-data="{
          open: true,
          darkMode: localStorage.getItem('theme')
                     ? localStorage.getItem('theme') === 'dark'
                     : true
      }"
    x-init="
          // Immediately reflect darkMode on <html>
          document.documentElement.classList.toggle('dark', darkMode);
          // Watch for changes and update localStorage and <html>
          $watch('darkMode', value => {
              localStorage.setItem('theme', value ? 'dark' : 'light');
              document.documentElement.classList.toggle('dark', value);
          });
      "
    class="flex h-screen transition-all">

    <!-- Sidebar (navigation) -->
    @include('layouts.navigation')

    <!-- Main Content Area -->
    <div :class="{ 'md:ml-64': open, 'md:ml-2': !open }"
      class="flex-1 transition-all duration-300 overflow-auto p-6">

      <!-- Framed content box -->
      <div class="border border-gray-300 dark:border-gray-700 
                  rounded-lg p-6 
                  bg-white dark:bg-gray-800 
                  text-gray-900 dark:text-gray-200 
                  shadow-lg min-h-full">

        <!-- Page Heading -->
        @isset($header)
        <header class="mb-4 p-4 text-gray-900 dark:text-gray-100 ">
          <div class="max-w-7xl mx-auto font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $header }}
          </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
          {{ $slot }}
        </main>
      </div>
    </div>
  </div>
</body>

</html>