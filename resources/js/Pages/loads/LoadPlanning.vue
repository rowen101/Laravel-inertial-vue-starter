<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Load Planning</h1>
      <va-button @click="showAddModal = true" color="primary">
        <va-icon name="ion-ios-add" class="mr-2" />
        Create Load
      </va-button>
    </div>

    <!-- Loads Table -->
    <va-card>
      <va-card-content>
        <va-data-table
          :items="loads"
          :columns="columns"
          :loading="loading"
          striped
          hoverable
        >
          <template #cell(status)="{ value }">
            <va-badge
              :color="getStatusColor(value)"
              :text="value.replace('_', ' ')"
            />
          </template>
          <template #cell(weight)="{ value }">
            {{ value }} lbs
          </template>
          <template #cell(rate)="{ value }">
            ${{ value.toLocaleString() }}
          </template>
          <template #cell(actions)="{ rowData }">
            <div class="flex gap-2">
              <va-button
                @click="editLoad(rowData)"
                size="small"
                color="info"
                icon="ion-ios-create"
              />
              <va-button
                @click="deleteLoad(rowData.id)"
                size="small"
                color="danger"
                icon="ion-ios-trash"
              />
            </div>
          </template>
        </va-data-table>
      </va-card-content>
    </va-card>

    <!-- Add/Edit Load Modal -->
    <va-modal v-model="showAddModal" title="Create Load" size="large">
      <va-form ref="loadForm" @submit.prevent="saveLoad">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <va-input
            v-model="loadForm.load_number"
            label="Load Number"
            :rules="[required]"
            required
          />
          <va-input
            v-model="loadForm.customer_name"
            label="Customer Name"
            :rules="[required]"
            required
          />
          <va-input
            v-model="loadForm.pickup_location"
            label="Pickup Location"
            :rules="[required]"
            required
          />
          <va-input
            v-model="loadForm.delivery_location"
            label="Delivery Location"
            :rules="[required]"
            required
          />
          <va-date-input
            v-model="loadForm.pickup_date"
            label="Pickup Date"
            :rules="[required]"
            required
          />
          <va-date-input
            v-model="loadForm.delivery_date"
            label="Delivery Date"
            :rules="[required]"
            required
          />
          <va-input
            v-model="loadForm.weight"
            label="Weight (lbs)"
            type="number"
            :rules="[required]"
            required
          />
          <va-input
            v-model="loadForm.rate"
            label="Rate ($)"
            type="number"
            step="0.01"
            :rules="[required]"
            required
          />
          <va-select
            v-model="loadForm.load_type"
            label="Load Type"
            :options="loadTypeOptions"
            :rules="[required]"
            required
          />
          <va-select
            v-model="loadForm.status"
            label="Status"
            :options="statusOptions"
            :rules="[required]"
            required
          />
        </div>
        <div class="col-span-2">
          <va-textarea
            v-model="loadForm.notes"
            label="Notes"
            rows="3"
          />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <va-button @click="showAddModal = false" color="secondary">
            Cancel
          </va-button>
          <va-button type="submit" color="primary">
            Save Load
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
const loads = ref([])
const loading = ref(false)
const showAddModal = ref(false)
const loadForm = ref({
  id: null,
  load_number: '',
  customer_name: '',
  pickup_location: '',
  delivery_location: '',
  pickup_date: null,
  delivery_date: null,
  weight: '',
  rate: '',
  load_type: 'dry_van',
  status: 'planned',
  notes: ''
})

const columns = [
  { key: 'load_number', label: 'Load #', sortable: true },
  { key: 'customer_name', label: 'Customer', sortable: true },
  { key: 'pickup_location', label: 'Pickup', sortable: true },
  { key: 'delivery_location', label: 'Delivery', sortable: true },
  { key: 'pickup_date', label: 'Pickup Date', sortable: true },
  { key: 'delivery_date', label: 'Delivery Date', sortable: true },
  { key: 'weight', label: 'Weight' },
  { key: 'rate', label: 'Rate' },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'actions', label: 'Actions', width: 120 }
]

