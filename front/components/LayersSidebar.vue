<template>
  <div class="pa-4">
    <h3 class="text-h6 mb-4">مدیریت لایه‌ها</h3>
    <draggable v-model="layers" item-key="id" @end="onDragEnd">
      <template #item="{ element, index }">
        <v-list-item class="my-1">
          <v-list-item-icon>
            <v-icon>{{ getLayerIcon(element.type) }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ element.name }}</v-list-item-title>
          </v-list-item-content>
          <v-list-item-action>
            <v-btn icon small @click="deleteLayer(index)">
              <v-icon color="error">mdi-delete</v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
      </template>
    </draggable>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'

const layers = ref([])
const emit = defineEmits(['update:layers', 'delete-layer'])

function deleteLayer(index) {
  emit('delete-layer', index)
}

function onDragEnd() {
  emit('update:layers', layers.value)
}

function getLayerIcon(type) {
  switch (type) {
    case 'image': return 'mdi-image'
    case 'video': return 'mdi-video'
    case 'model': return 'mdi-cube-outline'
    case 'effect': return 'mdi-sparkles'
    case 'text': return 'mdi-format-text'
    case 'group': return 'mdi-group'
    default: return 'mdi-file'
  }
}
</script>

<style scoped>
/* استایل‌های مورد نیاز */
</style> 