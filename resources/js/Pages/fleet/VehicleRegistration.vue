<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Vehicle Registration</h1>
      <va-button @click="showAddModal = true" color="primary">
        <va-icon name="ion-ios-add" class="mr-2" />
        Add Vehicle
      </va-button>
    </div>

    <!-- Vehicles Table -->
    <va-card>
      <va-card-content>
        <va-data-table
          :items="vehicles"
          :columns="columns"
          :loading="loading"
          striped
          hoverable
        >
          <template #cell(actions)="{ rowData }">
            <div class="flex gap-2">
              <va-button
                @click="editVehicle(rowData)"
                size="small"
                color="info"
                icon="ion-ios-create"
              />
              <va-button
                @click="deleteVehicle(rowData.id)"
                size="small"
                color="danger"
                icon="ion-ios-trash"
              />
            </div>
          </template>
        </va-data-table>
      </va-card-content>
    </va-card>

    <!-- Add/Edit Vehicle Modal -->
    <va-modal v-model="showAddModal" title="Add Vehicle" size="large">
      <va-form ref="vehicleForm" @submit.prevent="saveVehicle">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <va-input
            v-model="vehicleForm.vehicle_number"
            label="Vehicle Number"
            :rules="[required]"
            required
          />
          <va-input
            v-model="vehicleForm.make"
            label="Make"
            :rules="[required]"
            required
          />
          <va-input
            v-model="vehicleForm.model"
            label="Model"
            :rules="[required]"
            required
          />
          <va-input
            v-model="vehicleForm.year"
            label="Year"
            type="number"
            :rules="[required]"
            required
          />
          <va-input
            v-model="vehicleForm.vin"
            label="VIN"
            :rules="[required]"
            required
          />
          <va-select
            v-model="vehicleForm.status"
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
            Save Vehicle
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
const vehicles = ref([])
const loading = ref(false)
const showAddModal = ref(false)
const vehicleForm = ref({
  id: null,
  vehicle_number: '',
  make: '',
  model: '',
  year: '',
  vin: '',
  status: 'active'
})

const columns = [
  { key: 'vehicle_number', label: 'Vehicle Number', sortable: true },
  { key: 'make', label: 'Make', sortable: true },
  { key: 'model', label: 'Model', sortable: true },
  { key: 'year', label: 'Year', sortable: true },
  { key: 'vin', label: 'VIN' },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'actions', label: 'Actions', width: 120 }
]

const statusOptions = [
  { text: 'Active', value: 'active' },
  { text: 'Maintenance', value: 'maintenance' },
  { text: 'Out of Service', value: 'out_of_service' }
]

// Validation rules
const required = (value) => !!value || 'This field is required'

// Methods
const fetchVehicles = async () => {
  loading.value = true
  try {
    // Mock data for now - replace with actual API call
    vehicles.value = [
      {
        id: 1,
        vehicle_number: 'TRK-001',
        make: 'Freightliner',
        model: 'Cascadia',
        year: 2022,
        vin: '1FUJGHDV8NLAA1234',
        status: 'active'
      },
      {
        id: 2,
        vehicle_number: 'TRK-002',
        make: 'Peterbilt',
        model: '579',
        year: 2021,
        vin: '1XPWD40X1ED123456',
        status: 'maintenance'
      }
    ]
  } catch (error) {
    notify({
      message: 'Error fetching vehicles',
      color: 'danger'
    })
  } finally {
    loading.value = false
  }
}

const saveVehicle = async () => {
  try {
    // Mock save - replace with actual API call
    if (vehicleForm.value.id) {
      // Update existing vehicle
      const index = vehicles.value.findIndex(v => v.id === vehicleForm.value.id)
      if (index !== -1) {
        vehicles.value[index] = { ...vehicleForm.value }
      }
    } else {
      // Add new vehicle
      const newVehicle = {
        ...vehicleForm.value,
        id: Date.now() // Mock ID
      }
      vehicles.value.push(newVehicle)
    }
    
    notify({
      message: 'Vehicle saved successfully',
      color: 'success'
    })
    
    showAddModal.value = false
    resetForm()
  } catch (error) {
    notify({
      message: 'Error saving vehicle',
      color: 'danger'
    })
  }
}

const editVehicle = (vehicle) => {
  vehicleForm.value = { ...vehicle }
  showAddModal.value = true
}

const deleteVehicle = async (id) => {
  if (confirm('Are you sure you want to delete this vehicle?')) {
    try {
      // Mock delete - replace with actual API call
      vehicles.value = vehicles.value.filter(v => v.id !== id)
      notify({
        message: 'Vehicle deleted successfully',
        color: 'success'
      })
    } catch (error) {
      notify({
        message: 'Error deleting vehicle',
        color: 'danger'
      })
    }
  }
}

const resetForm = () => {
  vehicleForm.value = {
    id: null,
    vehicle_number: '',
    make: '',
    model: '',
    year: '',
    vin: '',
    status: 'active'
  }
}

// Lifecycle
onMounted(() => {
  fetchVehicles()
})
</script>