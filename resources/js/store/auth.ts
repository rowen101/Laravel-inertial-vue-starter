import { defineStore } from 'pinia'
import axios from 'axios'
import { useToast } from 'vuestic-ui'

const { init } = useToast()
export const useAuthStore = defineStore('auth', {
  state: () => {
    return {
      token: null as string | null,
      user: null as { uuid: string; userName: string; email: string; warehouse_id: number; warehouse_code: string } | null,
    }
  },

  actions: {
    // Initialize state from localStorage
    initializeStore() {
      if (typeof window !== 'undefined') {
        const token = localStorage.getItem('token')
        const user = localStorage.getItem('user')

        if (token) {
          this.token = token
        }
        if (user) {
          this.user = JSON.parse(user)
        }
      }
    },

    setToken(token: string) {
      this.token = token
      localStorage.setItem('token', token)
    },

    setUser(user: { uuid: string; userName: string; email: string; warehouse_id: number; warehouse_code: string }) {
      this.user = user
      localStorage.setItem('user', JSON.stringify(user))
    },

    clearAuth() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    },

    async login(credentials: { username: string; password: string }) {
      try {
        // For Laravel Inertia, we'll use the standard login endpoint
        const response = await axios.post('/login', credentials)

        if (response.data && response.data.token) {
          this.setToken(response.data.token)
          this.setUser({
            uuid: response.data.uuid,
            userName: response.data.userName,
            email: response.data.email,
            warehouse_id: response.data.warehouse_id,
            warehouse_code: response.data.warehouse_code,
          })
        } else {
          init({ message: 'Login failed', color: 'danger' })
        }
      } catch (error: any) {
        if (error.response) {
          init({ message: error.response.data.message || 'Login failed', color: 'danger' })
        } else {
          init({ message: 'An unexpected error occurred!', color: 'danger' })
        }
      }
    },

    logout() {
      this.clearAuth()
    },
  },

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
    username: (state) => state.user?.userName, // Getter for username
    uuid: (state) => state.user?.uuid, // Getter for uuid
    warehouse_id: (state) => state.user?.warehouse_id,
    warehouse_code: (state) => state.user?.warehouse_code,
  },
})