const loadTypeOptions = [
  { text: 'Dry Van', value: 'dry_van' },
  { text: 'Refrigerated', value: 'refrigerated' },
  { text: 'Flatbed', value: 'flatbed' },
  { text: 'Tanker', value: 'tanker' },
  { text: 'Container', value: 'container' }
]

const statusOptions = [
  { text: 'Planned', value: 'planned' },
  { text: 'Assigned', value: 'assigned' },
  { text: 'In Transit', value: 'in_transit' },
  { text: 'Delivered', value: 'delivered' },
  { text: 'Cancelled', value: 'cancelled' }
]

// Validation rules
const required = (value) => !!value || 'This field is required'

// Methods
const getStatusColor = (status) => {
  const colors = {
    planned: 'info',
    assigned: 'warning',
    in_transit: 'primary',
    delivered: 'success',
    cancelled: 'danger'
  }
  return colors[status] || 'secondary'
}

const fetchLoads = async () => {
  loading.value = true
  try {
    // Mock data for now - replace with actual API call
    loads.value = [
      {
        id: 1,
        load_number: 'LD-2024-001',
        customer_name: 'ABC Manufacturing',
        pickup_location: 'Chicago, IL',
        delivery_location: 'Dallas, TX',
        pickup_date: '2024-01-15',
        delivery_date: '2024-01-17',
        weight: 45000,
        rate: 2500.00,
        load_type: 'dry_van',
        status: 'planned',
        notes: 'Handle with care'
      },
      {
        id: 2,
        load_number: 'LD-2024-002',
        customer_name: 'XYZ Logistics',
        pickup_location: 'Los Angeles, CA',
        delivery_location: 'Phoenix, AZ',
        pickup_date: '2024-01-16',
        delivery_date: '2024-01-17',
        weight: 38000,
        rate: 1800.00,
        load_type: 'refrigerated',
        status: 'assigned',
        notes: 'Temperature controlled'
      }
    ]
  } catch (error) {
    notify({
      message: 'Error fetching loads',
      color: 'danger'
    })
  } finally {
    loading.value = false
  }
}

const saveLoad = async () => {
  try {
    // Mock save - replace with actual API call
    if (loadForm.value.id) {
      // Update existing load
      const index = loads.value.findIndex(l => l.id === loadForm.value.id)
      if (index !== -1) {
        loads.value[index] = { ...loadForm.value }
      }
    } else {
      // Add new load
      const newLoad = {
        ...loadForm.value,
        id: Date.now(), // Mock ID
        rate: parseFloat(loadForm.value.rate),
        weight: parseInt(loadForm.value.weight)
      }
      loads.value.push(newLoad)
    }
    
    notify({
      message: 'Load saved successfully',
      color: 'success'
    })
    
    showAddModal.value = false
    resetForm()
  } catch (error) {
    notify({
      message: 'Error saving load',
      color: 'danger'
    })
  }
}

const editLoad = (load) => {
  loadForm.value = { ...load }
  showAddModal.value = true
}

const deleteLoad = async (id) => {
  if (confirm('Are you sure you want to delete this load?')) {
    try {
      // Mock delete - replace with actual API call
      loads.value = loads.value.filter(l => l.id !== id)
      notify({
        message: 'Load deleted successfully',
        color: 'success'
      })
    } catch (error) {
      notify({
        message: 'Error deleting load',
        color: 'danger'
      })
    }
  }
}

const resetForm = () => {
  loadForm.value = {
    id: null,
    load_number: '',
    customer_name: '',
    pickup_location: '',
    delivery_location: '',
    pickup_date: null,
    delivery_date: null,
    weight: '',
    rate: '',
    load_type: 'dry_van',
    status: 'planned',
    notes: ''
  }
}

// Lifecycle
onMounted(() => {
  fetchLoads()
})
</script>