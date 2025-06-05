<template>
  <div class="pa-4">
    <h3 class="text-h6 mb-4">افزودن لایه عکس</h3>
    <v-text-field
      v-model="layerName"
      label="نام لایه"
      dense
      outlined
      class="mb-4"
    />
    <v-file-input
      v-model="imageFile"
      label="انتخاب عکس"
      accept="image/*"
      outlined
      dense
      show-size
      class="mb-4"
    />
    <v-btn color="primary" block @click="addImageLayer">افزودن</v-btn>
    <v-divider class="my-4" />
    <v-text-field
      v-model="imageUrl"
      label="لینک عکس (URL)"
      dense
      outlined
      class="mb-2"
    />
    <v-btn color="secondary" block @click="addImageByUrl">افزودن از طریق لینک</v-btn>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps({ addLayer: Function })
const layerName = ref('')
const imageFile = ref(null)
const imageUrl = ref('')
function addImageLayer() {
  if (!layerName.value) return alert('نام لایه را وارد کنید!')
  if (!imageFile.value) return alert('یک عکس انتخاب کنید!')
  const file = imageFile.value
  const reader = new FileReader()
  reader.onload = (e) => {
    props.addLayer({
      name: layerName.value,
      type: 'image',
      src: e.target.result
    })
    layerName.value = ''
    imageFile.value = null
  }
  reader.readAsDataURL(file)
}
function addImageByUrl() {
  if (!layerName.value) return alert('نام لایه را وارد کنید!')
  if (!imageUrl.value) return alert('لینک عکس را وارد کنید!')

  // تست بارگذاری تصویر قبل از افزودن به لایه‌ها
  const img = new window.Image()
  img.crossOrigin = 'anonymous'
  img.onload = () => {
    props.addLayer({
      name: layerName.value,
      type: 'image',
      src: imageUrl.value
    })
    layerName.value = ''
    imageUrl.value = ''
  }
  img.onerror = () => {
    alert('امکان بارگذاری این تصویر وجود ندارد. لطفاً از یک لینک معتبر و بدون محدودیت CORS استفاده کنید یا تصویر را دانلود و به پروژه اضافه کنید.')
  }
  img.src = imageUrl.value
}
</script> 