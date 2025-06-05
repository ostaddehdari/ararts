<template>
  <v-app dir="rtl">
  
    <!-- نوار ابزار زیر هدر -->
    <v-toolbar flat dense class="editor-toolbar" style="height: 48px !important;">
      <v-container class="pa-0">
        <v-row no-gutters>
          <v-col>
            <div class="d-flex align-center">
              <v-btn-toggle v-model="currentView" mandatory group>
                <v-btn value="2d">2D</v-btn>
                <v-btn value="3d">3D</v-btn>
              </v-btn-toggle>
              <v-btn icon @click="undo" :disabled="!canUndo" class="mx-1">
                <v-icon>mdi-undo</v-icon>
              </v-btn>
              <v-btn icon @click="redo" :disabled="!canRedo" class="mx-1">
                <v-icon>mdi-redo</v-icon>
              </v-btn>
              <v-divider vertical class="mx-2"></v-divider>
              <v-btn icon @click="togglePlayPause" class="mx-1">
                <v-icon>{{ isPlaying ? 'mdi-pause' : 'mdi-play' }}</v-icon>
              </v-btn>
              <v-btn icon @click="stop" class="mx-1">
                <v-icon>mdi-stop</v-icon>
              </v-btn>
              <v-divider vertical class="mx-2"></v-divider>
              <v-btn icon @click="showLayersSidebar = !showLayersSidebar" class="bt_layers sidebar_hide mx-1 ml-auto">
                <v-icon>mdi-layers</v-icon>
              </v-btn>
              <div class="view-toggle mb-2">
              
            </div>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-toolbar>

    <!-- منوی ثابت سمت چپ -->
    <v-navigation-drawer app permanent width="50" class="editor-menu">
      <v-list dense>
        <v-list-item v-for="item in menuItems" :key="item.title" @click="openSidebar(item)">
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>
        </v-list-item>
      </v-list>
      <template #append>
        <v-btn icon @click="triggerImage"><v-icon>mdi-image</v-icon></v-btn>
      </template>
    </v-navigation-drawer>

    <!-- سایدبار راست (پویا) -->
    <v-navigation-drawer app temporary right v-model="showSidebarContent" width="300">
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="text-h6">
            {{ activeSidebarTitle }}
          </v-list-item-title>
        </v-list-item-content>
        <v-btn icon @click="showSidebarContent = false" class="sidebar_hide mx-1 ml-auto">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-list-item>
      <v-divider></v-divider>
      <v-card flat>
        <component :is="activeSidebarComponent" v-if="activeSidebarComponent" :add-layer="addLayer" />
        <div v-else class="pa-4 text-center">محتوای سایدبار راست (در مراحل بعدی)</div>
      </v-card>
    </v-navigation-drawer>

    <!-- بخش اصلی ادیتور -->
    <v-main>
      <v-container fluid class="editor-container">
        <v-row class="fill-height">
          <v-col cols="12" class="d-flex flex-column align-center justify-center editor-canvas-wrapper">
            <!-- سوییچ بین 2D و 3D -->
            
            <div class="editor-canvas">
              <component :is="editorComponent" :layers="layers" :selected-layer-id="selectedLayerId" :select-layer="selectLayer" />
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <!-- سایدبار لایه‌ها -->
    <v-navigation-drawer app temporary right v-model="showLayersSidebar" width="300">
      <LayersSidebar
        :layers="layers"
        @update:layers="updateLayers"
        @delete-layer="deleteLayer"
      />
    </v-navigation-drawer>

    <!-- سایدبار المنتور -->
    <v-navigation-drawer app temporary right v-model="showElementsSidebar" width="300">
      <ElementsSidebar
        :elements="elements"
        @add-element="addElement"
        @close="showElementsSidebar = false"
      />
    </v-navigation-drawer>
  </v-app>
</template>

<script setup>
definePageMeta({
  layout: 'editor'
})
import { ref, computed, shallowRef } from 'vue'
import ImageLayerSidebar from '~/components/ImageLayerSidebar.vue'
import VideoLayerSidebar from '~/components/VideoLayerSidebar.vue'
import ModelLayerSidebar from '~/components/ModelLayerSidebar.vue'
import EffectLayerSidebar from '~/components/EffectLayerSidebar.vue'
import TextLayerSidebar from '~/components/TextLayerSidebar.vue'
import GroupLayerSidebar from '~/components/GroupLayerSidebar.vue'
import LayersSidebar from '~/components/LayersSidebar.vue'
import ElementsSidebar from '~/components/ElementsSidebar.vue'
import Editor2D from '~/components/Editor2D.vue'

