<script setup>
import {
  mdiAccountGroup,
  mdiFileDocumentPlus,
} from "@mdi/js";

import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CoreTable from "@/Components/CoreTable.vue";
import CardBox from "@/Components/CardBox.vue";
import BaseButton from "@/Components/BaseButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";

const tableRows = [
  {
    id: 1,
    itemcode: "ITM00123",
    description: "Sample Item 1",
    abccode: "F",
  },
  {
    id: 2,
    itemcode: "ITM00124",
    description: "Sample Item 2",
    abccode: "F",
  },
];

// Define table headers for CoreTable
const userHeader = [
  { text: "ID", value: "id" },
  { text: "Item Code", value: "itemcode" },
  { text: "Description", value: "description" },
  { text: "ABC Code", value: "abccode" },
];
// Debugging: Log tableRows and headers
console.log("Table Rows:", tableRows);
console.log("Table Headers:", userHeader);


// Placeholder functions for button actions
const actionClicked = (action) => {
  console.log(`${action} button clicked`);
};

const openLink = (link) => {
  console.log(`Open link: ${link}`);
};
</script>

<template>
  <div class="p-4   mt-14">
    <SectionTitleLineWithButton :icon="mdiAccountGroup" title="Item Master" main>
      <BaseButton
        @click="actionClicked('Add')"
        :icon="mdiFileDocumentPlus"
        label="Add Item"
        color="contrast"
        rounded-full
        small
      />
    </SectionTitleLineWithButton>

    <CardBox class="flex-1 p-3" has-table>
      <CoreTable
        v-if="tableRows.length > 0"
        :table-rows="tableRows"
        :table-header="userHeader"
        table-name="itemmaster"
        searchable-fields="id,itemcode,description,abccode"
        :is-paginated="true"
        @openLink="openLink"
      />
      <CardBoxComponentEmpty v-else>
        Click Add Item button to create your first record
      </CardBoxComponentEmpty>
    </CardBox>
  </div>
</template>
