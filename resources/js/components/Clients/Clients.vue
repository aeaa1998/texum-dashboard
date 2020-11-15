<template>
  <v-container class="mt-2 fluid">
    <v-snackbar
      :color="snackbar.color"
      top
      multi-line
      v-model="snackbar.show"
      :timeout="4000"
      >{{ snackbar.text }}</v-snackbar
    >
    <v-window v-model="currentWindow">
      <v-window-item :value="1">
        <v-row justify="center">
          <v-col sm="12" md="9">
            <v-card>
              <v-card-title>
                Tabla de Clientes
                <v-spacer></v-spacer>
                <v-text-field
                  append-icon="mdi-magnify"
                  label="Búsqueda detallada"
                  v-model="search"
                  single-line
                  hide-details
                ></v-text-field>
                <v-btn
                  @click="createModalOpen = true"
                  large
                  icon
                  color="black"
                  dark
                  class="ml-3"
                >
                  <v-icon>mdi-plus</v-icon>
                </v-btn>
              </v-card-title>
              <v-data-table
                :headers="headers"
                :items="clients"
                :search="search"
              >
                <template v-slot:item.created_at="{ item }">
                  {{ moment(item.created_at).locale("es").format("ll") }}
                </template>
                <template v-slot:item.actions="{ item }">
                  <span>
                    <v-btn icon @click="navigateToDetail(item)">
                      <v-icon>mdi-eye</v-icon>
                    </v-btn>
                  </span>
                </template>
              </v-data-table>
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
                Detalle del Cliente
                <v-card class="w-100" outlined elevation="6">
                  <v-tabs v-model="currentTab">
                    <v-tab>Información</v-tab>
                    <v-tab>Editar</v-tab>
                    <v-tab-item>
                      <v-card-text>
                        <v-row>
                          <v-col cols="12" md="4">
                            <h4>Nombre</h4>
                            <v-text-field
                              :value="selected.name"
                              readonly
                              autofocus
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-tab-item>
                    <v-tab-item>
                      <v-card>
                        <v-form v-model="valid">
                          <v-card-text>
                            <v-row>
                              <v-col cols="12">
                                <v-text-field
                                  v-model="clients.name"
                                  label="Nombre"
                                >
                                </v-text-field>
                              </v-col>
                              <v-col class="text-right">
                                <v-btn
                                  :disabled="!valid"
                                  @click="changeName()"
                                  align="right"
                                  >Editar</v-btn
                                >
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
    <NewClientModal
      v-model="createModalOpen"
      width="80%"
      max-width="850"
      :onSuccess="onSuccess"
    />
  </v-container>
</template>

<script>
import axios from "axios";
import moment from "moment";
import NewClientModal from "./NewClientModal";
export default {
  props: ["payload"],
  components: {
    NewClientModal,
  },
  data: () => ({
    moment: moment,
    createModalOpen: false,
    currentWindow: 1,
    currentTab: 0,
    alertDetails: {
      show: false,
      message: "",
      type: "success",
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
      { align: "center", text: "id", value: "id" },
      { align: "center", text: "Nombre", value: "name" },
      { align: "center", text: "Creado", value: "created_at" },
      { align: "center", text: "Acciones", value: "actions" },
    ],
    searchModel: {
      queryString: "",
      createdFrom: null,
      createdTo: null,
      firstName: null,
      lastName: null,
    },
    clientes: [],
    search: "",
    isLoading: true,
    currentPage: 1,
    pageTotal: 1,
    valid: true,
    pagination: {},
    selected: null,
    editSelect: null,
  }),
  beforeMount() {
    this.clients = this.payload;
  },
  methods: {
    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split("-");
      return `${day}/${month}/${year}`;
    },
    navigateToDetail(selected) {
      this.selected = selected;
      this.editSelect = { ...selected };
      this.currentWindow += 1;
      this.currentTab = 0;
    },
    onSuccess(text, payload) {
      if (payload) {
        let holder = [...this.clients];
        holder.push(payload.data);
        this.clients = holder;
        this.clients.sort((r, l) => r.id - l.id);
      }
      this.snackbar.text = text;
      this.snackbar.color = "success";
      this.snackbar.show = true;
    },
    changeName() {
      axios
        .put(`/client/${this.selected.id}`, {
          name: this.clients.name,
        })
        .then((response) => {
          this.snackbar.text = "Nombre Actualizado con exito";
          this.snackbar.color = "success";
          this.snackbar.show = true;
        })
        .catch(() => {
          this.snackbar.text = "No se pudo procesar la accion";
          this.snackbar.color = "error";
          this.snackbar.show = true;
        });
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
    computedDateFrom: {
      get: function get() {
        return this.formatDate(this.searchModel.createdFrom);
      },
      set: function set(value) {
        this.searchModel.createdFrom = value;
      },
    },
    computedDateTo: {
      get: function get() {
        return this.formatDate(this.searchModel.createdTo);
      },
      set: function set(value) {
        this.searchModel.createdTo = value;
      },
    },
  },
};
</script>