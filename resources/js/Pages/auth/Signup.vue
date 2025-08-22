<script setup>
import { useForm } from '@inertiajs/vue3'
import { route } from '../../../../vendor/tightenco/ziggy/src/js'
import AuthLayout from '../../Layouts/AuthLayout.vue'

defineOptions({
  layout: AuthLayout
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'))
}
</script>

<template>
  <div class="flex items-center justify-center min-h-screen">
    <VaCard class="auth-card w-full max-w-md">
      <VaCardContent class="p-8">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold text-gray-900 mb-2">
            Create your account
          </h2>
          <p class="text-gray-600">
            Join us today and get started
          </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <VaInput
            v-model="form.name"
            label="Full Name"
            placeholder="Enter your full name"
            :error="!!form.errors.name"
            :error-messages="form.errors.name"
            required
            class="w-full"
          />

          <VaInput
            v-model="form.email"
            label="Email Address"
            type="email"
            placeholder="Enter your email"
            :error="!!form.errors.email"
            :error-messages="form.errors.email"
            required
            class="w-full"
          />

          <VaInput
            v-model="form.password"
            label="Password"
            type="password"
            placeholder="Enter your password"
            :error="!!form.errors.password"
            :error-messages="form.errors.password"
            required
            class="w-full"
          />

          <VaInput
            v-model="form.password_confirmation"
            label="Confirm Password"
            type="password"
            placeholder="Confirm your password"
            required
            class="w-full"
          />

          <VaButton
            type="submit"
            :loading="form.processing"
            :disabled="form.processing"
            class="w-full"
            size="large"
          >
            {{ form.processing ? 'Creating account...' : 'Create account' }}
          </VaButton>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <Link :href="route('login')" class="font-medium text-primary hover:text-primary-dark">
              Sign in
            </Link>
          </p>
        </div>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<style scoped>
.auth-card {
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border-radius: 12px;
}
</style>
