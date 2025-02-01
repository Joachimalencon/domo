<template>
  <div class="fixed top-4 right-4 flex gap-2">
    <Button @click="getContainers" class="hover:bg-primary/75">
      <RefreshCw :class="loading ? 'animate-spin' : ''"/>
    </Button>


    <Dialog class="w-1/2">
      <DialogTrigger>
        <Button class="hover:bg-primary/75">
          <UserPlus v-if="unapprovedUsers.length"/>
          <User v-else/>
        </Button>
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Users list</DialogTitle>
          <DialogDescription class="">
            <div class="flex flex-col gap-2 mt-4">
              <div v-for="user in users" :key="user" class="flex gap-2 items-center">
                <span>{{ user.username }}</span>
                <span>({{ formatDate(user.createdAt) }})</span>
                <div v-if="currentUser.id !== user.id" class="ml-auto">
                  <Button
                      v-if="!user.approved"
                      @click="approveUser(user.id)"
                      class="h-7 bg-green-500 text-black hover:bg-green-500/75 w-20"
                      :disabled="loadingUsers[user.id]"
                  >
                    <Loader2 v-if="loadingUsers[user.id]" class="animate-spin mx-auto"/>
                    <span v-else>Approve</span>
                  </Button>
                  <Button
                      v-else
                      @click="revokeUser(user.id)"
                      class="h-7 bg-red-500 text-black hover:bg-red-500/75 w-20"
                      :disabled="loadingUsers[user.id]"
                  >
                    <Loader2 v-if="loadingUsers[user.id]" class="animate-spin mx-auto"/>
                    <span v-else>Revoke</span>
                  </Button>
                </div>
              </div>
            </div>
          </DialogDescription>
        </DialogHeader>
      </DialogContent>
    </Dialog>


    <Button @click="logout" class="hover:bg-primary/75">
      <LogOut/>
    </Button>
  </div>

  <div class="flex justify-center align-center">
    <list-containers class="mt-10"></list-containers>
  </div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue';
import {LogOut, RefreshCw, User, UserPlus, Loader2} from 'lucide-vue-next'
import {Button} from '@/components/ui/button';
import {useContainerStore} from '@/stores/containers';
import {useUserStore} from "@/stores/users.js";
import ListContainers from "@/components/containers/ListContainers.vue";
import {useAuthStore} from "@/stores/auth.js";
import {useRouter} from "vue-router";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'

const containerStore = useContainerStore();
const userStore = useUserStore();
const authStore = useAuthStore();
const router = useRouter();

const loading = ref(false);
const loadingUsers = ref({});

onMounted(async () => {
  if (authStore.isAuthenticated) {
    await authStore.getMe();
    await containerStore.getContainers()
    await userStore.getUsers()
  }
})

async function getContainers() {
  loading.value = true;
  await containerStore.getContainers();
  loading.value = false;
}

async function logout() {
  loading.value = true;
  await authStore.logout();

  router.push({name: 'Login'});
  loading.value = false;
}

const currentUser = computed(() => {
  return authStore.user
});

const users = computed(() => {
  return userStore.users
});

const unapprovedUsers = computed(() => {
  return userStore.users.filter(user => !user.approved);
})

async function approveUser(id) {
  loadingUsers.value[id] = true
  await userStore.approveUser(id);
  await userStore.getUsers();
  loadingUsers.value[id] = false
}

async function revokeUser(id) {
  loadingUsers.value[id] = true
  await userStore.revokeUser(id);
  await userStore.getUsers();
  loadingUsers.value[id] = false
}

function formatDate(isoDate) {
  const date = new Date(isoDate);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();

  return `${day}/${month}/${year}`;
}
</script>

<style>
body {
  background: linear-gradient(45deg, #020817, #5E2750);
}
</style>
