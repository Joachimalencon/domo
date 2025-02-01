<template>
  <Card>
    <CardHeader>
      <CardTitle class="capitalize">
        <span class="flex items-center justify-between">
          {{ cleanString(container.Names[0]) }}
          <Badge
              class="ml-2 text-xs"
              :class="container.State === 'running' ? 'bg-green-500' : 'bg-red-500' "
          > {{ container.State }}</Badge>
        </span>
      </CardTitle>
      <CardDescription class="truncate">{{ container.Image }}</CardDescription>
    </CardHeader>
    <CardContent class="flex flex-col gap-4">
      <span>Créé le {{ convertTimestamp(container.Created) }}</span>
      <span>{{ container.Status }}</span>
    </CardContent>
    <CardFooter v-if="!isDomoApi" class="flex gap-4 justify-end">
      <Button v-if="container.State === 'running'" @click="stopContainer" class="w-20 hover:bg-primary/75">Stop</Button>
      <Button v-else @click="startContainer" class="w-20 hover:bg-primary/75">Start</Button>
    </CardFooter>
  </Card>
</template>

<script setup>
import {ref} from 'vue';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import {Button} from "@/components/ui/button/index.js";
import {Badge} from '@/components/ui/badge'
import {useContainerStore} from '@/stores/containers'

const store = useContainerStore();

const props = defineProps({
  container: {type: Object, required: true},
});

const isDomoApi = ref(props.container.Names[0] === '/domo_api' || props.container.Names[0] === '/domo_db');
const loading = ref(false);

function cleanString(string) {
  if (string.startsWith('/')) {
    string = string.slice(1);
  }

  string = string.replace(/_/g, ' ');

  return string;
}

function convertTimestamp(timestamp) {
  const date = new Date(timestamp * 1000);

  const day = date.getUTCDate();
  const monthNames = [
    "janvier", "février", "mars", "avril", "mai", "juin",
    "juillet", "août", "septembre", "octobre", "novembre", "décembre"
  ];
  const month = monthNames[date.getUTCMonth()];
  const year = date.getUTCFullYear();

  const hours = String(date.getUTCHours()).padStart(2, '0');
  const minutes = String(date.getUTCMinutes()).padStart(2, '0');
  const seconds = String(date.getUTCSeconds()).padStart(2, '0');

  return `${day} ${month} ${year} à ${hours}:${minutes}:${seconds}.`;
}

function startContainer() {
  loading.value = true;
  store.startContainer(props.container.Id);
  loading.value = false;
}

function stopContainer() {
  loading.value = true;
  store.stopContainer(props.container.Id);
  loading.value = false;
}
</script>
