import { createRouter, createWebHistory } from "vue-router";
import MasterBarang from "./components/MasterBarang.vue";
import BarangMasuk from "./components/BarangMasuk.vue";
import BarangKeluar from "./components/BarangKeluar.vue";
import Laporan from "./components/Laporan.vue";
import Login from "./components/Login.vue";

const routes = [
  { path: "/login", name: "Login", component: Login },
  { path: "/master-barang", name: "MasterBarang", component: MasterBarang, meta: { requiresAuth: true } },
  { path: "/barang-masuk", name: "BarangMasuk", component: BarangMasuk, meta: { requiresAuth: true } },
  { path: "/barang-keluar", name: "BarangKeluar", component: BarangKeluar, meta: { requiresAuth: true } },
  { path: "/laporan", name: "Laporan", component: Laporan, meta: { requiresAuth: true } },
  { path: "/", redirect: "/login" }, 
  { path: "/:pathMatch(.*)*", redirect: "/login" }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("auth_token");

  if (to.meta.requiresAuth && !token) {
    next("/login");
  } else if (to.path === "/login" && token) {
    next("/master-barang");
  } else {
    next();
  }
})

export default router;