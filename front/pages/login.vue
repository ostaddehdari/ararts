<template>
  <v-container fluid class="fill-height pa-0 login-container">
    <!-- لوگو ثابت در بالا سمت چپ -->
    <div class="fixed-logo d-none d-md-block">
      <img src="/images/logo.png" alt="Logo" height="40" />
    </div>

    <v-row no-gutters class="fill-height">
      <!-- ستون سمت چپ - ویدیو -->
      <v-col cols="12" md="4" class="d-none d-md-flex flex-column">
        <div class="video-wrapper">
          <div class="video-container">
            <video
              ref="videoRef"
              autoplay
              loop
              muted
              playsinline
              class="video-background"
            >
              <source src="/videos/login-background.mp4" type="video/mp4" />
              مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
            </video>
          </div>
        </div>
      </v-col>

      <!-- ستون وسط - فرم لاگین -->
      <v-col cols="12" md="4" class="d-flex align-center justify-center">
        <v-card class="elevation-12" width="100%" max-width="400">
          <v-card-title class="text-center text-h4 font-weight-bold">
            خوش آمدید
          </v-card-title>
          
          <v-card-text>
            <v-form @submit.prevent="handleLogin">
              <v-text-field
                v-model="email"
                label="ایمیل"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                :rules="emailRules"
                class="mb-4"
              />
              
              <v-text-field
                v-model="password"
                label="رمز عبور"
                prepend-inner-icon="mdi-lock"
                :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append-inner="showPassword = !showPassword"
                :type="showPassword ? 'text' : 'password'"
                variant="outlined"
                :rules="passwordRules"
                class="mb-4"
              />

              <v-btn
                color="primary"
                block
                size="large"
                type="submit"
                class="mt-4 font-weight-bold"
              >
                ورود
              </v-btn>
            </v-form>

            <div class="text-center mt-4">
              <v-btn variant="text" color="primary" to="/forgot-password" class="font-weight-medium">
                فراموشی رمز عبور؟
              </v-btn>
            </div>

            <v-divider class="my-4" />

            <div class="text-center">
              <p class="text-body-2 mb-2 font-weight-medium">یا ورود با</p>
              <v-btn-group>
                <v-btn icon="mdi-google" variant="outlined" />
                <v-btn icon="mdi-facebook" variant="outlined" />
                <v-btn icon="mdi-apple" variant="outlined" />
              </v-btn-group>
            </div>
          </v-card-text>

          <v-card-actions class="justify-center pb-6">
            <span class="text-body-2 font-weight-medium">حساب کاربری ندارید؟</span>
            <v-btn variant="text" color="primary" to="/register" class="font-weight-bold">
              ثبت نام
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <!-- ستون خالی سمت راست برای تعادل -->
      <v-col cols="12" md="4" class="d-none d-md-flex" />
    </v-row>
  </v-container>
</template>

<script setup>
import { onMounted, ref } from 'vue'

definePageMeta({
  layout: 'login',
  middleware: ['auth']
})

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const videoRef = ref(null)

const emailRules = [
  v => !!v || 'ایمیل الزامی است',
  v => /.+@.+\..+/.test(v) || 'ایمیل معتبر نیست'
]

const passwordRules = [
  v => !!v || 'رمز عبور الزامی است',
  v => v.length >= 6 || 'رمز عبور باید حداقل ۶ کاراکتر باشد'
]

const handleLogin = async () => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/login-email-validate?email=${encodeURIComponent(email.value)}&password=${encodeURIComponent(password.value)}`)
    const data = await response.json()
    
    if (data.status === 'success') {
      // ذخیره اطلاعات در کوکی با زمان انقضا
      const user = useCookie('user', { maxAge: 86400 }) // 24 ساعت
      const hashcode = useCookie('hashcode', { maxAge: 86400 }) // 24 ساعت
      
      user.value = data.user
      hashcode.value = data.hashcode
      
      // انتقال به صفحه اصلی
      navigateTo('/')
    } else {
      alert('خطا در ورود به سیستم')
    }
  } catch (error) {
    console.error('خطا در ارتباط با سرور:', error)
    alert('خطا در ارتباط با سرور')
  }
}

// اضافه کردن event listener برای ویدیو
onMounted(() => {
  if (videoRef.value) {
    videoRef.value.play().catch(error => {
      console.error('Error playing video:', error)
    })
  }
})
</script>

<style scoped>
.v-card {
  border-radius: 12px;
}

/* تنظیمات فونت وزیر برای این صفحه */
:deep(.v-field__input) {
  font-family: 'Vazirmatn', sans-serif !important;
}

:deep(.v-field__label) {
  font-family: 'Vazirmatn', sans-serif !important;
}

:deep(.v-btn__content) {
  font-family: 'Vazirmatn', sans-serif !important;
}

/* تنظیمات جدید ویدیو */
.video-wrapper {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  width: 100%;
}

.video-container {
  position: relative;
  width: 100%;
  max-width: 250px; /* عرض کمتر */
  height: 450px; /* ارتفاع کمتر */
  background-color: #f5f5f5;
  border-radius: 20px; /* رادیوس بیشتر */
  overflow: hidden;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  border: 5px solid #000; /* بوردر سیاه */
}

.video-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 15px; /* رادیوس داخلی */
}

/* گرادیانت پس‌زمینه */
.login-container {
  background: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #ffffff 100%);
  min-height: 100vh;
}

/* تنظیمات اضافی برای کارت لاگین */
.v-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  margin: 0 auto;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
}

/* تنظیمات لوگوی ثابت */
.fixed-logo {
  position: fixed;
  top: 20px;
  left: 20px;
  z-index: 100;
  padding: 10px;
  background: transparent;
  border-radius: 8px;
  backdrop-filter: blur(5px);
}
</style> 