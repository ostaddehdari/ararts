<template>
  <v-app-bar
    fixed
    color="white"
    elevation="2"
    class="px-4"
  >
    <!-- لوگو -->
    <img
      src="/images/logo_black.png"
      alt="Logo"
      height="40"
      class="ml-4"
    />

    <v-spacer />

    <!-- دکمه‌های هدر -->
    <div class="d-flex align-center">
      <!-- تیکت‌ها -->
      <v-btn
        icon
        class="mx-2"
        @click="showTickets = !showTickets"
      >
        <v-badge
          :content="ticketCount"
          :model-value="ticketCount > 0"
          color="error"
        >
          <v-icon>mdi-ticket</v-icon>
        </v-badge>
      </v-btn>

      <!-- نوتیفیکیشن‌ها -->
      <v-btn
        icon
        class="mx-2"
        @click="showNotifications = !showNotifications"
      >
        <v-badge
          :content="notificationCount"
          :model-value="notificationCount > 0"
          color="error"
        >
          <v-icon>mdi-bell</v-icon>
        </v-badge>
      </v-btn>

      <!-- دکمه منوی همبرگری -->
      <v-btn
        icon
        class="mx-2"
        @click="drawer = !drawer"
      >
        <v-icon>mdi-menu</v-icon>
      </v-btn>

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
              @click="triggerFileInput"
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

    <!-- منوی همبرگری کشویی -->
    <v-navigation-drawer
      v-model="drawer"
      temporary
      location="right"
      width="300"
      class="hamburger-menu"
      :scrim="true"
      :overlay="true"
      permanent
    >
      <v-list class="h-100">
        <v-list-item
          v-for="(item, i) in navigationItems"
          :key="i"
          :to="item.to"
          :value="item"
          class="py-2"
          @click="drawer = false"
        >
          <template v-slot:prepend>
            <v-icon :icon="item.icon" />
          </template>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- منوی تیکت‌ها -->
    <v-menu
      v-model="showTickets"
      location="bottom end"
    >
      <v-card min-width="300">
        <v-list>
          <v-list-item
            v-for="(ticket, i) in tickets"
            :key="i"
            :to="ticket.to"
          >
            <v-list-item-title>{{ ticket.title }}</v-list-item-title>
            <v-list-item-subtitle>{{ ticket.date }}</v-list-item-subtitle>
          </v-list-item>
        </v-list>
      </v-card>
    </v-menu>

    <!-- منوی نوتیفیکیشن‌ها -->
    <v-menu
      v-model="showNotifications"
      location="bottom end"
    >
      <v-card min-width="300">
        <v-list>
          <v-list-item
            v-for="(notification, i) in notifications"
            :key="i"
            :to="notification.to"
          >
            <v-list-item-title>{{ notification.title }}</v-list-item-title>
            <v-list-item-subtitle>{{ notification.date }}</v-list-item-subtitle>
          </v-list-item>
        </v-list>
      </v-card>
    </v-menu>

    <!-- ورودی فایل مخفی برای آپلود آواتار -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      class="d-none"
      @change="handleAvatarUpload"
    />
  </v-app-bar>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// متغیرهای وضعیت
const drawer = ref(false)
const showUserMenu = ref(false)
const showTickets = ref(false)
const showNotifications = ref(false)
const fileInput = ref(null)

// اطلاعات کاربر
const userName = ref('استاد دهداری')
const userEmail = ref('ostaddehdari@gmail.com')
const userAvatar = ref('/images/default-avatar.png')
const totalCoins = ref(50)
const remainingCoins = ref(10)

// تعداد نوتیفیکیشن‌ها و تیکت‌ها
const notificationCount = ref(3)
const ticketCount = ref(2)

// آیتم‌های منوی کاربر
const userMenuItems = [
  { title: 'پروفایل من', icon: 'mdi-account', to: '/profile' },
  { title: 'دعوت دوستان', icon: 'mdi-account-plus', to: '/invite' },
  { title: 'پشتیبانی', icon: 'mdi-help-circle', to: '/support' }
]

// آیتم‌های منوی اصلی
const navigationItems = [
  { title: 'آلبوم‌های من', icon: 'mdi-image-multiple', to: '/albums' },
  { title: 'آثار عمومی من', icon: 'mdi-earth', to: '/public-works' },
  { title: 'تیکت‌های من', icon: 'mdi-ticket', to: '/tickets' },
  { title: 'اکسپلور', icon: 'mdi-compass', to: '/explore' },
  { title: 'رسانه‌های من', icon: 'mdi-play-circle', to: '/media' },
  { title: 'سیاست‌ها', icon: 'mdi-shield', to: '/policies' },
  { title: 'درباره ما', icon: 'mdi-information', to: '/about' }
]

// نوتیفیکیشن‌ها
const notifications = [
  { title: 'تیکت جدید پاسخ داده شد', date: '2 ساعت پیش', to: '/tickets/1' },
  { title: 'اثر جدید شما تایید شد', date: '1 روز پیش', to: '/works/1' },
  { title: 'کوین جدید دریافت کردید', date: '2 روز پیش', to: '/wallet' }
]

// تیکت‌ها
const tickets = [
  { title: 'پاسخ به تیکت پشتیبانی', date: '1 ساعت پیش', to: '/tickets/1' },
  { title: 'درخواست جدید', date: '3 ساعت پیش', to: '/tickets/2' }
]

// تابع آپلود آواتار
const triggerFileInput = () => {
  fileInput.value.click()
}

const handleAvatarUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    // اینجا می‌توانید فایل را به سرور آپلود کنید
    const reader = new FileReader()
    reader.onload = (e) => {
      userAvatar.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

// تابع خروج
const handleLogout = () => {
  const user = useCookie('user')
  const hashcode = useCookie('hashcode')
  
  user.value = null
  hashcode.value = null
  
  navigateTo('/login')
}

// بررسی وضعیت لاگین در زمان لود صفحه
onMounted(() => {
  const user = useCookie('user')
  const hashcode = useCookie('hashcode')
  
  if (!user.value || !hashcode.value) {
    navigateTo('/login')
  }
})
</script>

<style scoped>
.v-app-bar {
  direction: rtl;
}

.v-list-item {
  min-height: 48px;
}

.v-avatar {
  cursor: pointer;
  transition: transform 0.2s;
}

.v-avatar:hover {
  transform: scale(1.05);
}

.hamburger-menu {
  direction: rtl;
  height: 100vh !important;
  position: fixed !important;
  top: 0 !important;
  right: 0 !important;
  z-index: 1000 !important;
}

/* انیمیشن برای منوی همبرگری */
.hamburger-menu.v-navigation-drawer--open {
  transform: translateX(0);
  transition: transform 0.3s ease-in-out;
}

.hamburger-menu.v-navigation-drawer--close {
  transform: translateX(100%);
  transition: transform 0.3s ease-in-out;
}

/* استایل برای لیست در منوی همبرگری */
.hamburger-menu .v-list {
  height: 100%;
  padding-top: 64px; /* ارتفاع هدر */
}
</style> 