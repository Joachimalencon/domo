<template>
  <Card>
    <CardHeader>
      <CardTitle>Login</CardTitle>
    </CardHeader>

    <form @submit.prevent="onSubmit">
      <CardContent class="flex flex-col gap-4">
        <FormField name="username" v-slot="{ field, errorMessage }">
          <FormItem>
            <FormLabel>Username</FormLabel>
            <FormControl>
              <Input v-bind="field" placeholder="Username" />
            </FormControl>
            <FormMessage v-if="errorMessage">{{ errorMessage }}</FormMessage>
          </FormItem>
        </FormField>

        <FormField name="password" v-slot="{ field, errorMessage }">
          <FormItem>
            <FormLabel>Password</FormLabel>
            <FormControl>
              <Input type="password" v-bind="field" placeholder="Password" />
            </FormControl>
            <FormMessage v-if="errorMessage">{{ errorMessage }}</FormMessage>
          </FormItem>
        </FormField>
        <span v-if="serverError" class="text-red-600 mt-2">
          {{ serverError }}
        </span>
      </CardContent>

      <CardFooter class="flex justify-end">
        <Button type="submit" class="w-1/4 hover:bg-primary/75">
          <Loader2 class="w-4 h-4 mr-2 animate-spin" v-if="loading" />
          <span v-else>Login</span>
        </Button>
      </CardFooter>
    </form>
  </Card>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as z from 'zod';

import { useAuthStore } from '@/stores/auth.js';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
  CardFooter,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
  FormField,
  FormItem,
  FormLabel,
  FormControl,
  FormMessage,
} from '@/components/ui/form';
import { Loader2 } from 'lucide-vue-next';

const formSchema = toTypedSchema(
    z.object({
      username: z.string().nonempty('Username is required.'),
      password: z.string().nonempty('Password is required.'),
    })
);

const form = useForm({
  validationSchema: formSchema,
});

const router = useRouter();
const authStore = useAuthStore();

const loading = computed(() => authStore.isLoading);
const serverError = ref('');

const onSubmit = form.handleSubmit(async (values) => {
  serverError.value = '';
  try {
    const response = await authStore.login(values);

    if (response.error === true) {
      serverError.value = response.message || 'An error occurred while logging in.';
      return;
    }

    if (authStore.isAuthenticated && authStore.isApproved) {
      router.push({ name: 'Home' });
    } else {
      router.push({ name: 'WaitingApproval' });
    }
  } catch (error) {
    console.error('Erreur lors de la connexion :', error);
  }
});
</script>
