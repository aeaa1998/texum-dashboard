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

    <v-row justify="center">
      <v-col sm="12" md="3">
        <!-- This is the search component -->
        <v-expansion-panels multiple class="mb-1">
          <v-expansion-panel>
            <v-expansion-panel-header
              >Filtrar por fechas</v-expansion-panel-header
            >
            <v-expansion-panel-content>
              <v-row no-gutters>
                <v-col cols="12">
                  <h6>Buscar desde</h6>
                  <v-menu
                    ref="menuFrom"
                    v-model="menuFrom"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        readonly
                        clearable
                        v-model="computedDateFrom"
                        label="Fecha de inicio"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="dateFrom"
                      no-title
                      @input="menuFrom = false"
                      :allowed-dates="
                        (val) => {
                          if (dateTo) {
                            return moment(val) < moment(dateTo);
                          }
                          return true;
                        }
                      "
                    />
                  </v-menu>
                </v-col>
                <v-col cols="12">
                  <h6>Buscar al</h6>
                  <v-menu
                    ref="menuTo"
                    v-model="menuTo"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        readonly
                        clearable
                        v-model="computedDateTo"
                        label="Fecha de final"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="dateTo"
                      no-title
                      @input="menuTo = false"
                      :allowed-dates="
                        (val) => {
                          if (dateFrom) {
                            return moment(val) > moment(dateFrom);
                          }
                          return true;
                        }
                      "
                    ></v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>
          <v-expansion-panel>
            <v-expansion-panel-header
              >Filtros de busqueda generales</v-expansion-panel-header
            >
            <v-expansion-panel-content>
              <v-form v-model="isValidForm">
                <h6>Datos del paquete</h6>
                <v-row no-gutters>
                  <v-col cols="12">
                    <v-text-field
                      v-model="barCode"
                      label="Codigo de barras"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-select
                      clearable
                      v-model="lotId"
                      :items="lots"
                      :item-text="
                        (item) => `Lote: ${item.client.name} - ${item.number}`
                      "
                      :item-value="(item) => item.id"
                      label="Lote: Cliente - Número"
                    ></v-select>
                  </v-col>
                </v-row>
              </v-form>
            </v-expansion-panel-content>
          </v-expansion-panel>
          <v-expansion-panel>
            <v-expansion-panel-header
              >Ubicación del paquete</v-expansion-panel-header
            >
            <v-expansion-panel-content>
              <h6>Ubicación actual del paquete</h6>
              <v-row>
                <v-col cols="12" md="6">
                  <v-select
                    clearable
                    v-model="oldLetter"
                    :items="letters"
                    label="Letra del rack"
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    clearable
                    v-model="oldRow"
                    :items="rows"
                    label="Fila del rack"
                  >
                    <template v-slot:no-data>
                      <div class="pa-2">
                        No hay filas con la letra del rack seleccionado
                      </div>
                    </template>
                  </v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    clearable
                    v-model="oldColumn"
                    :items="columns"
                    label="Columna del rack"
                  >
                    <template v-slot:no-data>
                      <div class="pa-2">
                        No hay columnas con la letra y fila del rack
                        seleccionado
                      </div>
                    </template>
                  </v-select>
                </v-col>
              </v-row>
              <h6>Ubicación a cambiar del paquete</h6>
              <v-row>
                <v-col cols="12" md="6">
                  <v-select
                    clearable
                    v-model="newLetter"
                    :items="letters"
                    label="Letra del rack"
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    clearable
                    v-model="newRow"
                    :items="rows"
                    label="Fila del rack"
                  >
                    <template v-slot:no-data>
                      <div class="pa-2">
                        No hay filas con la letra del rack seleccionado
                      </div>
                    </template>
                  </v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    clearable
                    v-model="newColumn"
                    :items="columns"
                    label="Columna del rack"
                  >
                    <template v-slot:no-data>
                      <div class="pa-2">
                        No hay columnas con la letra y fila del rack
                        seleccionado
                      </div>
                    </template>
                  </v-select>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-col>
      <v-col sm="12" md="9">
        <v-card>
          <v-card-title>
            Solicitudes de paquetes
            <v-spacer></v-spacer>
            <v-text-field
              v-model="generalFilter"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
            <v-btn icon>
              <v-icon>mdi-magnify</v-icon>
            </v-btn>
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
          <v-row justify="center">
            <v-col cols="11">
              <v-chip
                @click="statusIdFiler = 2"
                class="mr-2"
                :color="
                  statusIdFiler == 2 ? `orange lighten-1` : `orange lighten-4`
                "
              >
                <v-icon left>mdi-alarm</v-icon>Pendientes
              </v-chip>
              <v-chip
                @click="statusIdFiler = 3"
                class="mr-2"
                :color="statusIdFiler == 3 ? `red darken-2` : `red lighten-4`"
              >
                <v-icon left>mdi-close</v-icon>Rechazados
              </v-chip>
              <v-chip
                @click="statusIdFiler = 1"
                :color="
                  statusIdFiler == 1 ? `green darken-3` : `green lighten-5`
                "
              >
                <v-icon left>mdi-check</v-icon>Aceptados
              </v-chip>
            </v-col>
          </v-row>
          <v-data-table
            :loading="isLoading"
            :headers="headers"
            :items="requests"
            hide-default-footer
          >
            <template v-slot:item.actions="{ item }">
              <span v-if="item.status_id == 2">
                <v-btn icon @click="updateRequest(item, true)">
                  <v-icon color="green">mdi-check</v-icon>
                </v-btn>
                <v-btn icon @click="() => updateRequest(item, false)">
                  <v-icon color="red">mdi-close</v-icon>
                </v-btn>
              </span>
              <span v-else>Sin acciones</span>
            </template>
            <template v-slot:item.oldLocker="{ item }">{{
              `${item.old_locker.letter} - ${item.old_locker.row} - ${item.old_locker.column}`
            }}</template>
            <template v-slot:item.newLocker="{ item }">{{
              `${item.new_locker.letter} - ${item.new_locker.row} - ${item.new_locker.column}`
            }}</template>
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
    <NewRequestModal
      v-model="createModalOpen"
      :payload="{ rows, letters, columns, onSuccess }"
    />
  </v-container>
