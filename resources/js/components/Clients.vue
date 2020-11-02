<template>
  <div class="mh-100 main-bg">
    <v-container>
      <v-card>
        <v-card-title>
          Clientes
          <v-spacer></v-spacer>
          <v-text-field
            append-icon="mdi-magnify"
            label="Búsqueda detallada"
            v-model="search"
            search="search"
            single-line
            hide-details></v-text-field>
            <!-- <v-btn @click="createModalOpen = true" large icon color="black" dark class="ml-3">
                <v-icon>mdi-plus</v-icon>
            </v-btn> -->
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="clients"
          :search="search"
          >
          <!-- hide-default-footer agregar con el pagination -->
        </v-data-table>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import axios from "axios"
export default {
  props: ["clients"],
  data: () => ({
    headers: [
    {align: 'center', text: 'id', value: 'id'},
    {align: 'center', text: 'Numero', value: 'name'},
    {align: 'center', text: 'Creado', value: 'created_at'},
    {align: 'center', text: 'Acciones'}
    ],
    clientes: [],
    search: '',
  }),
  beforeMount() {
    this.clientes = this.clients.data
    console.log(this.clientes)
  }
}
</script>