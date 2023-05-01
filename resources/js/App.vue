<template>
  <router-view />
</template>

<script>
import { useThemeSettingsStore } from "@/store/themeSettings";
import { useAuthStore } from "@/store/auth";

export default {
    computed: {
        isOnline() {
        }
    },
  mounted() {
    this.$store.themeSettingsStore = useThemeSettingsStore()
    this.$store.useAuthStore = useAuthStore()
  }
}
</script>

<style></style>
