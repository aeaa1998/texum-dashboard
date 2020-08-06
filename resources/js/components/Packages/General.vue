<template>
  <v-container class="mt-2 fluid">
    <v-window v-model="currentWindow">
      <v-window-item :value="1">
        <v-row justify="center">
          <v-col sm="12" md="3">
            <div v-if="isLoading">
              <v-skeleton-loader v-for="i in 2" :key="i" type="list-item" class="mx-auto my-1"></v-skeleton-loader>
            </div>

            <!-- This is the search component -->
            <v-expansion-panels v-else class="mb-1">
              <v-expansion-panel>
                <v-expansion-panel-header>Filtros de busqueda</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-form v-model="isValidForm">
                    <h4>Datos del paquete</h4>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field v-model="barCode" label="Codigo de barras"></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <v-select
                          clearable
                          v-model="statusId"
                          :items="statuses"
                          :item-text="(item) => (item.name)"
                          :item-value="(item) => (item.id)"
                          label="Status del paquete"
                        ></v-select>
                      </v-col>
                      <v-col cols="12">
                        <v-select
                          clearable
                          v-model="lotId"
                          :items="lots"
                          :item-text="(item) => (`Lote: ${item.client.name} - ${item.number}`)"
                          :item-value="(item) => (item.id)"
                          label="Lote: Cliente - Número"
                        ></v-select>
                      </v-col>
                    </v-row>
                    <h4>Ubicación del paquete</h4>
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-select
                          clearable
                          v-model="letter"
                          :items="letters"
                          label="Letra del rack"
                        ></v-select>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-select clearable v-model="row" :items="rows" label="Fila del rack">
                          <template v-slot:no-data>
                            <div class="pa-2">No hay filas con la letra del rack seleccionado</div>
                          </template>
                        </v-select>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-select
                          clearable
                          v-model="column"
                          :items="columns"
                          label="Column del rack"
                        >
                          <template v-slot:no-data>
                            <div
                              class="pa-2"
                            >No hay columnas con la letra y fila del rack seleccionado</div>
                          </template>
                        </v-select>
                      </v-col>
                    </v-row>
                  </v-form>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-col>
          <v-col sm="12" md="9">
            <div v-if="isLoading">
              <v-skeleton-loader type="table" class="mx-auto my-1"></v-skeleton-loader>
            </div>
            <v-card v-else>
              <v-card-title>
                Paquetes
                <v-spacer></v-spacer>
                <v-text-field v-model="generalFilter" label="Buscar" single-line hide-details></v-text-field>
                <v-btn icon>
                  <v-icon>mdi-magnify</v-icon>
                </v-btn>
              </v-card-title>
              <v-data-table :headers="headers" :items="packages" hide-default-footer>
                <template v-slot:item.actions="{ item }">
                  <v-btn icon @click="navigateToDetail(item)">
                    <v-icon>mdi-eye</v-icon>
                  </v-btn>
                </template>
                <template
                  v-slot:item.actualLocker="{ item }"
                >{{`${item.currentLocker.letter} - ${item.currentLocker.row} - ${item.currentLocker.column}`}}</template>
              </v-data-table>
              <div>
                <v-pagination
                  v-model="currentPage"
                  :length="pageTotal"
                  circle
                  :total-visible="10"
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
                </v-btn>Detalle del paquete
              </h2>
              <v-card class="w-100" outlined>
                <v-card-text>
                  <v-row>
                    <v-col cols="12" md="4">
                      <h4>Código de barras</h4>
                      <v-text-field :value="selected.bar_code" readonly autofocus></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <h4>Cliente al que pertence</h4>
                      <v-text-field :value="selected.client.name" readonly autofocus></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <div>
                        <h4 style="display: inline;">Número de lote</h4>
                        <v-chip
                          class="ml-4"
                          small
                          outlined
                          :color="selected.lot.is_delivered == 1 ? 'green' : 'red'"
                        >{{selected.lot.is_delivered == 1 ? 'Entregado' : 'Pendiente'}}</v-chip>
                      </div>
                      <v-text-field :value="selected.lot.number" readonly autofocus></v-text-field>
                    </v-col>
                    <v-col md="8" cols="12">
                      <h4>Locker actual</h4>
                      <v-row v-if="selected.status_id == 3">
                        <v-col>
                          <h6>Letra del rack</h6>
                          <v-text-field :value="selected.currentLocker.letter" readonly autofocus></v-text-field>
                        </v-col>
                        <v-col>
                          <h6>Columna del rack</h6>
                          <v-text-field :value="selected.currentLocker.column" readonly autofocus></v-text-field>
                        </v-col>
                        <v-col>
                          <h6>Fila del rack</h6>
                          <v-text-field :value="selected.currentLocker.row" readonly autofocus></v-text-field>
                        </v-col>
                      </v-row>
                      <div
                        v-else
                      >El paquete actualmente no se encuentra colocado en bodega y no tiene un locker asignado.</div>
                    </v-col>
                    <v-col md="4" cols="12">
                      <h4>Estatus del paquete</h4>
                      <div class="pa-4">
                        <span
                          :class="`text-h6 ${selectedPackageStatusColor}--text mr-4`"
                        >{{selected.status.name}}</span>
                        <v-icon
                          class="mx-4"
                          x-large
                          :color="selectedPackageStatusColor"
                        >{{selectedPackageStatusIcon}}</v-icon>
                      </div>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </v-window-item>
    </v-window>
  </v-container>
