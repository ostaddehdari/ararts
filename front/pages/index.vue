<template>
  <v-container fluid class="main-content py-8 text-right" dir="rtl">
    <!-- بخش جستجو و معرفی -->
    <section class="intro-section mb-8">
      <v-sheet class="pa-8 gradient-bg" rounded>
        <div class="text-center mb-4">
          <h1 class="display-1 font-weight-bold mb-2">#BringIdeasToLife</h1>
          <p class="subtitle-1 mb-6">ایده‌هایت را به واقعیت تبدیل کن!</p>
          <v-text-field
            v-model="search"
            placeholder="جستجو در پروژه‌ها..."
            prepend-inner-icon="mdi-magnify"
            class="search-bar mx-auto text-right"
            solo
            rounded
            hide-details
            @keyup.enter="goToExplore"
            style="max-width: 400px; direction: rtl;"
          />
        </div>
        <v-row justify="center">
          <v-col cols="12" md="4">
            <v-card class="learn-card" outlined>
              <v-card-title class="justify-center">نحوه کار را بیاموزید</v-card-title>
              <v-card-actions class="justify-center">
                <v-btn color="primary" @click="goToTutorial">شروع کنید</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-sheet>
    </section>

    <!-- بخش پروژه‌های آماده -->
    <section class="mb-8">
      <div class="section-title mb-4">پروژه‌های آماده</div>
      <v-row>
        <v-col v-for="(template, i) in templates" :key="i" cols="12" sm="6" md="4" lg="3">
          <v-card>
            <video
              :src="template.video"
              style="width: 100%; height: 320px; object-fit: cover; border-radius: 8px 8px 0 0;"
              autoplay
              muted
              loop
              playsinline
            ></video>
            <v-card-title>{{ template.title }}</v-card-title>
            <v-card-actions>
              <v-btn text color="primary" @click="viewTemplate(template)">مشاهده پروژه</v-btn>
              <v-btn color="primary" @click="useTemplate(template)">استفاده از الگو</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </section>

    <!-- بخش آموزش‌ها -->
    <section class="mb-8">
      <div class="section-title mb-4">آموزش‌ها</div>
      <v-slide-group show-arrows>
        <v-slide-item v-for="(tutorial, i) in tutorials" :key="i">
          <v-card class="mx-2" width="250">
            <v-img :src="tutorial.image" height="120" />
            <v-card-title>{{ tutorial.title }}</v-card-title>
            <v-card-subtitle>آموزش</v-card-subtitle>
            <v-card-actions>
              <v-btn color="primary" @click="goToTutorial(tutorial)">مشاهده</v-btn>
            </v-card-actions>
          </v-card>
        </v-slide-item>
      </v-slide-group>
    </section>

    <!-- بخش آلبوم‌ها -->
    <section class="mb-8">
      <div class="section-title mb-4 d-flex align-center justify-space-between text-right" style="direction: rtl;">
        <span>آلبوم‌ها</span>
        <v-btn color="primary" outlined @click="addAlbum">
          <v-icon start>mdi-plus</v-icon>
          آلبوم جدید
        </v-btn>
      </div>
      <v-row class="text-right" style="direction: rtl;">
        <v-col v-for="(album, i) in albums" :key="i" cols="12" sm="6" md="4" lg="3">
          <v-card class="position-relative">
            <div class="d-flex flex-row align-center justify-center mt-4 mb-2">
              <v-icon size="24" color="yellow darken-2" class="ml-2">mdi-folder</v-icon>
              <span class="font-weight-bold">{{ album.title }}</span>
            </div>
            <p class="mb-1 mt-1 text-center" style="font-size: 0.95rem; color: #666;">{{ album.count }} پروژه</p>
            <v-btn icon class="album-action-btn delete-btn small-icon-btn" @click="deleteAlbum(album)">
              <v-icon color="error" size="20">mdi-delete</v-icon>
            </v-btn>
            <v-btn icon class="album-action-btn edit-btn small-icon-btn" @click="editAlbum(album)">
              <v-icon size="20">mdi-pencil</v-icon>
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </section>

    <!-- بخش آثار هنری/آخرین پروژه‌ها -->
    <section>
      <div class="section-title mb-4 text-right" style="direction: rtl;">آخرین پروژه‌ها</div>
      <v-row class="text-right" style="direction: rtl;">
        <v-col v-for="(project, i) in projects" :key="i" cols="12" sm="6" md="4" lg="3">
          <v-card>
            <v-img :src="project.image" height="140" />
            <v-card-title>{{ project.title }}</v-card-title>
            <v-card-actions>
              <v-btn color="primary" @click="editProject(project)">ویرایش</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col v-if="projects.length === 0" cols="12">
          <div class="text-center py-8">
            هنوز پروژه‌ای ندارید! <v-btn color="primary" @click="addProject">شروع کنید</v-btn>
          </div>
        </v-col>
      </v-row>
    </section>
  </v-container>
