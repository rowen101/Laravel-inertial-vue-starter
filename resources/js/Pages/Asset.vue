<script setup>
import {
  mdiAccount,
  mdiAccountGroup, mdiDeleteAlert,
  mdiFileDocumentPlus, mdiMail,
} from "@mdi/js";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CoreTable from "@/Components/CoreTable.vue";
import CardBox from "@/Components/CardBox.vue";
import BaseButton from "@/Components/BaseButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";



const tableRows =[
    {
        id:1,
        itemcode:"ITM00123",
        description:"sample",
        abccode:"F"
    }
]
</script>
<template>
 <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

 </div>
<Head title="itemamaster" />
  <SectionTitleLineWithButton :icon="mdiAccountGroup" title="Item Master" main>
    <BaseButton
      @click="actionClicked('Add')"
      :icon="mdiFileDocumentPlus "
      label="Add Item"
      color="contrast"
      rounded-full
      small
    />
  </SectionTitleLineWithButton>

  <CardBox class="flex-1 p-6" has-table>
    <CoreTable v-if="tableRows.length > 0"
                :table-rows="tableRows"
                :table-header="userHeader"
                table-name="itemmaster"
                searchable-fields="id,itemcode,description,abccode"
                :is-paginated="true"
                @openLink="openLink"
    />
    <CardBoxComponentEmpty v-else >Click Add Item button to create your first record</CardBoxComponentEmpty>
  </CardBox>
</template>
