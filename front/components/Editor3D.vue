<template>
  <TresCanvas v-bind="state" class="tres-canvas">
    <TresPerspectiveCamera :position="[3, 3, 3]" :look-at="[0, 0, 0]" />
    <OrbitControls />

    <!-- Trigger Image در 3D -->
    <TresMesh :position="[0, 0, -0.01]">
      <TresPlaneGeometry :args="[triggerImageSize.width / 100, triggerImageSize.height / 100]" />
      <TresMeshBasicMaterial>
        <TresTexture :src="triggerImageSrc" />
      </TresMeshBasicMaterial>
    </TresMesh>

    <!-- لایه‌های 3D -->
    <template v-for="layer in props.layers" :key="layer.id">
      <TresMesh
        v-if="layer.type === 'model'"
        :position="[layer.x || 0, layer.y || 0, layer.z || 0]"
        @click="selectLayer(layer.id)"
      >
        <TresBoxGeometry />
        <TresMeshBasicMaterial :color="layer.id === props.selectedLayerId ? 'red' : 'green'" />
      </TresMesh>
    </template>

    <!-- نورپردازی -->
    <TresAmbientLight :intensity="1" />
    <TresDirectionalLight :position="[0, 10, 0]" />
  </TresCanvas>
</template>

<script setup>
import { TresCanvas } from '@tresjs/core'
import { TresPerspectiveCamera, TresAmbientLight, TresDirectionalLight } from '@tresjs/core'
import { OrbitControls } from '@tresjs/cientos'
import { reactive, watch, ref } from 'vue'

const props = defineProps(['layers', 'selectedLayerId', 'selectLayer'])
const emit = defineEmits(['update-layer-position', 'update-layer-rotation', 'layer-selected'])

const state = reactive({
  // تنظیمات Canvas
})

// Trigger Image در 3D
const triggerImageSrc = ref('/images/trigger-image.png')
const triggerImageSize = ref({ width: 400, height: 600 })

function selectLayer(id) {
  emit('layer-selected', id)
}

watch(() => props.selectedLayerId, (newId) => {
  // منطق برای هایلایت کردن شی 3D انتخاب شده
})

// watch props.layers برای اضافه/حذف/آپدیت عناصر در صحنه Three.js
</script>

<style scoped>
.tres-canvas {
  width: 100%;
  height: 100%;
  background-color: #eee;
}
</style> 