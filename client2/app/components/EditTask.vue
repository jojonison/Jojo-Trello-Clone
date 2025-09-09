<script setup lang="ts">
import type {Task} from "~/utils/types/models/task";

const props = defineProps<{
  task: Task
}>()
const emit = defineEmits(['submit', 'cancel'])

const editTitle = ref<string | undefined>(props.task.title)
const editDescription = ref<string | undefined>(props.task.description)

watch(() => props.task, (newTask: Task) => {
  editTitle.value = newTask.title
  editDescription.value = newTask.description
}, { immediate: true })

function submitEdit() {
  emit('submit', {
    id: props.task.id,
    title: editTitle.value,
    description: editDescription.value
  })
}
</script>

<template>
  <main class="flex flex-col">
    <UFormField>
      <label class="text-blue-950 font-bold">Title</label>
      <UInput v-model="editTitle" placeholder="Edit Title:" color="neutral" class="bg-blue-200 border-blue-950 p-2 text-blue-950" />
    </UFormField>
    <UFormField>
      <label class="text-blue-950 font-bold">Description</label>
      <UInput v-model="editDescription" placeholder="Edit Description:" color="neutral" class="bg-blue-200 border-blue-950 p-2 text-blue-950" />
    </UFormField>
    <div>
      <UButton
          class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
          color="neutral"
          @click="submitEdit"
      >
        💾 Finish Editing
      </UButton>
      <UButton
          class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
          color="neutral"
          @click="$emit('cancel')"
      >
        🚫 Cancel
      </UButton>
    </div>
  </main>
</template>