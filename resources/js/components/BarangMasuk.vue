<template>
  <div class="table-container">
    <div class="table-header">
      <h2 class="table-title">Barang Masuk</h2>
      <button class="btn-add" @click="openModal('add')">Tambah Barang Masuk</button>
    </div>

    <table class="modern-table">
      <thead>
        <tr>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Jumlah Masuk</th>
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
            <button @click="openModal('edit', item)" class="btn-edit">Edit</button>
            <button @click="deleteBarangMasuk(item.id)" class="btn-delete">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="pagination">
      <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
      <span>Page {{ currentPage }} / {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>

    <div class="modal" :class="{ 'modal-active': showModal }">
      <div class="modal-content">
        <h3>{{ modalMode === 'add' ? 'Tambah Barang Masuk' : 'Edit Barang Masuk' }}</h3>

        <select v-model="form.asset_id">
          <option disabled value="">-- Pilih Barang --</option>
          <option v-for="asset in assetList" :key="asset.id" :value="asset.id">
            {{ asset.kode }} - {{ asset.nama }}
          </option>
        </select>

        <input type="date" v-model="form.tanggal" />
        <input type="number" v-model.number="form.jumlah" min="1" placeholder="Jumlah Masuk" />
        <input type="text" v-model="form.keterangan" placeholder="Keterangan (opsional)" />

        <div class="modal-actions">
          <button
            @click="modalMode === 'add' ? submitBarangMasuk() : updateBarangMasuk()"
            class="btn-save"
            :disabled="isLoading"
          >
            <span v-if="isLoading" class="spinner-small"></span>
            <span v-else>Simpan</span>
          </button>
          <button @click="showModal = false" class="btn-cancel" :disabled="isLoading">Batal</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "BarangMasuk",
  data() {
    return {
      assetList: [],
      masukList: [],
      showModal: false,
      isLoading: false,
      modalMode: "add",
      editingId: null,
      form: { asset_id: "", tanggal: "", jumlah: null, keterangan: "" },
      currentPage: 1,
      perPage: 8,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.masukList.length / this.perPage);
    },
    paginatedList() {
      const start = (this.currentPage - 1) * this.perPage;
      return this.masukList.slice(start, start + this.perPage);
    },
  },
  mounted() {
    this.getAssets();
    this.getBarangMasuk();
  },
  methods: {
    async getAssets() {
      try {
        const response = await axios.get("/api/assets");
        this.assetList = response.data;
      } catch (error) {
        console.error("Gagal mengambil daftar barang:", error);
      }
    },
    async getBarangMasuk() {
      try {
        const response = await axios.get("/api/barang-masuk");
        this.masukList = response.data;
      } catch (error) {
        console.error("Gagal mengambil barang masuk:", error);
      }
    },
    openModal(mode, item = null) {
      this.modalMode = mode;
      if (mode === "add") {
        this.form = { asset_id: "", tanggal: "", jumlah: null, keterangan: "" };
        this.editingId = null;
      } else if (mode === "edit" && item) {
        this.form = { ...item, asset_id: item.asset.id };
        this.editingId = item.id;
      }
      this.showModal = true;
    },
    async submitBarangMasuk() {
      if (!this.form.asset_id || !this.form.tanggal || !this.form.jumlah) {
        alert("Lengkapi semua field!");
        return;
      }
      if (this.form.jumlah <= 0) {
        alert("Jumlah harus lebih dari 0!");
        return;
      }

      this.isLoading = true;
      try {
        const response = await axios.post("/api/barang-masuk", this.form);
        this.masukList.push(response.data);
        this.form = { asset_id: "", tanggal: "", jumlah: null, keterangan: "" };
        this.showModal = false;
        alert("Barang masuk berhasil disimpan!");
      } catch (error) {
        console.error("Gagal menyimpan barang masuk:", error);
        alert("Gagal menyimpan barang masuk!");
      } finally {
        this.isLoading = false;
      }
    },
    async updateBarangMasuk() {
      if (!this.form.asset_id || !this.form.tanggal || !this.form.jumlah) {
        alert("Lengkapi semua field!");
        return;
      }

      this.isLoading = true;
      try {
        const response = await axios.put(`/api/barang-masuk/${this.editingId}`, this.form);
        const index = this.masukList.findIndex((a) => a.id === this.editingId);
        if (index !== -1) this.masukList.splice(index, 1, response.data);
        this.showModal = false;
      } catch (error) {
        console.error("Gagal mengupdate barang masuk:", error);
        alert("Gagal mengupdate barang masuk!");
      } finally {
        this.isLoading = false;
      }
    },
    async deleteBarangMasuk(id) {
      if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) return;

      try {
        await axios.delete(`/api/barang-masuk/${id}`);
        this.masukList = this.masukList.filter((item) => item.id !== id);
      } catch (error) {
        console.error("Gagal menghapus barang masuk:", error);
        alert("Gagal menghapus barang masuk!");
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
    prevPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
  },
};
</script>
