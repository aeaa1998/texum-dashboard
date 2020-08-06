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
            v-model="search"
            append-icon="mdi-magnify"
            label="Búsqueda detallada"
            single-line
            hide-details></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          hide-default-footer>
        </v-data-table>
        <div>
          <v-pagination
            v-model="currentPage"
            :total-visible="10"
            circle>

          </v-pagination>
        </div>
      </v-card>
    </v-container>
    

  </div>
</template>

<script>
import axios from "axios"
export default {
  
  data: () => ({
    headers: [
    {text: 'Nombre', align: 'center', value: 'nombre'},
    {text: 'Apellido', align: 'center', value: 'apellido'},
    {text: 'Correo', align: 'center', value: 'correo'},
    {text: 'Cargo', align: 'center', value: 'cargo'}
    ],
    isLoading: true,
    currentPage: 1,
  }),
  
  
}
</script>

<style>

</style>