<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <card-container
        v-for="container in store.containers"
        :key="container.id"
        :container="container"
    ></card-container>
  </div>
</template>

<script setup>
import {useContainerStore} from '@/stores/containers'
import CardContainer from "@/components/containers/CardContainer.vue";

const store = useContainerStore()
</script>