</template>
<script>
import axios from "axios";
import moment from "moment";
import NewRequestModal from "./NewRequestModal";
export default {
  props: ["rows", "letters", "columns", "lots", "statuses", "payload"],
  components: {
    NewRequestModal,
  },
  data: () => ({
    moment: moment,
    currentWindow: 1,
    createModalOpen: false,
    snackbar: {
      show: false,
      text: "",
      color: "success",
    },
    headers: [
      { align: "center", text: "Código de barras", value: "package.bar_code" },
      { align: "center", text: "Cliente", value: "package.client.name" },
      { align: "center", text: "Número de lote", value: "package.lot.number" },
      { align: "center", text: "Status", value: "status.name" },
      {
        align: "center",
        text: "Locker actual (Letra-Fila-Columna)",
        value: "oldLocker",
      },
      {
        align: "center",
        text: "Locker nuevo (Letra-Fila-Columna)",
        value: "newLocker",
      },
      { align: "center", text: "Acciones", value: "actions" },
    ],
    currentPage: 1,
    pageTotal: 1,
    requests: [],
    pagination: {},
    selected: null,
    isLoading: false,
    isValidForm: false,
    generalFilter: "",
    barCode: "",
    lotId: null,
    statusId: null,
    oldLetter: null,
    statusIdFiler: 2,
    oldRow: null,
    oldColumn: null,
    newLetter: null,
    newRow: null,
    newColumn: null,
    dateFrom: null,
    menuTo: null,
    menuFrom: null,
    dateTo: null,
  }),
  beforeMount() {
    this.pagination = this.payload;
    this.currentPage = this.payload.current_page;
    this.pageTotal = this.payload.last_page;
    this.requests = this.payload.data;
    console.log(this.requests);
  },
  methods: {
    onSuccess(text) {
      this.snackbar.text = text;
      this.snackbar.color = "success";
      this.snackbar.show = true;
      if (this.requests.length <= 10) {
        this.fetchPage(undefined, 1);
      }
    },
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
      if (this.statusIdFiler != "") {
        url = `${url}&status_id=${this.statusIdFiler}`;
      }
      if (this.barCode != "") {
        url = `${url}&bar_code=${this.barCode}`;
      }
      if (this.lotId) {
        url = `${url}&lot_id=${this.lotId}`;
      }
      if (this.oldLetter) {
        url = `${url}&old_letter=${this.oldLetter}`;
      }
      if (this.newLetter) {
        url = `${url}&new_letter=${this.newLetter}`;
      }
      if (this.oldColumn) {
        url = `${url}&old_column=${this.old_column}`;
      }
      if (this.newColumn) {
        url = `${url}&new_column=${this.newColumn}`;
      }
      if (this.oldRow) {
        url = `${url}&old_row=${this.oldRow}`;
      }
      if (this.newRow) {
        url = `${url}&new_row=${this.newRow}`;
      }
      if (this.dateFrom) {
        url = `${url}&date_from=${this.dateFrom}`;
      }
      if (this.dateTo) {
        url = `${url}&date_to=${this.dateTo}`;
      }
      this.isLoading = true;
      axios
        .get(url)
        .then((response) => {
          let payloadAnswer = response.data.payload;
          this.currentPage = payloadAnswer.current_page;
          this.pageTotal = payloadAnswer.last_page;
          this.requests = payloadAnswer.data;
          this.pagination = payloadAnswer;
          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;
          this.snackbar.text = "No se pudo filtrar las solicitudes";
          this.snackbar.color = "error";
          this.snackbar.show = true;
        });
    },
    updateRequest(request, accepted) {
      axios
        .put(`${request.id}`, { accepted: accepted ? 1 : 0 })
        .then((response) => {
          this.fetchPage(undefined);
          this.snackbar.text =
            "Se ha actualizado de manera correcta la solicitud";
          this.snackbar.color = "success";
          this.snackbar.show = true;
        })
        .catch(() => {
          this.snackbar.text = "No se pudo actualizar la solicitud";
          this.snackbar.color = "error";
          this.snackbar.show = true;
        });
    },
    cleanPage() {
      let url = this.pagination.path + "?dataOnly=true&page=1";
      axios.get(url).then((response) => {
        this.pagination = response.data.books;
      });
    },
    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split("-");
      return `${day}/${month}/${year}`;
    },
  },
  watch: {
    generalFilter(val) {
      this.fetchPage(undefined, 1);
    },
    lotId(val) {
      this.fetchPage(undefined, 1);
    },
    barCode() {
      this.fetchPage(undefined, 1);
    },
    oldLetter() {
      this.fetchPage(undefined, 1);
    },
    newLetter() {
      this.fetchPage(undefined, 1);
    },
    oldColumn() {
      this.fetchPage(undefined, 1);
    },
    newColumn() {
      this.fetchPage(undefined, 1);
    },
    oldRow() {
      this.fetchPage(undefined, 1);
    },
    newRow() {
      this.fetchPage(undefined, 1);
    },
    dateFrom(val) {
      this.fetchPage(undefined, 1);
    },
    dateTo(val) {
      this.fetchPage(undefined, 1);
    },
    statusIdFiler() {
      this.fetchPage(undefined, 1);
    },
  },
  computed: {
    computedDateFrom: {
      get() {
        return this.formatDate(this.dateFrom);
      },
      set(value) {
        this.dateFrom = value;
      },
    },
    computedDateTo: {
      get() {
        return this.formatDate(this.dateTo);
      },
      set(value) {
        this.dateTo = value;
      },
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
