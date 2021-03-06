<template>
  <v-container class="mt-2 fluid">
    <v-snackbar
      :color="snackbar.color"
      top
      multi-line
      v-model="snackbar.show"
      :timeout="4000"
    >{{ snackbar.text }}</v-snackbar>
    <v-window v-model="currentWindow">
      <v-window-item :value="1">
        <v-row justify="center">
          <v-col sm="12" md="9">
            <v-card>
              <v-card-title>
                Tabla de Roles de Trabajadores
                <v-spacer></v-spacer>
                <v-text-field
                  append-icon="mdi-magnify"
                  label="Búsqueda detallada"
                  v-model="searchModel.queryString"
                  search="search"
                  single-line
                  hide-details
                ></v-text-field>
              </v-card-title>
              <v-data-table :headers="headers" :items="roles" :search="search">
                <template v-slot:item.role_id="{ item }">
                  {{roleIdentifier(item.role_id)}}
                </template>
                <template v-slot:item.created_at="{ item }">
                  {{moment(item.created_at).locale('es').format('ll')}}
                </template>
                <template v-slot:item.actions="{ item }">
                  <span>
                    <v-btn icon @click="navigateToEdit(item)">
                      <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                  </span>
                </template>
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
          </v-col>
        </v-row>
      </v-window-item>
      <v-window-item :value="2">
        <div v-if="selected">
          <v-row align-content="center" justify="center">
            <v-col cols="12" md="10">
              <h2>
                <v-btn icon @click="currentWindow--" class="mx-4">
                  <v-icon x-large>mdi-arrow-left</v-icon>
                </v-btn>
                Detalle del Trabajador
                <v-card class="w-100" outlined elevation="6">
                  <v-tabs v-model="currentTab">
                    <v-tab>Editar</v-tab>
                    <v-tab-item>
                      <v-card>
                        <v-form
                          v-model="valid">
                          <v-card-text>
                            <v-row>
                              <v-col cols="12">
                                <v-text-field
                                  v-model="roles.role"
                                  label="Role">
                                </v-text-field>
                              </v-col>
                              <v-col class="text-right">
                                <v-btn :disabled="!valid" @click="changeRole()" align="right">Editar</v-btn>
                              </v-col>
                            </v-row>
                          </v-card-text>
                        </v-form>
                      </v-card>
                    </v-tab-item>
                  </v-tabs>
                </v-card>
              </h2>
            </v-col>
          </v-row>
        </div>
      </v-window-item>
    </v-window>
  </v-container>
</template>

<script>
import axios from "axios";
import moment from "moment";
export default {
  props: ["roles"],
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
      {align: 'center', text: 'Email', value: 'email'},
      {align: 'center', text: 'Rol', value: 'role_id'},
      {align: 'center', text: 'Creado', value: 'created_at'},
      {align: "center", text: "Acciones", value: "actions" },
    ],
    searchModel: {
      queryString: "",
      createdFrom: null,
      createdTo: null,
      firstName: null,
      lastName: null,
    },
    rols: [],
    search: '',
    isLoading: true,
    currentPage: 1,
    pageTotal: 1,
    valid: true,
    pagination: {},
    selected: null,
    editSelect: null,
  }),
  beforeMount() {
    this.rols = this.roles.data
    console.log(this.roles)
  },
  methods: {
    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split("-");
      return `${day}/${month}/${year}`;
    },
    navigateToEdit(selected) {
      this.selected = selected;
      this.editSelect = { ...selected };
      this.currentWindow += 1;
      this.currentTab = 0;
    },
    changeRole() {
      console.log(this.selected.id)
      console.log(this.roles.role)
      axios
        .put(`/roles/${this.selected.id}`, { 
          role: this.roles.role,
        })
        .then((response) => {
          this.snackbar.text = "Rol Actualizado con exito";
          this.snackbar.color = "success";
          this.snackbar.show = true;
        })
        .catch(() => {
          this.snackbar.text = "No se pudo procesar la accion";
          this.snackbar.color = "error";
          this.snackbar.show = true;
        });
    },
    roleIdentifier(rol) {
      if(rol == 1) return `Trabajador`;
      if(rol == 2) return `Administrador`;
      if(rol == 3) return `Jefe`;
    },
  },
  watch: {
    searchModel: {
      handler: function (val, oldVal) {
        this.fetchPage(undefined, 1);
      },
      deep: true,
    },
  },
  computed: {
    computedDateFrom() {
      return this.formatDate(this.searchModel.createdFrom);
    },
    computedDateTo() {
      return this.formatDate(this.searchModel.createdTo);
    },
  },
}
</script>