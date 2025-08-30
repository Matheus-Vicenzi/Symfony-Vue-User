import { ref } from 'vue'

interface ToastState {
  message: string
  type: 'success' | 'warning' | 'danger'
  visible: boolean
}

const toast = ref<ToastState>({ message: '', type: 'success', visible: false })

function showToast(message: string, type: 'success' | 'warning' | 'danger' = 'success') {
  toast.value = { message, type, visible: true }
  setTimeout(() => toast.value.visible = false, 3000)
}

export { toast, showToast }
