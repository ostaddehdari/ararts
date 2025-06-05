export default defineNuxtRouteMiddleware(async (to) => {
  // اگر در صفحه لاگین هستیم، بررسی می‌کنیم که آیا کاربر قبلاً لاگین کرده است
  if (to.path === '/login') {
    const user = useCookie('user')
    const hashcode = useCookie('hashcode')
    
    if (user.value && hashcode.value) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/check-login?user=${user.value}&hashcode=${hashcode.value}`)
        const data = await response.json()
        
        if (data.status === 'success') {
          return navigateTo('/')
        } else {
          // پاک کردن کوکی‌ها در صورت نامعتبر بودن
          user.value = null
          hashcode.value = null
        }
      } catch (error) {
        console.error('خطا در بررسی وضعیت لاگین:', error)
        // پاک کردن کوکی‌ها در صورت خطا
        user.value = null
        hashcode.value = null
      }
    }
    return
  }

  // برای سایر صفحات، بررسی می‌کنیم که آیا کاربر لاگین کرده است
  const user = useCookie('user')
  const hashcode = useCookie('hashcode')
  
  if (!user.value || !hashcode.value) {
    return navigateTo('/login')
  }

  try {
    const response = await fetch(`http://127.0.0.1:8000/api/check-login?user=${user.value}&hashcode=${hashcode.value}`)
    const data = await response.json()
    
    if (data.status !== 'success') {
      // پاک کردن کوکی‌ها در صورت نامعتبر بودن
      user.value = null
      hashcode.value = null
      return navigateTo('/login')
    }
  } catch (error) {
    console.error('خطا در بررسی وضعیت لاگین:', error)
    // پاک کردن کوکی‌ها در صورت خطا
    user.value = null
    hashcode.value = null
    return navigateTo('/login')
  }
}) 