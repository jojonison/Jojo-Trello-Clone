<script setup lang="ts">
import type {Project} from "~/utils/types/models/project";
import type {AxiosResponse} from "axios";
import * as z from 'zod'
const {$api} = useNuxtApp();
const toast = useToast();
const emit = defineEmits<{(e: 'projectAdded', project: Project): void}>();
const state = ref<Partial<Schema>>({project_name: undefined});

type Schema = z.output<typeof schema>
const schema = z.object({
  project_name: z.string().min(1, 'Project name must not be empty')
});
function addProject() {
  $api.post('createProject', {project_name: state.value.project_name})
      .then((response: AxiosResponse) => {
        emit('projectAdded', response.data);
        toast.add({
          title: 'Added a new project',
          description: `Successfully added: ${response.data.project_name}`,
          color: 'success'
        })
      })
}
</script>

<template>
  <main class="flex justify-center max-w-full m-2">
    <UForm :schema="schema" :state="state" @submit="addProject">
      <UFormField label="Project Name" name="project name">
        <UInput v-model="state.project_name" color="neutral" placeholder="Enter Project Name"/>
      </UFormField>
      <UButton type="submit" color="neutral" class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300 m-2">
        ✚ Add Project!
      </UButton>
    </UForm>
  </main>
</template>