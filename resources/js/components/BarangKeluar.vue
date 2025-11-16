<template>
  <div class="table-container">
    <div class="table-header">
      <h2 class="table-title">Barang Keluar</h2>
      <button class="btn-add" @click="openModal('add')">Tambah Barang Keluar</button>
    </div>

    <table class="modern-table">
      <thead>
        <tr>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Jumlah Keluar</th>
          <th>Tanggal</th>
          <th>Keterangan</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in paginatedList" :key="item.id">
          <td>{{ item.asset.kode }}</td>
          <td>{{ item.asset.nama }}</td>
          <td>{{ item.jumlah }}</td>
          <td>{{ item.tanggal }}</td>
          <td>{{ item.keterangan }}</td>
          <td>
            <button @click="editItem(item)" class="btn-edit">Edit</button>
            <button @click="deleteItem(item.id)" class="btn-delete">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="pagination">
      <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
      <span>Page {{ currentPage }} / {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>

    <!-- Modal -->
    <div class="modal" :class="{ 'modal-active': showModal }">
      <div class="modal-content">
        <h3>{{ modalMode === 'add' ? 'Tambah Barang Keluar' : 'Edit Barang Keluar' }}</h3>

        <select v-model="form.asset_id">
          <option disabled value="">-- Pilih Barang --</option>
          <option v-for="asset in assetList" :key="asset.id" :value="asset.id">
            {{ asset.kode }} - {{ asset.nama }} (Stok: {{ asset.stock_awal }})
          </option>
        </select>

        <input type="date" v-model="form.tanggal" />
        <input type="number" v-model.number="form.jumlah" min="1" placeholder="Jumlah Keluar" />
        <input type="text" v-model="form.keterangan" placeholder="Keterangan (opsional)" />

        <div class="modal-actions">
          <button @click="submitBarangKeluar" class="btn-save" :disabled="isLoading">
            <span v-if="isLoading" class="spinner-small"></span>
            <span v-else>Simpan</span>
          </button>
          <button @click="showModal = false" class="btn-cancel" :disabled="isLoading">
            Batal
          </button>
        </div>

        <div v-if="errorMessage" class="error-message">
          Error: {{ errorMessage }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "BarangKeluar",
  data() {
    return {
      assetList: [],
      keluarList: [],
      showModal: false,
      isLoading: false,
      errorMessage: "",
      form: { asset_id: "", tanggal: "", jumlah: null, keterangan: "" },
      currentPage: 1,
      perPage: 8,
      modalMode: "add",
      editingId: null,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.keluarList.length / this.perPage);
    },
    paginatedList() {
      const start = (this.currentPage - 1) * this.perPage;
      return this.keluarList.slice(start, start + this.perPage);
    },
  },
  mounted() {
    this.getAssets();
    this.getBarangKeluar();
  },
  methods: {
    openModal(mode) {
      this.modalMode = mode;
      this.showModal = true;
      if (mode === "add") this.form = { asset_id: "", tanggal: "", jumlah: null, keterangan: "" };
    },
    async getAssets() {
      const response = await axios.get("/api/assets");
      this.assetList = response.data;
    },
    async getBarangKeluar() {
      const response = await axios.get("/api/barang-keluar");
      this.keluarList = response.data;
    },
    editItem(item) {
      this.modalMode = "edit";
      this.editingId = item.id;
      this.form = { ...item, asset_id: item.asset_id };
      this.showModal = true;
    },
    async updateItem() {
      this.isLoading = true;
      try {
        const response = await axios.put(`/api/barang-keluar/${this.editingId}`, this.form);
        const index = this.keluarList.findIndex((i) => i.id === this.editingId);
        if (index !== -1) this.keluarList.splice(index, 1, response.data);
        this.showModal = false;
      } catch (error) {
        console.error(error);
        this.errorMessage = error.response?.data?.error || "Gagal update barang keluar!";
      } finally {
        this.isLoading = false;
      }
    },
    async deleteItem(id) {
      if (!confirm("Yakin ingin menghapus?")) return;
      try {
        await axios.delete(`/api/barang-keluar/${id}`);
        this.keluarList = this.keluarList.filter((i) => i.id !== id);
      } catch (error) {
        console.error(error);
        alert("Gagal menghapus barang keluar!");
      }
    },
    submitBarangKeluar() {
      if (this.modalMode === "add") {
        this.createItem();
      } else {
        this.updateItem();
      }
    },
    async createItem() {
      this.isLoading = true;
      this.errorMessage = "";
      try {
        const response = await axios.post("/api/barang-keluar", this.form);
        this.keluarList.push(response.data);
        this.showModal = false;
        this.form = { asset_id: "", tanggal: "", jumlah: null, keterangan: "" };
      } catch (error) {
        this.errorMessage = error.response?.data?.error || "Gagal menyimpan barang keluar!";
      } finally {
        this.isLoading = false;
      }
    },
    nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
    prevPage() { if (this.currentPage > 1) this.currentPage--; },
  },
};
</script>