const albumName = ref('نام آلبوم')
const projectName = ref('نام اثر')
const showSidebarContent = ref(false)
const showLayersSidebar = ref(false)
const showElementsSidebar = ref(false)
const activeSidebarTitle = ref('')
const currentView = ref('2d')
const editorComponent = computed(() => currentView.value === '2d' ? Editor2D : 'Editor3D')
const selectedLayerId = ref(null)
const isPlaying = ref(false)
const canUndo = ref(false)
const canRedo = ref(false)

const menuItems = [
  
  { title: 'افزودن عکس', icon: 'mdi-image', component: ImageLayerSidebar },
  { title: 'افزودن ویدیو', icon: 'mdi-video-outline', component: VideoLayerSidebar },
  { title: 'افزودن مدل سه‌بعدی', icon: 'mdi-cube-scan', component: ModelLayerSidebar },
  { title: 'افزودن افکت', icon: 'mdi-star-four-points-circle', component: EffectLayerSidebar },
  { title: 'افزودن متن', icon: 'mdi-format-text-variant', component: TextLayerSidebar },
  { title: 'افزودن المنت', icon: 'mdi-shape-outline', component: ElementsSidebar },
  { title: 'افزودن گروه', icon: 'mdi-folder-multiple-plus', component: GroupLayerSidebar }
]

const elements = [
  { id: 1, name: 'مستطیل', icon: 'mdi-rectangle-outline', type: 'shape' },
  { id: 2, name: 'دایره', icon: 'mdi-circle-outline', type: 'shape' },
  { id: 3, name: 'مثلث', icon: 'mdi-triangle-outline', type: 'shape' },
  { id: 4, name: 'خط', icon: 'mdi-vector-line', type: 'shape' },
  { id: 5, name: 'ستاره', icon: 'mdi-star-outline', type: 'shape' },
  { id: 6, name: 'قلب', icon: 'mdi-heart-outline', type: 'shape' }
]

const activeSidebarComponent = shallowRef(null)

const layers = ref([
  { id: 'layer1', name: 'لایه عکس', type: 'image' },
  { id: 'layer2', name: 'لایه ویدیو', type: 'video' },
  { id: 'layer3', name: 'لایه متن', type: 'text' }
])

function openSidebar(item) {
  activeSidebarTitle.value = item.title
  activeSidebarComponent.value = item.component
  showSidebarContent.value = true
}

function updateLayers(newLayers) {
  layers.value = [...newLayers]
}

function deleteLayer(index) {
  layers.value.splice(index, 1)
}

function addLayer(layer) {
  const newLayer = {
    ...layer,
    id: 'layer' + (layers.value.length + 1)
  }
  if (layer.type === 'image' && layer.src) {
    newLayer.src = layer.src
  }
  layers.value.push(newLayer)
}

function addElement(element) {
  addLayer({
    type: element.type,
    name: element.name,
    icon: element.icon
  })
}

function selectLayer(id) {
  selectedLayerId.value = id
}

function triggerImage() {
  // کد مربوط به نمایش تصویر
}

function undo() {
  // کد مربوط به undo
}

function redo() {
  // کد مربوط به redo
}

function togglePlayPause() {
  isPlaying.value = !isPlaying.value
}

function stop() {
  isPlaying.value = false
}


</script>

<style scoped>
.editor-menu {
  min-width: 70px !important;
  max-width: 70px !important;
}
.project-name-input {
  min-width: 50% !important;
  width: 100% !important;
  max-width: 400px !important;
  font-weight: bold;
}
.save-project-btn {
  border-width: 2px !important;
  border-radius: 8px !important;
}
.editor-container {
  position: relative;
  height: 100%;
  width: 100%;
}
.editor-canvas-wrapper {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  border-radius: 8px;
  overflow: hidden;
  width: 100%;
}
.editor-canvas {
  position: relative;
  width: 100%;
  min-height: 400px;
  background-color: white;
  overflow: hidden;
}
.editor-toolbar {
  border-bottom: 1px solid #e0e0e0;
  background-color: #f5f5f5 !important;
}

.bt_layers {
	position: absolute;
	right: 0;
}
.v-navigation-drawer__content .sidebar_hide {
	position: absolute;
	right: 0;
	z-index: 1;
	top: 0;
	width: 24px !important;
	height: 24px !important;
	font-size: 10px;
}
</style> 