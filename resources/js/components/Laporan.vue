<template>
  <div class="table-container">
    <div class="table-header">
      <h2 class="table-title">Laporan Barang</h2>
    </div>

    <div class="filter-container">
      <input type="date" v-model="filterStart" />
      <input type="date" v-model="filterEnd" />
      <button class="btn-filter" @click="filterLaporan">Filter</button>
    </div>

    <table class="modern-table">
      <thead>
        <tr>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Stock Awal</th>
          <th>Total Masuk</th>
          <th>Total Keluar</th>
          <th>Stok Akhir</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in paginatedList" :key="item.id">
          <td>{{ item.kode }}</td>
          <td>{{ item.nama }}</td>
          <td>{{ item.stock_awal }}</td>
          <td>{{ item.total_masuk }}</td>
          <td>{{ item.total_keluar }}</td>
          <td>{{ item.stok_akhir }}</td>
        </tr>
      </tbody>
    </table>

    <div class="pagination">
      <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
      <span>Page {{ currentPage }} / {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Laporan",
  data() {
    return {
      laporanList: [],
      filterStart: "",
      filterEnd: "",
      currentPage: 1,
      perPage: 8, 
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.laporanList.length / this.perPage);
    },
    paginatedList() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.laporanList.slice(start, end);
    },
  },
  mounted() {
    this.getLaporan();
  },
  methods: {
    async getLaporan(start = null, end = null) {
      try {
        const params = {};
        if (start) params.start = start;
        if (end) params.end = end;

        const response = await axios.get("http://127.0.0.1:8000/api/laporan", { params });
        this.laporanList = response.data;
      } catch (error) {
        console.error("Gagal mengambil laporan:", error);
      }
    },
    filterLaporan() {
      this.currentPage = 1;
      this.getLaporan(this.filterStart, this.filterEnd);
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