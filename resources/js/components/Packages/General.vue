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
            <v-expansion-panels class="mb-1">
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
            <v-card>
              <v-card-title>
                Paquetes
                <v-spacer></v-spacer>
                <v-text-field v-model="generalFilter" label="Buscar" single-line hide-details></v-text-field>
                <v-btn icon @click="() => fetchPage(undefined, 1)">
                  <v-icon>mdi-magnify</v-icon>
                </v-btn>
                <v-btn @click="createModalOpen = true" large icon color="black" dark class="ml-3">
                  <v-icon>mdi-plus</v-icon>
                </v-btn>
              </v-card-title>
              <v-data-table
                :loading="isLoading"
                :headers="headers"
                :items="packages"
                hide-default-footer
              >
                <template v-slot:item.actions="{ item }">
                  <v-btn icon @click="navigateToDetail(item)">
                    <v-icon>mdi-eye</v-icon>
                  </v-btn>
                  <v-btn icon @click="navigateToEdit(item)">
                    <v-icon>mdi-pencil</v-icon>
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
              <v-card class="w-100" outlined elevation="6">
                <v-tabs v-model="currentTab">
                  <v-tab>Información</v-tab>
                  <v-tab>Editar</v-tab>

                  <v-tab-item>
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
                              <v-text-field
                                :value="selected.currentLocker.letter"
                                readonly
                                autofocus
                              ></v-text-field>
                            </v-col>
                            <v-col>
                              <h6>Columna del rack</h6>
                              <v-text-field
                                :value="selected.currentLocker.column"
                                readonly
                                autofocus
                              ></v-text-field>
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
                  </v-tab-item>
                  <v-tab-item>
                    <v-form v-model="editForm">
                      <v-card-text>
                        <v-row>
                          <v-col cols="12" md="6">
                            <h4>Código de barras</h4>
                            <v-text-field
                              clearable
                              v-model="editSelect.bar_code"
                              :rules="[rules.required]"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="12" md="6">
                            <div>
                              <h4 style="display: inline;">Número de lote</h4>
                              <v-chip
                                class="ml-4"
                                small
                                outlined
                                :color="editSelect.lot.is_delivered == 1 ? 'green' : 'red'"
                              >{{editSelect.lot.is_delivered == 1 ? 'Entregado' : 'Pendiente'}}</v-chip>
                            </div>
                            <v-autocomplete
                              clearable
                              :rules="[rules.required]"
                              v-model="editSelect.lot_id"
                              :items="filteredLots"
                              label="Lote"
                              :item-text="(item) => `Número ${item.number} - ${moment(item.cut_date).locale('es').format('ll')}`"
                              item-value="id"
                              placeholder="Ingresa el numero de lote"
                            ></v-autocomplete>
                          </v-col>
                        </v-row>
                      </v-card-text>
                      <v-card-actions>
                        <v-btn @click="clearEdit" outlined>Limpiar campos</v-btn>
                        <v-btn
                          :disabled="!editForm"
                          @click="updatePackage"
                          color="success"
                          outlined
                        >Aceptar</v-btn>
                      </v-card-actions>
                    </v-form>
                  </v-tab-item>
                </v-tabs>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </v-window-item>
    </v-window>
    <NewPackageModal
      v-model="createModalOpen"
      width="80%"
      max-width="850"
      :payload="{rows, letters,columns, lots, statuses, payload, onSuccess}"
    />
  </v-container>
</template>
<script>
import NewPackageModal from "./NewPackageModal";
import axios from "axios";
import moment from "moment";
export default {
  props: ["rows", "letters", "columns", "lots", "statuses", "payload"],
  components: { NewPackageModal },
  data: () => ({
    moment: moment,
    currentWindow: 1,
    editSelect: null,
    editForm: true,
    currentTab: 0,
    snackbar: {
      show: false,
      text: "",
      color: "success",
    },
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
    createModalOpen: false,
    rules: {
      required: (v) => !!v || "Es un campo obligatorio",
    },
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
      this.editSelect = { ...selected };
      this.currentWindow++;
      this.currentTab = 0;
    },
    navigateToEdit(selected) {
      this.selected = selected;
      this.editSelect = { ...selected };
      this.currentWindow++;
      this.currentTab = 1;
    },
    updatePackage() {
      axios
        .put(`/packages/${this.editSelect.id}`, {
          bar_code: this.editSelect.bar_code,
          lot_id: this.editSelect.lot_id,
        })
        .then(({ data }) => {
          this.packages = this.packages.map((pkg) => {
            if (data.package.id == pkg.id) {
              return data.package;
            }
            return pkg;
          });
          this.selected = data.package;
          this.snackbar.text = "El paquete se actualizo con exito.";
          this.snackbar.color = "success";
          this.snackbar.show = true;
          this.currentWindow--;
        })
        .catch(() => {
          this.snackbar.text = "No se pudo actualizar el paquete";
          this.snackbar.color = "error";
          this.snackbar.show = true;
        });
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
        });
    },
    cleanPage() {
      let url = this.pagination.path + "?dataOnly=true&page=1";
      axios.get(url).then((response) => {
        this.pagination = response.data.books;
      });
    },
    onSuccess(text) {
      this.snackbar.text = text;
      this.snackbar.color = "success";
      this.snackbar.show = true;
    },
    clearEdit() {
      this.editSelect = { ...this.selected };
    },
    edit() {},
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
    filteredLots() {
      if (this.editSelect == null) {
        return [];
      }
      return this.lots.filter(
        (lot) => lot.client.id == this.editSelect.lot.client.id
      );
    },
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
