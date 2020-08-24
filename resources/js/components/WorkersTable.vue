<template>
  <div class="mh-100 main-bg">
    <v-container>
      <v-card>
        <div v-if="isLoading">
          <v-skeleton-loader>
          </v-skeleton-loader>
        </div>
        <v-card-title>
          Tabla de trabajadores
          <v-spacer></v-spacer>
          <v-text-field
            append-icon="mdi-magnify"
            label="Búsqueda detallada"
            v-model="search"
            search="search"
            single-line
            hide-details></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="workerstable"
          :search="search"
          >
          <!-- hide-default-footer agregar con el pagination -->
        </v-data-table>
        <div>
          <!-- <v-pagination
            v-model="currentPage"
            :length="pageTotal"
            :total-visible="10"
            circle>

          </v-pagination> -->
        </div>
      </v-card>
    </v-container>
    

  </div>
</template>

<script>
import axios from "axios"
export default {
  props: ["workerstable"],
  data: () => ({
    headers: [
    // {align: 'center', text: 'Id', value: 'id'},
    {align: 'center', text: 'Nombre', value: 'first_name'},
    {align: 'center', text: 'Apellido', value: 'last_name'},
    {align: 'center', text: 'Creado', value: 'created_at'},
    ],
    isLoading: true,
    currentPage: 1,
    pageTotal:1,
    workers: [],
    clients: {},
    search: '',
  }),

  beforeMount() {
    // this.currentPage = this.workerstable.current_page;
    // this.pageTotal = this.workerstable.last_page;
    this.workers = this.workerstable.data
    console.log(this.workerstable)
  }
  
  
}
</script>

<style>

</style>