</template>
<script>
import axios from "axios";
export default {
  props: ["rows", "letters", "columns", "lots", "statuses", "payload"],
  data: () => ({
    currentWindow: 1,
    headers: [
      { align: "center", text: "Código de barras", value: "bar_code" },
      { align: "center", text: "Cliente", value: "client.name" },
      { align: "center", text: "Número de lote", value: "lot.number" },
      { align: "center", text: "Status", value: "status.name" },
      {
        align: "center",
        text: "Locker (Letra-Fila-Columna)",
        value: "actualLocker",
      },
      { align: "center", text: "Acciones", value: "actions" },
    ],
    currentPage: 1,
    pageTotal: 1,
    packages: [],
    pagination: {},
    selected: null,
    isLoading: false,
    isValidForm: false,
    generalFilter: "",
    barCode: "",
    lotId: null,
    statusId: null,
    letter: null,
    row: null,
    column: null,
  }),
  beforeMount() {
    this.pagination = this.payload;
    this.currentPage = this.payload.current_page;
    this.pageTotal = this.payload.last_page;
    this.packages = this.payload.data;
  },
  methods: {
    navigateToDetail(selected) {
      this.selected = selected;
      this.currentWindow++;
    },
    fetchPage(next = undefined, page = 1) {
      let url = this.pagination.path + "?dataOnly=true&page=" + page;
      if (next !== undefined) {
        url = next
          ? this.pagination.next_page_url
          : this.pagination.next_page_url;
      }
      if (this.generalFilter != "") {
        url = `${url}&query=${this.generalFilter}`;
      }
      if (this.barCode != "") {
        url = `${url}&bar_code=${this.barCode}`;
      }
      if (this.statusId) {
        url = `${url}&status_id=${this.statusId}`;
      }
      if (this.lotId) {
        url = `${url}&lot_id=${this.lotId}`;
      }
      if (this.letter) {
        url = `${url}&letter=${this.letter}`;
      }
      if (this.column) {
        url = `${url}&column=${this.column}`;
      }
      if (this.row) {
        url = `${url}&row=${this.row}`;
      }
      this.isLoading = true;
      axios
        .get(url)
        .then((response) => {
          let payloadAnswer = response.data.payload;
          this.currentPage = payloadAnswer.current_page;
          this.pageTotal = payloadAnswer.last_page;
          this.packages = payloadAnswer.data;
          this.pagination = payloadAnswer;
          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;
          console.log(error);
        });
    },
    cleanPage() {
      let url = this.pagination.path + "?dataOnly=true&page=1";
      axios.get(url).then((response) => {
        this.pagination = response.data.books;
      });
    },
  },
  watch: {
    generalFilter(val) {
      this.fetchPage(undefined, 1);
    },
    statusId() {
      this.fetchPage(undefined, 1);
    },
    lotId(val) {
      this.fetchPage(undefined, 1);
    },
    barCode() {
      this.fetchPage(undefined, 1);
    },
    letter() {
      this.fetchPage(undefined, 1);
    },
    column() {
      this.fetchPage(undefined, 1);
    },
    row() {
      this.fetchPage(undefined, 1);
    },
    selected(value, oldValue) {},
  },
  computed: {
    selectedPackageStatusIcon() {
      if (this.selected) {
        if (this.selected.status_id == 3) {
          return "mdi-warehouse";
        } else if (this.selected.status_id == 2) {
          return "mdi-location-enter";
        } else if (this.selected.status_id == 1) {
          return "mdi-account-hard-hat";
        } else {
          return "mdi-account-clock";
        }
      }
      return "mdi-warehouse";
    },
    selectedPackageStatusColor() {
      if (this.selected) {
        if (this.selected.status_id == 3) {
          return "green";
        } else if (this.selected.status_id == 2) {
          return "orange";
        } else if (this.selected.status_id == 1) {
          return "blue";
        } else {
          return "orange";
        }
      }
      return "green";
    },
  },
};
</script>
<style scoped>
.text-h6 {
  font-weight: 500;
  font-size: 1.25rem;
}
</style>