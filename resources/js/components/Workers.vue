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
          <v-col sm="12" md="3">
            <!-- This is the search component -->
            <v-expansion-panels multiple class="mb-1">
              <v-expansion-panel>
                <v-expansion-panel-header>Filtrar por creación de usuario</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-row no-gutters>
                    <v-col cols="12">
                      <h6>Buscar desde</h6>
                      <v-menu
                        ref="menuFrom"
                        v-model="menus.from"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedDateFrom"
                            label="Fecha de inicio"
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="searchModel.createdFrom"
                          no-title
                          @input="menus.from = false"
                          :allowed-dates="val  => {
                                if(searchModel.createdTo){
                                    return moment(val) <  moment(searchModel.createdTo)
                                }
                                return true
                                }"
                        />
                      </v-menu>
                    </v-col>
                    <v-col cols="12">
                      <h6>Buscar al</h6>
                      <v-menu
                        ref="menuTo"
                        v-model="menus.to"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedDateTo"
                            label="Fecha de final"
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="searchModel.createdTo"
                          no-title
                          @input="menus.to = false"
                          :allowed-dates="val  => {
                                if(searchModel.createdFrom){
                                    return moment(val) >  moment(searchModel.createdFrom)
                                }
                                return true
                                }"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Filtros de busqueda generales</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <h6>Datos del trabajador</h6>
                  <v-row no-gutters>
                    <v-col cols="12">
                      <v-text-field v-model="searchModel.firstName" label="Nombre del trabajador"></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field v-model="searchModel.lastName" label="Apellido del trabajador"></v-text-field>
                    </v-col>
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-col>
          <v-col sm="12" md="9">
            <v-card>
              <v-card-title>
                Tabla de trabajadores
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
              <v-data-table :headers="headers" :items="users" :search="search">
                <template
                  v-slot:item.created_at="{ item }"
                >{{moment(item.created_at).locale('es').format('ll')}}</template>
                <template
                  v-slot:item.verified_at="{ item }"
                >{{item.verified_at ? moment(item.verified_at).locale('es').format('ll') : 'Sin verificar'}}</template>
                <template v-slot:item.actions="{ item }">
                  <span v-if="!item.verified_at">
                    <v-btn icon @click="acceptWorker(item, true)">
                      <v-icon color="green">mdi-check</v-icon>
                    </v-btn>
                    <v-btn icon @click="() => acceptWorker(item, false)">
                      <v-icon color="red">mdi-close</v-icon>
                    </v-btn>
                  </span>
                  <span v-else>
                    <v-btn icon @click="navigateToDetail(item)">
                      <v-icon>mdi-eye</v-icon>
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
                    <v-tab>Información</v-tab>
                    <v-tab-item>
                      <v-card-text>
                        <v-row>
                          <v-col cols="3" justify="center">
                            <v-avatar
                              color="#DBDBDB"
                              size="100">
                              <v-icon size="50px">mdi-account</v-icon>
                            </v-avatar>
                          </v-col>
                          <v-col cols="12" md="4">
                            <h4>Nombre</h4>
                            <v-text-field :value="selected.first_name" readonly autofocus></v-text-field>
                          </v-col>
                          <v-col cols="12" md="4">
                            <h4>Apellido</h4>
                            <v-text-field :value="selected.last_name" readonly autofocus></v-text-field>
                          </v-col>
                          <v-col md="3">
                          </v-col>
                          <v-col cols="12" md="4">
                            <h4>Email</h4>
                            <v-text-field :value="selected.email" readonly autofocus></v-text-field>
                          </v-col>
                          <v-col cols="12" md="4">
                            <h4>Fecha Verificacion</h4>
                            <v-text-field :value="selected.verified_at" readonly autofocus></v-text-field>
                          </v-col>
                        </v-row>
                      </v-card-text>
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
  props: ["payload"],
  data: () => ({
    moment: moment,
    currentWindow: 1,
    currentTab: 0,
    snackbar: {
      show: false,
      text: "",
      color: "success",
    },
    menus: {
      from: false,
      to: false,
    },
    headers: [
      // {align: 'center', text: 'Id', value: 'id'},
      { align: "center", text: "Nombre", value: "first_name" },
      { align: "center", text: "Apellido", value: "last_name" },
      { align: "center", text: "e-mail", value: "email" },
      { align: "center", text: "Creado", value: "created_at" },
      { align: "center", text: "Verificado", value: "verified_at" },
      { align: "center", text: "Acciones", value: "actions" },
    ],
    isLoading: true,
    currentPage: 1,
    pageTotal: 1,
    selected: null,
    editSelect: null,
    users: [],
    clients: {},
    pagination: {},
    searchModel: {
      queryString: "",
      createdFrom: null,
      createdTo: null,
      firstName: null,
      lastName: null,
    },
    search: "",
    Nombre: "Javier",
    Apellido: "Ramirez",
    Email: "ram18099@uvg.edu.gt",
    Fecha: "21-10-2020",
  }),
  beforeMount() {
    this.pagination = this.payload;
    this.currentPage = this.payload.current_page;
    this.pageTotal = this.payload.last_page;
    this.users = this.payload.data;
    console.log(this.payload);
  },
  methods: {
    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split("-");
      return `${day}/${month}/${year}`;
    },
    acceptWorker(worker, accepted) {
      axios
        .post(`/workers/${worker.id}/accept`, { accepted: accepted ? 1 : 0 })
        .then((response) => {
          //showAlert
          this.snackbar.text = response.data.message;
          this.snackbar.color = "success";
          this.snackbar.show = true;
          if (accepted) {
            this.users = this.users.map((user) => {
              if (user.id == worker.id) {
                user.verified_at = this.moment();
              }
              return user;
            });
          } else {
            this.users = this.users.filter((user) => user.id != worker.id);
          }
        })
        .catch(() => {
          this.snackbar.text = "No se pudo procesar la accion";
          this.snackbar.color = "error";
          this.snackbar.show = true;
        });
    },
    fetchPage(next = undefined, page = 1) {
      let url = this.pagination.path + "?dataOnly=true&page=" + page;
      if (next !== undefined) {
        url = next
          ? this.pagination.next_page_url + "?dataOnly=true"
          : this.pagination.next_page_url + "?dataOnly=true";
      }
      url = Object.keys(this.searchModel).reduce((acc, val) => {
        let v = this.searchModel[val];
        if (v != "" && v) {
          acc = `${acc}&${val}=${v}`;
        }
        return acc;
      }, url);
      console.log(url);
      this.isLoading = true;
      axios
        .get(url)
        .then((response) => {
          let payloadAnswer = response.data.payload;
          console.log(response.data);
          this.currentPage = payloadAnswer.current_page;
          this.pageTotal = payloadAnswer.last_page;
          this.users = payloadAnswer.data;
          this.pagination = payloadAnswer;
          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;
        });
    },
    navigateToDetail(selected) {
      this.selected = selected;
      this.editSelect = { ...selected };
      this.currentWindow += 1;
      this.currentTab = 0;
    }
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
};
</script>

<style>
</style>