</template>

<script setup>
import { ref, nextTick } from 'vue'

const search = ref('')
const templates = ref([
  { video: 'https://cdn.artivive.com/videos/template3.webm', title: 'قالب آماده ۱' },
  { video: 'https://cdn.artivive.com/videos/template4.webm', title: 'قالب آماده ۲' },
  { video: 'https://cdn.artivive.com/videos/template5.webm', title: 'قالب آماده ۳' },
  { video: 'https://cdn.artivive.com/videos/template-valentine3.mp4', title: 'قالب آماده ۴' }
])
const tutorials = ref([
  { image: '/images/learn/learn1.png', title: 'آموزش ۱' },
  { image: '/images/learn/learn2.png', title: 'آموزش ۲' },
  { image: '/images/learn/learn3.png', title: 'آموزش ۳' },
  { image: '/images/learn/learn4.png', title: 'آموزش ۴' }
])
const albums = ref([
  { title: 'آلبوم طراحی', count: 5 },
  { title: 'آلبوم شخصی', count: 2 }
])
const projects = ref([
  { image: '/images/project/p1.jpg', title: 'پروژه ۱' },
  { image: '/images/project/p2.png', title: 'پروژه ۲' }
])

const showControls = (index, event) => {
  videoControls.value[index] = true
  nextTick(() => {
    if (videos.value[index]) {
      videos.value[index].play()
    }
  })
}

const goToExplore = () => {
  // ریدایرکت به صفحه اکسپلور با مقدار جستجو
  navigateTo(`/explore?search=${search.value}`)
}
const goToTutorial = (tutorial) => {
  // ریدایرکت به صفحه آموزش یا ویدیو
  navigateTo('/tutorials')
}
const viewTemplate = (template) => {
  // نمایش جزئیات پروژه آماده
  navigateTo('/templates/' + template.title)
}
const useTemplate = (template) => {
  // کپی پروژه آماده برای کاربر
  alert('پروژه به حساب شما اضافه شد!')
}
const addAlbum = () => {
  // باز کردن مودال یا ریدایرکت به ساخت آلبوم
  navigateTo('/albums/create')
}
const editAlbum = (album) => {
  // ویرایش آلبوم
  navigateTo('/albums/edit')
}
const deleteAlbum = (album) => {
  // حذف آلبوم
  alert('آلبوم حذف شد!')
}
const editProject = (project) => {
  // ویرایش پروژه
  navigateTo('/projects/edit')
}
const addProject = () => {
  // افزودن پروژه جدید
  navigateTo('/projects/create')
}

definePageMeta({
  layout: 'default'
})
</script>

<style scoped>
.main-content {
  background: #f8fafc;
}
.intro-section .gradient-bg {
  background: linear-gradient(90deg, #e3f0ff 0%, #f8fafc 100%);
}
.section-title {
  font-size: 1.3rem;
  font-weight: bold;
  color: #333;
}
.search-bar {
  font-size: 1.1rem;
}
.learn-card {
  background: #fffbe6;
}
video {
  height: 300px;
}
.album-action-btn {
  position: absolute;
  left: 8px;
  z-index: 2;
}
.delete-btn {
  top: 8px;
}
.edit-btn {
  bottom: 8px;
}
.small-icon-btn.v-btn--icon.v-btn--density-default {
  width: 24px !important;
  height: 24px !important;
  min-width: 24px !important;
  min-height: 24px !important;
}
</style> 