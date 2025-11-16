<template>
  <div class="d-flex">
    <SideBar v-if="isLoggedIn" @logout="handleLogout" />

    <LoadingOverlay :show="isLoading" />

    <div class="content flex-grow-1">
      <keep-alive>
        <router-view @login-success="handleLoginSuccess" />
      </keep-alive>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import SideBar from "./components/SideBar.vue";
import LoadingOverlay from "./components/LoadingOverlay.vue";

export default {
  components: { SideBar, LoadingOverlay },
  setup() {
    const isLoggedIn = ref(!!localStorage.getItem("auth_token"));
    const isLoading = ref(false);

    const handleLoginSuccess = () => {
      isLoggedIn.value = true;
    };

    const handleLogout = () => {
      localStorage.removeItem("auth_token");
      isLoggedIn.value = false;      
    };

    return { isLoggedIn, isLoading, handleLoginSuccess, handleLogout };
  },
};
</script>
