
<template>
  <UApp>
    <main class="font-mono">
      <div class="flex justify-between w-full border-8 border-yellow-800  px-3 py-2 bg-yellow-100/90 text-pink-800 text-2xl">
        <RouterLink :to="{name: 'logIn'}" class="w-full border-4 border-yellow-700  px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-2xl">
          Log In
        </RouterLink>
        <RouterLink :to="{name: 'homepage'}" class="w-full border-4 border-yellow-700  px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-2xl">
          Home
        </RouterLink>
        <RouterLink :to="{name: 'about'}" class="w-full border-4 border-yellow-700 px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-2xl">
          About
        </RouterLink>
        <RouterLink :to="{name: 'todo'}" class="w-full border-4 border-yellow-700 px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-2xl">To Do List</RouterLink>
      </div>
      <RouterView/>
    </main>
  </UApp>
</template>


