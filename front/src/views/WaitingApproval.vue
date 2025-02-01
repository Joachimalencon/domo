<template>
  <Card class="w-full md:w-[550px] mx-auto">
    <CardHeader>
      <CardTitle>Account Pending Approval</CardTitle>
    </CardHeader>

    <CardContent class="flex flex-col gap-4">
      <p>Your account has been successfully created and is awaiting approval by an administrator.</p>
      <p class="text-sm text-gray-500">
        Please check back later.
      </p>
    </CardContent>

    <CardFooter class="flex justify-end">
      <Button @click="logout" class="w-1/4 hover:bg-primary/75">
        <Loader2 class="w-4 h-4 mr-2 animate-spin" v-if="loading"/>
        <span v-else>Logout</span>
      </Button>
    </CardFooter>
  </Card>
</template>

<script setup>
import {ref} from 'vue';
import {useAuthStore} from '@/stores/auth.js';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
  CardFooter,
} from '@/components/ui/card';
import {Button} from '@/components/ui/button';
import {Loader2} from 'lucide-vue-next';
import {useRouter} from "vue-router";

const authStore = useAuthStore();

const router = useRouter();
const loading = ref(false);

async function logout() {
  loading.value = true;
  await authStore.logout();

  router.push({name: 'Login'});
  loading.value = false;
}
</script>
