<template>
  <div class="sidebar d-flex flex-column" style="height: 100vh; padding: 1rem; background-color: #f8f9fa;">
    
    <b-nav vertical>
      <h4 class="mb-4">Dashboard</h4>
      <b-nav-item
        v-for="item in menuItems"
        :key="item.id"
        class="sidebar-item"
        :class="{ active: activeItem === item.id }"
        :to="item.route"
        router
        @click="setActive(item.id)"
      >
        <i :class="item.icon + ' me-2'"></i> {{ item.name }}
      </b-nav-item>
    </b-nav>

    <b-nav vertical class="mt-auto">
      <b-nav-item
        class="sidebar-item text-danger"
        @click="logout"
      >
        <i class="bi bi-box-arrow-right me-2"></i> Logout
      </b-nav-item>
    </b-nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeItem: 1,
      menuItems: [
        { id: 1, name: "Master Barang", icon: "bi bi-house-door-fill", route: "/master-barang" },
        { id: 2, name: "Barang Masuk", icon: "bi bi-folder-fill", route: "/barang-masuk" },
        { id: 3, name: "Barang Keluar", icon: "bi bi-gear-fill", route: "/barang-keluar" },
        { id: 4, name: "Laporan", icon: "bi bi-file-earmark-text-fill", route: "/laporan" },
      ],
    };
  },
  methods: {
    setActive(id) {
      this.activeItem = id;
    },
logout() {
  const confirmed = window.confirm("Apakah Anda yakin ingin logout?");
  if (confirmed) {
    localStorage.removeItem("auth_token");
    this.$emit("logout"); 
    this.$router.push("/login");
  }
}

  },
};
</script>
