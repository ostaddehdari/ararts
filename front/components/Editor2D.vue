<template>
  <canvas ref="canvas2D" class="fabric-canvas"></canvas>
</template>

<script setup>
import { onMounted, onBeforeUnmount, watch, ref } from 'vue'
import * as fabric from 'fabric'

const props = defineProps(['layers', 'selectedLayerId', 'selectLayer'])
const emit = defineEmits(['update-layer-position', 'update-layer-size', 'layer-selected'])

const canvas2D = ref(null)
let fabricCanvas = null

onMounted(() => {
  fabricCanvas = new fabric.Canvas(canvas2D.value, {
    width: canvas2D.value.parentElement.clientWidth,
    height: canvas2D.value.parentElement.clientHeight,
    selection: true,
  })

  // اضافه کردن Trigger Image
  fabric.Image.fromURL('/images/trigger-image.png', (img) => {
    img.set({
      left: fabricCanvas.width / 2 - img.width / 2,
      top: fabricCanvas.height / 2 - img.height / 2,
      selectable: false,
      evented: false,
      opacity: 0.7,
    })
    fabricCanvas.add(img)
    fabricCanvas.sendToBack(img)
  }, { crossOrigin: 'anonymous' })

  // رندر اولیه لایه‌ها
  renderLayers()

  // رویدادهای Fabric.js
  fabricCanvas.on('object:modified', (e) => {
    const activeObject = e.target
    emit('update-layer-position', {
      id: activeObject.id,
      x: activeObject.left,
      y: activeObject.top,
    })
  })

  fabricCanvas.on('selection:created', (e) => {
    if (e.target && e.target.id) {
      emit('layer-selected', e.target.id)
    }
  })

  fabricCanvas.on('selection:updated', (e) => {
    if (e.target && e.target.id) {
      emit('layer-selected', e.target.id)
    }
  })

  fabricCanvas.on('selection:cleared', () => {
    emit('layer-selected', null)
  })
})

onBeforeUnmount(() => {
  if (fabricCanvas) {
    // قبل از dispose، بررسی ref و parentNode
    if (canvas2D.value && canvas2D.value.parentNode) {
      fabricCanvas.dispose()
    }
    fabricCanvas = null
  }
})

watch(() => props.layers, (newLayers) => {
  renderLayers()
}, { deep: true })

watch(() => props.selectedLayerId, (newId) => {
  if (fabricCanvas) {
    fabricCanvas.forEachObject((obj) => {
      obj.set({ borderColor: 'blue', cornerColor: 'green' })
      if (obj.id === newId) {
        fabricCanvas.setActiveObject(obj)
        obj.set({ borderColor: 'red', cornerColor: 'red' })
      }
    })
    fabricCanvas.renderAll()
  }
})

function renderLayers() {
  if (!fabricCanvas) return

  // دیباگ: لاگ لایه‌ها
  console.log('Editor2D layers:', props.layers)

  // حذف همه لایه‌های قبلی (به جز trigger image)
  fabricCanvas.getObjects().forEach(obj => {
    if (obj.id !== 'trigger-image') {
      fabricCanvas.remove(obj)
    }
  })

  props.layers.forEach(layer => {
    let obj
    if (layer.type === 'image') {
      if (!layer.src) return // اگر src وجود نداشت، هیچ کاری نکن
      // دیباگ: لاگ src
      console.log('Add image layer src:', layer.src)
      fabric.Image.fromURL(layer.src, (img) => {
        if (!img) {
          alert('امکان بارگذاری این تصویر وجود ندارد. ممکن است مشکل CORS یا فرمت تصویر باشد.')
          return
        }
        img.set({
          id: layer.id,
          left: layer.x || 0,
          top: layer.y || 0,
          width: layer.width || 100,
          height: layer.height || 100,
        })
        fabricCanvas.add(img)
        fabricCanvas.renderAll()
      }, { crossOrigin: 'anonymous' })
    } else if (layer.type === 'text') {
      const textContent = typeof layer.content === 'string' ? layer.content : ''
      obj = new fabric.IText(textContent, {
        id: layer.id,
        left: layer.x || 0,
        top: layer.y || 0,
        fontSize: layer.fontSize || 20,
        fill: layer.color || '#000',
      })
      fabricCanvas.add(obj)
    }
  })
  fabricCanvas.renderAll()
}
</script>

<style scoped>
.editor-canvas {
  /* min-height: 400px; */
  min-height: 600px;
}

canvas.fabric-canvas {
  /* height: 400px; */
  height: 100%;
}

.canvas-container {
  /* height: 400px; */
  height: 100%;
}

.fabric-canvas {
  border: 1px dashed #999;
  background-color: #f8f8f8;
}
</style> 