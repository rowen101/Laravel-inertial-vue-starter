<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Driver Management</h1>
      <va-button @click="showAddModal = true" color="primary">
        <va-icon name="ion-ios-add" class="mr-2" />
        Add Driver
      </va-button>
    </div>

    <!-- Drivers Table -->
    <va-card>
      <va-card-content>
        <va-data-table
          :items="drivers"
          :columns="columns"
          :loading="loading"
          striped
          hoverable
        >
          <template #cell(status)="{ value }">
            <va-badge
              :color="getStatusColor(value)"
              :text="value"
            />
          </template>
          <template #cell(actions)="{ rowData }">
            <div class="flex gap-2">
              <va-button
                @click="editDriver(rowData)"
                size="small"
                color="info"
                icon="ion-ios-create"
              />
              <va-button
                @click="deleteDriver(rowData.id)"
                size="small"
                color="danger"
                icon="ion-ios-trash"
              />
            </div>
          </template>
        </va-data-table>
      </va-card-content>
    </va-card>

    <!-- Add/Edit Driver Modal -->
    <va-modal v-model="showAddModal" title="Add Driver" size="large">
      <va-form ref="driverForm" @submit.prevent="saveDriver">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <va-input
            v-model="driverForm.first_name"
            label="First Name"
            :rules="[required]"
            required
          />
          <va-input
            v-model="driverForm.last_name"
            label="Last Name"
            :rules="[required]"
            required
          />
          <va-input
            v-model="driverForm.license_number"
            label="License Number"
            :rules="[required]"
            required
          />
          <va-input
            v-model="driverForm.phone"
            label="Phone Number"
            :rules="[required]"
            required
          />
          <va-input
            v-model="driverForm.email"
            label="Email"
            type="email"
            :rules="[required, emailRule]"
            required
          />
          <va-date-input
            v-model="driverForm.hire_date"
            label="Hire Date"
            :rules="[required]"
            required
          />
          <va-date-input
            v-model="driverForm.license_expiry"
            label="License Expiry"
            :rules="[required]"
            required
          />
          <va-select
            v-model="driverForm.status"
            label="Status"
            :options="statusOptions"
            :rules="[required]"
            required
          />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <va-button @click="showAddModal = false" color="secondary">
            Cancel
          </va-button>
          <va-button type="submit" color="primary">
            Save Driver
          </va-button>
        </div>
      </va-form>
    </va-modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'

const { init: notify } = useToast()

// Data
const drivers = ref([])
const loading = ref(false)
const showAddModal = ref(false)
const driverForm = ref({
  id: null,
  first_name: '',
  last_name: '',
  license_number: '',
  phone: '',
  email: '',
  hire_date: null,
  license_expiry: null,
  status: 'active'
})

const columns = [
  { key: 'first_name', label: 'First Name', sortable: true },
  { key: 'last_name', label: 'Last Name', sortable: true },
  { key: 'license_number', label: 'License #', sortable: true },
  { key: 'phone', label: 'Phone' },
  { key: 'email', label: 'Email' },
  { key: 'hire_date', label: 'Hire Date', sortable: true },
  { key: 'license_expiry', label: 'License Expiry', sortable: true },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'actions', label: 'Actions', width: 120 }
]

const statusOptions = [
  { text: 'Active', value: 'active' },
  { text: 'On Leave', value: 'on_leave' },
  { text: 'Suspended', value: 'suspended' },
  { text: 'Terminated', value: 'terminated' }
]

// Validation rules
const required = (value) => !!value || 'This field is required'
const emailRule = (value) => {
  const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return pattern.test(value) || 'Invalid email format'
}

// Methods
const getStatusColor = (status) => {
  const colors = {
    active: 'success',
    on_leave: 'warning',
    suspended: 'danger',
    terminated: 'danger'
  }
  return colors[status] || 'secondary'
}

const fetchDrivers = async () => {
  loading.value = true
  try {
    // Mock data for now - replace with actual API call
    drivers.value = [
      {
        id: 1,
        first_name: 'John',
        last_name: 'Smith',
        license_number: 'DL123456789',
        phone: '(555) 123-4567',
        email: 'john.smith@example.com',
        hire_date: '2023-01-15',
        license_expiry: '2025-12-31',
        status: 'active'
      },
      {
        id: 2,
        first_name: 'Sarah',
        last_name: 'Johnson',
        license_number: 'DL987654321',
        phone: '(555) 987-6543',
        email: 'sarah.johnson@example.com',
        hire_date: '2022-08-20',
        license_expiry: '2024-06-30',
        status: 'active'
      }
    ]
  } catch (error) {
    notify({
      message: 'Error fetching drivers',
      color: 'danger'
    })
  } finally {
    loading.value = false
  }
}

const saveDriver = async () => {
  try {
    // Mock save - replace with actual API call
    if (driverForm.value.id) {
      // Update existing driver
      const index = drivers.value.findIndex(d => d.id === driverForm.value.id)
      if (index !== -1) {
        drivers.value[index] = { ...driverForm.value }
      }
    } else {
      // Add new driver
      const newDriver = {
        ...driverForm.value,
        id: Date.now() // Mock ID
      }
      drivers.value.push(newDriver)
    }
    
    notify({
      message: 'Driver saved successfully',
      color: 'success'
    })
    
    showAddModal.value = false
    resetForm()
  } catch (error) {
    notify({
      message: 'Error saving driver',
      color: 'danger'
    })
  }
}

const editDriver = (driver) => {
  driverForm.value = { ...driver }
  showAddModal.value = true
}

const deleteDriver = async (id) => {
  if (confirm('Are you sure you want to delete this driver?')) {
    try {
      // Mock delete - replace with actual API call
      drivers.value = drivers.value.filter(d => d.id !== id)
      notify({
        message: 'Driver deleted successfully',
        color: 'success'
      })
    } catch (error) {
      notify({
        message: 'Error deleting driver',
        color: 'danger'
      })
    }
  }
}

const resetForm = () => {
  driverForm.value = {
    id: null,
    first_name: '',
    last_name: '',
    license_number: '',
    phone: '',
    email: '',
    hire_date: null,
    license_expiry: null,
    status: 'active'
  }
}

// Lifecycle
onMounted(() => {
  fetchDrivers()
})
</script>