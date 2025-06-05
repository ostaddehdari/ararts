<template>
  <div class="pa-4">
    <h3 class="text-h6 mb-4">افزودن لایه ویدیو</h3>
    <v-text-field
      v-model="layerName"
      label="نام لایه"
      dense
      outlined
      class="mb-4"
    />
    <v-file-input
      v-model="videoFile"
      label="انتخاب ویدیو"
      accept="video/*"
      outlined
      dense
      show-size
      class="mb-4"
    />
    <v-btn color="primary" block @click="addVideoLayer">افزودن</v-btn>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps({ addLayer: Function })
const layerName = ref('')
const videoFile = ref(null)
function addVideoLayer() {
  if (!layerName.value) return alert('نام لایه را وارد کنید!')
  props.addLayer({
    name: layerName.value,
    type: 'video',
    file: videoFile.value
  })
  layerName.value = ''
  videoFile.value = null
}
</script> 