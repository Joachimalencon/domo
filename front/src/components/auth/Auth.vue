<template>
  <div class="w-full md:w-[550px] mx-auto">
    <Tabs v-model="activeTab" class="w-[550px]">
      <TabsList class="grid w-full grid-cols-2">
        <TabsTrigger value="login">
          Login
        </TabsTrigger>
        <TabsTrigger value="register">
          Register
        </TabsTrigger>
      </TabsList>
      <TabsContent value="login">
        <Login/>
      </TabsContent>
      <TabsContent value="register">
        <Register :switchTab="(tab) => activeTab = tab"/>
      </TabsContent>
    </Tabs>
  </div>
</template>

<script setup>
import {ref} from 'vue';
import {
  Tabs,
  TabsContent,
  TabsList,
  TabsTrigger,
} from '@/components/ui/tabs'
import Login from "@/components/auth/Login.vue";
import Register from "@/components/auth/Register.vue";

const activeTab = ref('login');
</script>