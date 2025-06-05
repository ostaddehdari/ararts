<template>
  <div class="pa-4">
    <h3 class="text-h6 mb-4">افزودن لایه متن</h3>
    <v-text-field
      v-model="layerName"
      label="نام لایه"
      dense
      outlined
      class="mb-4"
    />
    <v-textarea
      v-model="textContent"
      label="متن"
      rows="3"
      outlined
      dense
      class="mb-4"
    />
    <v-btn color="primary" block @click="addTextLayer">افزودن</v-btn>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps({ addLayer: Function })
const layerName = ref('')
const textContent = ref('')
function addTextLayer() {
  if (!layerName.value || !textContent.value) return alert('نام لایه و متن را وارد کنید!')
  props.addLayer({
    name: layerName.value,
    type: 'text',
    content: textContent.value
  })
  layerName.value = ''
  textContent.value = ''
}
</script> 