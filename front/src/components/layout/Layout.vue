<template>
  <div class="flex min-h-screen">
    <main class="flex-1 text-foreground p-8 overflow-y-auto relative">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()
</script>
