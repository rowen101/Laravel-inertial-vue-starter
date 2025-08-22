<template>
  <div class="w-full max-w-sm mx-auto">
    <VaCard class="auth-card">
      <VaCardContent class="p-6">
        <div class="text-center mb-6">
          <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome back</h1>
          <p class="text-gray-600">Sign in to your account</p>
        </div>
        
        <form @submit.prevent="submit" class="space-y-4">
          <VaInput
            v-model="form.email"
            type="email"
            label="Email"
            placeholder="Enter your email"
            :error="!!errors.email"
            :error-messages="errors.email"
            required
            class="w-full"
          />
          
          <VaInput
            v-model="form.password"
            type="password"
            label="Password"
            placeholder="Enter your password"
            :error="!!errors.password"
            :error-messages="errors.password"
            required
            class="w-full"
          />
          
          <div class="flex items-center justify-between">
            <VaCheckbox
              v-model="form.remember"
              label="Remember me"
              class="text-sm"
            />
          </div>
          
          <VaButton
            type="submit"
            :loading="form.processing"
            class="w-full"
            color="primary"
            size="large"
          >
            {{ form.processing ? 'Signing in...' : 'Sign in' }}
          </VaButton>
          
          <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
              Don't have an account?
              <Link href="/register" class="text-primary hover:underline font-medium">
                Sign up
              </Link>
            </p>
          </div>
        </form>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AuthLayout from '../../Layouts/AuthLayout.vue'

defineOptions({
  layout: AuthLayout
})

defineProps({
  errors: Object
})

const form = useForm({
  email: '',
  password: '',
  remember: false
})

const submit = () => {
  form.post('/login')
}
</script>

<style scoped>
.auth-card {
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border-radius: 12px;
}
</style>
