<nav
  x-bind:class="[
    open ? 'w-64' : 'w-2',
    darkMode
      ? 'bg-gray-900 text-gray-200'
      : 'bg-gray-1000 text-gray-900'
  ]"
  class="fixed left-0 top-0 h-full flex flex-col transition-all duration-300 ease-in-out overflow-visible"
>
  <!-- Collapse Toggle Button -->
  <button
    @click="open = !open"
    class="absolute top-1/4 right-[-1.5rem] transform translate-x-1/2 p-3 rounded-full shadow-md focus:outline-none transition-all"
    x-bind:class="
      darkMode
        ? 'bg-gray-800 text-gray-300'
        : 'bg-gray-200 text-gray-900'
    "
  >
    <template x-if="open">
      <x-bx-horizontal-left class="w-5 h-5" />
    </template>
    <template x-if="!open">
      <x-bx-horizontal-right class="w-5 h-5" />
    </template>
  </button>

  <!-- Sidebar Content (visible when expanded) -->
  <div x-show="open">
    <!-- Sidebar Header -->
    <div
      class="flex items-center justify-between h-16 px-4 border-b"
      x-bind:class="darkMode ? 'border-gray-700' : 'border-gray-300'"
    >
      <span
        class="text-lg font-semibold"
        x-bind:class="darkMode ? 'text-gray-200' : 'text-gray-900'"
      >
        Menu
      </span>
    </div>

    <!-- User Dropdown -->
    <div
      class="p-2 border-b relative"
      x-data="{ userDropdownOpen: false }"
      x-bind:class="darkMode ? 'border-gray-700' : 'border-gray-300'"
    >
      <button
        @click="userDropdownOpen = !userDropdownOpen"
        class="flex items-center w-full focus:outline-none"
      >
        <div
          class="ml-3 text-base font-medium"
          x-bind:class="darkMode ? 'text-gray-200' : 'text-gray-900'"
        >
          {{ Auth::user()->name }} {{ Auth::user()->surname }}
        </div>
        <svg
          class="fill-current h-4 w-4 ml-auto transform transition-transform duration-200"
          :class="{ 'rotate-180': userDropdownOpen }"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
        >
          <path
            fill-rule="evenodd"
            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
            clip-rule="evenodd"
          />
        </svg>
      </button>

      <!-- Dropdown Content -->
      <div
        x-show="userDropdownOpen"
        @click.away="userDropdownOpen = false"
        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 z-10 transition-all"
        x-bind:class="darkMode ? 'bg-gray-800' : 'bg-gray-200'"
      >
        <x-responsive-nav-link
          :href="route('profile.edit')"
          class="block px-4 py-2"
          x-bind:class="darkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-300'"
        >
          Profile
        </x-responsive-nav-link>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <x-responsive-nav-link
            :href="route('logout')"
            onclick="event.preventDefault(); this.closest('form').submit();"
            class="block px-4 py-2"
            x-bind:class="darkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-300'"
          >
            Log Out
          </x-responsive-nav-link>
        </form>
      </div>
    </div>

    <!-- Navigation Links -->
    <div class="mt-5 flex-1 px-2 space-y-2 flex flex-col overflow-y-auto">
      <x-nav-link
        :href="route('dashboard')"
        :active="request()->routeIs('dashboard')"
        class="block py-2 px-3 rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-300'"
      >
        {{ __('Jaunumi') }}
      </x-nav-link>
      <x-nav-link
        :href="route('tasks')"
        :active="request()->routeIs('tasks')"
        class="block py-2 px-3 rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-300'"
      >
        {{ __('Uzdevumi') }}
      </x-nav-link>
      <x-nav-link
        :href="route('competition')"
        :active="request()->routeIs('competition')"
        class="block py-2 px-3 rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-300'"
      >
        {{ __('Sacensības') }}
      </x-nav-link>
      <x-nav-link
        :href="route('submitions')"
        :active="request()->routeIs('submitions')"
        class="block py-2 px-3 rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-300'"
      >
        {{ __('Iesniegumi') }}
      </x-nav-link>
      <x-nav-link
        :href="route('leaderboard')"
        :active="request()->routeIs('leaderboard')"
        class="block py-2 px-3 rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-300'"
      >
        {{ __('Līderu saraksts') }}
      </x-nav-link>
      <x-nav-link
        :href="route('code_space')"
        :active="request()->routeIs('code_space')"
        class="block py-2 px-3 rounded-md transition"
        x-bind:class="darkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-300'"
      >
        {{ __('Trenēties') }}
      </x-nav-link>
    </div>
    
    <!-- Dark Mode Toggle Icon at the Bottom -->
    <div class="p-2 flex justify-center">
      <span @click="darkMode = !darkMode" class="cursor-pointer">
        <!-- When in light mode (darkMode is false), show the Sun icon -->
        <template x-if="!darkMode">
          <x-bi-sun class="w-6 h-6 text-gray-500" />
        </template>
        <!-- When in dark mode (darkMode is true), show the Moon icon -->
        <template x-if="darkMode">
          <x-bi-moon-fill class="w-6 h-6 text-white" />
        </template>
      </span>
    </div>
  </div>
</nav>