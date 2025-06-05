<template>
  <v-app>
    <v-main>
      <NuxtLayout>
        <NuxtPage />
      </NuxtLayout>
    </v-main>
  </v-app>
</template>

<script setup>
// تنظیمات اولیه برنامه
</script>

<style>
html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  direction: rtl;
}

.v-application {
  font-family: 'Vazirmatn', sans-serif !important;
}

/* تنظیمات اضافی برای فونت وزیر */
.v-btn {
  font-family: 'Vazirmatn', sans-serif !important;
}

.v-card-title {
  font-family: 'Vazirmatn', sans-serif !important;
}

.v-text-field label {
  font-family: 'Vazirmatn', sans-serif !important;
}
</style>
