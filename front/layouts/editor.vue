<template>
  <v-app dir="rtl">
    <!-- هدر ادیتور -->
    <v-app-bar app flat>
      <div class="d-flex align-center w-100">
        <div class="breadcrumb d-flex align-center">
          <v-btn icon to="/"><v-icon>mdi-home</v-icon></v-btn>
          <span class="mx-2">/</span>
          <span class="font-weight-bold">{{ albumName }}</span>
          <span class="mx-2">/</span>
          <v-text-field
            v-model="projectName"
            hide-details
            single-line
            dense
            variant="underlined"
            class="project-name-input"
            style="max-width: 200px;"
          />
        </div>
        <v-spacer />
        <div class="d-flex align-center">
          <div class="d-flex align-center">
          <!-- دکمه ذخیره پروژه -->
          <v-btn color="primary" class="mx-2 save-project-btn" variant="outlined" @click="saveProject">
            <v-icon start>mdi-content-save</v-icon>
            ذخیره
          </v-btn>
        </div>
     
         
          <!-- منوی کاربر -->
          <v-menu
            v-model="showUserMenu"
            :close-on-content-click="false"
            location="bottom end"
          >
            <template v-slot:activator="{ props }">
              <v-btn
                icon
                v-bind="props"
                class="mx-2"
              >
                <v-avatar
                  size="40"
                  :image="userAvatar"
                />
              </v-btn>
            </template>
            <v-card min-width="300">
              <v-list>
                <!-- اطلاعات کاربر -->
                <v-list-item>
                  <template v-slot:prepend>
                    <v-avatar
                      size="60"
                      :image="userAvatar"
                      @click="triggerFileInput"
                      style="cursor:pointer"
                    />
                  </template>
                  <v-list-item-title class="text-h6">{{ userName }}</v-list-item-title>
                  <v-list-item-subtitle>{{ userEmail }}</v-list-item-subtitle>
                </v-list-item>
                <v-divider />
                <!-- کوین‌ها -->
                <v-list-item>
                  <v-list-item-title>حساب شما {{ totalCoins }} کوین دارد</v-list-item-title>
                  <v-list-item-subtitle>{{ remainingCoins }} کوین باقیمانده</v-list-item-subtitle>
                  <v-progress-linear
                    :model-value="(remainingCoins / totalCoins) * 100"
                    color="primary"
                    height="8"
                    class="mt-2"
                  />
                </v-list-item>
                <v-divider />
                <!-- لینک‌های منو -->
                <v-list-item
                  v-for="(item, i) in userMenuItems"
                  :key="i"
                  :to="item.to"
                  :value="item"
                >
                  <template v-slot:prepend>
                    <v-icon :icon="item.icon" />
                  </template>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item>
                <v-divider />
                <!-- دکمه خروج -->
                <v-list-item @click="handleLogout">
                  <template v-slot:prepend>
                    <v-icon icon="mdi-logout" color="error" />
                  </template>
                  <v-list-item-title class="text-error">خروج</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-card>
          </v-menu>
        </div>
      </div>
    </v-app-bar>
    <v-main>
      <slot />
    </v-main>
    <!-- ورودی فایل مخفی برای آپلود آواتار -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      class="d-none"
      @change="handleAvatarUpload"
    />
  </v-app>
</template>

<script setup>
import { ref } from 'vue'

const albumName = ref('آلبوم طراحی')
const projectName = ref('پروژه ۱')

// متغیرهای وضعیت
const showUserMenu = ref(false)
const showNotifications = ref(false)
const fileInput = ref(null)

// اطلاعات کاربر
const userName = ref('استاد دهداری')
const userEmail = ref('ostaddehdari@gmail.com')
const userAvatar = ref('@user_defult.png')
const totalCoins = ref(50)
const remainingCoins = ref(10)

// تعداد نوتیفیکیشن‌ها
const notificationCount = ref(3)

// آیتم‌های منوی کاربر
const userMenuItems = [
  { title: 'پروفایل من', icon: 'mdi-account', to: '/profile' },
  { title: 'دعوت دوستان', icon: 'mdi-account-plus', to: '/invite' },
  { title: 'پشتیبانی', icon: 'mdi-help-circle', to: '/support' }
]

const triggerFileInput = () => {
  fileInput.value.click()
}

const handleAvatarUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      userAvatar.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleLogout = () => {
  // اینجا می‌توانید کوکی‌ها را پاک و ریدایرکت کنید
}

const addAlbum = () => {
  // اینجا می‌توانید ریدایرکت یا باز شدن مودال را قرار دهید
}
function saveProject() {
  // کد ذخیره پروژه
  alert('پروژه ذخیره شد!')
}
</script>

<style scoped>
.editor-menu {
  min-width: 50px !important;
  max-width: 50px !important;
}
.project-name-input {
  font-weight: bold;
}
.add-album-btn {
  border-width: 2px !important;
  border-radius: 8px !important;
}
.breadcrumb{
	width: 50%;
}
</style>
