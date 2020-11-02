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
        <v-data-table :headers="headers" :items="clients" :search="search">
          <template v-slot:item.actions="{ item }">
            <span>
              <v-btn icon @click="navigateToDetail(item)">
                <v-icon>mdi-eye</v-icon>
              </v-btn>
            </span>
          </template>
          <!-- hide-default-footer agregar con el pagination -->
        </v-data-table>
        <div>
          <v-pagination
            v-model="currentPage"
            :length="pageTotal"
            :total-visible="10"
            circle
            @input="(val) => fetchPage(undefined, val)"
          ></v-pagination>
        </div>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import axios from "axios";
import moment from "moment";
export default {
  props: ["clients"],
  data: () => ({
    moment: moment,
    currentWindow: 1,
    currentTab: 0,
    alertDetails: {
      show: false,
      message: "",
      type: "success"
    },
    snackbar: {
      show: false,
      text: "",
      color: "success",
    },
    newName: "",
    menus: {
      from: false,
      to: false,
    },
    headers: [
      {align: 'center', text: 'id', value: 'id'},
      {align: 'center', text: 'Nombre', value: 'name'},
      {align: 'center', text: 'Creado', value: 'created_at'},
      {align: "center", text: "Acciones", value: "actions" },
    ],
    clientes: [],
    search: '',
    isLoading: true,
    currentPage: 1,
    pageTotal: 1,
    pagination: {},
    selected: null,
    editSelect: null,
  }),
  beforeMount() {
    this.clientes = this.clients.data
    console.log(this.clientes)
  },
  methods: {
    navigateToDetail(selected) {
      this.selected = selected;
      this.editSelect = { ...selected};
      this.currentWindow += 1;
      this.currentTab = 0;
    },
    changeName() {
      axios
        .post(`/clients/${client.id}`, { 
          name: this.name,
        })
        .then((response) => {
          this.snackbar.text = "Nombre actualizado con exito";
          this.snackbar.color = "success";
          this.snackbar.show = true;
        })
        .catch(() => {
          this.snackbar.text = "No se pudo procesar la accion";
          this.snackbar.color = "error";
          this.snackbar.show = true;
        });
    }
  }
}
</script>