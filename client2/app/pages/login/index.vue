<script setup lang="ts">
import { useAuthStore } from "~/store/auth";
import {z} from "zod";

const auth = useAuthStore();
const email = ref<string>('');
const password = ref<string>('');
const toast = useToast();
const router = useRouter();

const schema = z.object({
  email: z.email('invalid email'),
  password: z.string()
})

type Schema = z.output<typeof schema>

const state = reactive<Partial<Schema>>({
  email: undefined,
  password: undefined
})

function authenticate() {
  const result = schema.safeParse({email: email.value, password: password.value});
  if (!result.success) {
    toast.add({
      title: z.flattenError(result.error).fieldErrors.email?.at(0),
      description: 'please enter an email address',
      color: 'warning',
    })
  } else {
    auth.login(email.value, password.value)
        .then(() => {
          router.push({name:'todo'});
        })
        .catch(() => {})
        .finally(() => {})
  }
}
</script>

<template>
  <main class="flex items-center justify-center h-screen bg-cover bg-center font-mono">
    <UForm
        :schema="schema"
        :state="state"
        class="bg-yellow-100/90 border-[6px] border-yellow-800 rounded-xl shadow-lg p-6 w-80"
        @submit.prevent="authenticate"
    >
      <UFormField label="Email" name="Email">
        <UInput v-model="email" class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-400 bg-yellow-50 text-yellow-900 placeholder-yellow-700"/>
      </UFormField>
      <UFormField label="Password" name="Password">
        <UInput v-model="password" type="password" class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-400 bg-yellow-50 text-yellow-900 placeholder-yellow-700"/>
      </UFormField>
      <UButton
          type="submit"
          class="w-full mt-6 py-2 px-4 rounded-full border-2 border-yellow-800 bg-yellow-200 hover:bg-yellow-300 text-yellow-900 hover:text-white font-semibold transition-all duration-300"
          @click="authenticate">Log In
      </UButton>
    </UForm>
  </main>
</template>