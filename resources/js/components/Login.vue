<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-4">

    <div
      class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-8 transform transition-all duration-500 animate-fadeIn"
    >
      <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">
        Sign In 👋
      </h2>

      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block mb-1 text-gray-700 dark:text-gray-700 font-medium">
            Email
          </label>
          <input
            type="email"
            v-model="email"
            required
            class="w-full p-3 rounded-xl border border-gray-100 focus:ring-4 focus:ring-indigo-300 outline-none transition"
          />
        </div>

        <div class="mb-6">
          <label class="block mb-1 text-gray-700 dark:text-gray-700 font-medium">
            Password
          </label>

          <div class="relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              v-model="password"
              required
              class="w-full p-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-indigo-300 outline-none transition"
            />
            <i
              :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"
              class="absolute right-5 top-4 text-xl text-gray-500 cursor-pointer"
              @click="showPassword = !showPassword"
            ></i>
          </div>
        </div>

        <button
          type="submit"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold p-3 rounded-xl shadow-lg transition transform hover:scale-[1.02]"
        >
          <span v-if="isLoading">Loading...</span>
          <span v-else>Login</span>
        </button>

      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
      showPassword: false,
      isLoading: false,
    };
  },
  methods: {
async login() {
  if (!this.email || !this.password) {
    alert("Email & password harus diisi");
    return;
  }

  this.isLoading = true;

  try {
    const response = await axios.post("/api/login", {
      email: this.email,
      password: this.password,
    });

    const token = response.data.token;
    localStorage.setItem("auth_token", token);

    this.$emit("login-success");

    alert("Login berhasil!");
    this.$router.push("/master-barang");
  } catch (error) {
    console.error("Login gagal:", error.response?.data || error.message);
    alert(error.response?.data?.message || "Login gagal, cek email & password");
  } finally {
    this.isLoading = false;
  }
},


    logout() {
      localStorage.removeItem("auth_token"); 
      this.$router.push("/login");
    },
  },
};
</script>

<style>
@keyframes fadeIn {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.6s ease-out;
}
</style>
