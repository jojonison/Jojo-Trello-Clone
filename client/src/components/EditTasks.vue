<script setup lang="ts">
import { ref, watch} from 'vue'
import {Task} from "@/types/models/task";

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
    <div class="flex flex-col">
        <label class="text-yellow-800">Title</label> 
        <input v-model="editTitle" placeholder="Edit Title:" class="max-w-6/12 max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 text-pink-800 text-1xl" />
        <label class="text-yellow-800">Description</label> 
        <input v-model="editDescription" placeholder="Edit Description:" class="max-w-6/12 max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 text-pink-800 text-1xl" />
        <div>
            <button 
                @click="submitEdit" 
                class="max-w-6/12 max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-1xl"
            >
                💾 Finish Editing
            </button>
            <button 
                @click="$emit('cancel')" 
                class="max-w-6/12 max-h-9 border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800 text-1xl"
            >
                🚫 Cancel
            </button>
        </div>
    </div>
</template>
