<script setup lang="ts">
import type {Project} from "~/utils/types/models/project";
import type {Task} from "~/utils/types/models/task";
import type {AxiosResponse} from "axios";

const {$api} = useNuxtApp();
const props = defineProps<{selectedProject?: Project}>()
const emit = defineEmits<{(e: 'taskAdded', task: Task): void}>()
const state = ref({
  title: '',
  description: ''
})
const toast = useToast();
function addTask() {
  if (!props.selectedProject) {
    toast.add({title:'Please select a project', description: 'Cannot add task without a project', color:'error'});
    return
  }
  $api.post(`/create-task`, {
    title: state.value.title, description: state.value.description, project_id: props.selectedProject?.id
  })
      .then((response: AxiosResponse) => {
        emit('taskAdded', response.data)
        toast.add({title:'Added a Task', description: `Successfully added ${response.data.title}`, color:'success'});
      })
}
</script>

<template>
  <main class="flex justify-center max-w-full m-2">
    <UForm :state="state" @submit="addTask">
      <UFormField label="Title" name="title"><UInput v-model="state.title" color="neutral" placeholder="Enter Title"/></UFormField>
      <UFormField label="Description" name="description"><UInput v-model="state.description" color="neutral" placeholder="Enter Description"/></UFormField>
      <UButton type="submit" color="neutral" class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300 m-2">✚ Add Task!</UButton>
    </UForm>
  </main>
</template>