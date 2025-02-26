<nav
  x-bind:class="[open ? 'w-64' : 'w-2', darkMode ? 'bg-gray-900 text-gray-200' : 'bg-gray-100 text-gray-900']"
  class="z-50 fixed left-0 top-0 h-full flex flex-col transition-all duration-300 ease-in-out overflow-visible">
  <!-- Toggle Button -->
  <button
    @click="open = !open"
    class="absolute top-1/4 transform transition-all duration-300 p-2 rounded-full shadow-md focus:outline-none
           left-[calc(100%-1rem)] md:right-[-1.5rem] md:translate-x-1/2"
    x-bind:class="darkMode ? 'bg-gray-800 text-gray-300' : 'bg-gray-200 text-gray-900'">
    <template x-if="open">
      <x-bx-horizontal-left class="w-6 h-6" />
    </template>
    <template x-if="!open">
      <x-bx-horizontal-right class="w-6 h-6" />
    </template>
  </button>

  <!-- Sidebar Content -->
  <div x-show="open" class="flex flex-col h-full">

    <!-- User Dropdown (spaced from top with pt-24) -->
    <div
      class="pl-2 pt-24 relative"
      x-data="{ userDropdownOpen: false }"
      x-bind:class="darkMode ? 'border-gray-700' : 'border-gray-300'">
      <button
        @click="userDropdownOpen = !userDropdownOpen"
        class="flex items-center w-full focus:outline-none">
        <div
          class="ml-3 text-lg font-medium"
          x-bind:class="darkMode ? 'text-gray-200' : 'text-gray-900'">
          {{ Auth::user()->name }} {{ Auth::user()->surname }}
        </div>
        <svg
          class="fill-current h-4 w-4 ml-auto transform transition-transform duration-200"
          :class="{ 'rotate-180': userDropdownOpen }"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20">
          <path
            fill-rule="evenodd"
            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 
               011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
            clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Dropdown Content -->
      <div
        x-show="userDropdownOpen"
        @click.away="userDropdownOpen = false"
        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 z-60 transition-all"
        x-bind:class="darkMode ? 'bg-gray-800' : 'bg-gray-200'">
        <x-responsive-nav-link
          :href="route('profile.edit')"
          class="block px-4 py-2 text-lg"
          x-bind:class="darkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-300'">
          Profile
        </x-responsive-nav-link>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <x-responsive-nav-link
            :href="route('logout')"
            onclick="event.preventDefault(); this.closest('form').submit();"
            class="block px-4 py-2 text-lg"
            x-bind:class="darkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-300'">
            Log Out
          </x-responsive-nav-link>
        </form>
      </div>
    </div>

    <!-- "Navigācijas pogas" Title -->
    <div class="px-3 mt-8 pb-2 text-lg" x-bind:class="darkMode ? 'text-gray-300' : 'text-gray-700'">
      Navigācijas pogas
    </div>

    <!-- Navigation Links -->
    <div class="mt-2 text-lg flex-1 px-2 space-y-2 flex flex-col overflow-y-auto">
      <x-nav-link
        :href="route('dashboard')"
        :active="request()->routeIs('dashboard')"
        class="flex items-center py-2 px-3 rounded-md transition text-lg"
        x-bind:class="darkMode ? 'hover:bg-gray-800 text-gray-300' : 'hover:bg-gray-300 text-gray-900'">
        <x-ri-news-fill class="w-5 h-5 mr-2" x-bind:class="darkMode ? 'text-gray-300' : 'text-gray-900'" />
        {{ __('Jaunumi') }}
      </x-nav-link>

      <x-nav-link
        :href="route('tasks')"
        :active="request()->routeIs('tasks')"
        class="flex items-center py-2 px-3 text-lg rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800 text-gray-300' : 'hover:bg-gray-300 text-gray-900'">
        <x-fas-tasks class="w-5 h-5 mr-2" x-bind:class="darkMode ? 'text-gray-300' : 'text-gray-900'" />
        {{ __('Uzdevumi') }}
      </x-nav-link>

      <x-nav-link
        :href="route('competition')"
        :active="request()->routeIs('competition')"
        class="flex items-center py-2 px-3 text-lg rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800 text-gray-300' : 'hover:bg-gray-300 text-gray-900'">
        <x-maki-racetrack class="w-5 h-5 mr-2" x-bind:class="darkMode ? 'text-gray-300' : 'text-gray-900'" />
        {{ __('Sacensības') }}
      </x-nav-link>

      <x-nav-link
        :href="route('submitions')"
        :active="request()->routeIs('submitions')"
        class="flex items-center py-2 px-3 text-lg rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800 text-gray-300' : 'hover:bg-gray-300 text-gray-900'">
        <x-ionicon-mail class="w-5 h-5 mr-2" x-bind:class="darkMode ? 'text-gray-300' : 'text-gray-900'" />
        {{ __('Iesniegumi') }}
      </x-nav-link>

      <x-nav-link
        :href="route('leaderboard')"
        :active="request()->routeIs('leaderboard')"
        class="flex items-center py-2 px-3 text-lg rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800 text-gray-300' : 'hover:bg-gray-300 text-gray-900'">
        <x-iconoir-leaderboard-star class="w-5 h-5 mr-2" x-bind:class="darkMode ? 'text-gray-300' : 'text-gray-900'" />
        {{ __('Līderu saraksts') }}
      </x-nav-link>

      <x-nav-link
        :href="route('code_space')"
        :active="request()->routeIs('code_space')"
        class="flex items-center py-2 px-3 text-lg rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800 text-gray-300' : 'hover:bg-gray-300 text-gray-900'">
        <x-fas-brain class="w-5 h-5 mr-2" x-bind:class="darkMode ? 'text-gray-300' : 'text-gray-900'" />
        {{ __('Trenēties') }}
      </x-nav-link>
    </div>

    <!-- Dark Mode Toggle at the Bottom -->
    <div class="p-6 pb-12 mt-auto">
      <span @click="darkMode = !darkMode" class="cursor-pointer">
        <template x-if="!darkMode">
          <x-bi-sun class="w-6 h-6 text-gray-500" />
        </template>
        <template x-if="darkMode">
          <x-bi-moon-fill class="w-6 h-6 text-white" />
        </template>
      </span>
    </div>

  </div>
</nav>