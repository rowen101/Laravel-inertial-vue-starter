<script setup>
import { computed, ref, inject } from "vue";
import { useMainStore } from "@/Stores/main";
import { mdiCheckDecagram } from "@mdi/js";
import BaseLevel from "@/Components/BaseLevel.vue";
import UserAvatarCurrentUser from "@/Components/UserAvatarCurrentUser.vue";
import CardBox from "@/Components/CardBox.vue";
import FormCheckRadio from "@/Components/FormCheckRadio.vue";
import PillTag from "@/Components/PillTag.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
const mainStore = useMainStore();

const userName = computed(() => mainStore.userName);
const showRsidebar = inject('showRsidebar')
const disable2fa = inject('disable2fa')
const userSwitchVal = ref(false);
const props = defineProps({userUse2FactorAuthentication:Boolean})
</script>

<template>
  <CardBox>
    <BaseLevel type="justify-around lg:justify-center">
      <UserAvatarCurrentUser class="lg:mx-12" />
      <!-- <div class="space-y-3 text-center md:text-left lg:mx-12">
        <div class="flex justify-center md:block">
          <FormCheckRadio v-model="userSwitchVal" name="notifications-switch" type="switch" label="Notifications"
            :input-value="true" />
        </div>
      </div> -->
    </BaseLevel>
    <div class="flex flex-row justify-center mt-10">
      <BaseButtons>
        <BaseButton color="info" type="submit" label="Change Password" @click="showRsidebar = 'changePass'" />
        <BaseButton v-if="props.userUse2FactorAuthentication==false" color="info" label="Enable 2-Step Verification" @click="showRsidebar = 'enalbeTwoF2'" outline />
        <BaseButton v-if="props.userUse2FactorAuthentication==true" color="danger" label="Disable 2-Step Verification" @click="showRsidebar = 'disable2fa'" outline />
      </BaseButtons>
    </div>

  </CardBox>
</template>
