<template>
  <div class="pa-4">
    <h3 class="text-h6 mb-4">افزودن لایه افکت</h3>
    <v-text-field
      v-model="layerName"
      label="نام لایه"
      dense
      outlined
      class="mb-4"
    />
    <v-select
      v-model="effectType"
      :items="effectOptions"
      label="نوع افکت"
      dense
      outlined
      class="mb-4"
    />
    <v-btn color="primary" block @click="addEffectLayer">افزودن</v-btn>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps({ addLayer: Function })
const layerName = ref('')
const effectType = ref('')
const effectOptions = [
  'Blur',
  'Glow',
  'Shadow',
  'Colorize',
  'Fade',
  'Wave',
  'Noise'
]
function addEffectLayer() {
  if (!layerName.value || !effectType.value) return alert('نام لایه و نوع افکت را وارد کنید!')
  props.addLayer({
    name: layerName.value,
    type: 'effect',
    effect: effectType.value
  })
  layerName.value = ''
  effectType.value = ''
}
</script> 