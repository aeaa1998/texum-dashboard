<template>
  <v-container class="mt-2 fluid">
    <v-window v-model="currentWindow">
      <v-window-item :value="1">
        <v-row justify="center">
          <v-col sm="12" md="3">
            <!-- This is the search component -->
            <v-expansion-panels multiple class="mb-1">
              <v-expansion-panel>
                <v-expansion-panel-header>Filtrar por fechas</v-expansion-panel-header>
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
                          :allowed-dates="val  => {
                            if(dateTo){
                                return moment(val) <  moment(dateTo)
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
                        v-model="menuTo"
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
                          v-model="dateTo"
                          no-title
                          @input="menuTo = false"
                          :allowed-dates="val  => {
                            if(dateFrom){
                                return moment(val) >  moment(dateFrom)
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
                  <v-form v-model="isValidForm">
                    <h6>Datos del paquete</h6>
                    <v-row no-gutters>
                      <v-col cols="12">
                        <v-text-field v-model="barCode" label="Codigo de barras"></v-text-field>
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
                  </v-form>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Ubicación del paquete</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <h6>Ubicación actual del paquete</h6>
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
                      <v-select clearable v-model="newRow" :items="rows" label="Fila del rack">
                        <template v-slot:no-data>
                          <div class="pa-2">No hay filas con la letra del rack seleccionado</div>
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
                          <div
                            class="pa-2"
                          >No hay columnas con la letra y fila del rack seleccionado</div>
                        </template>
                      </v-select>
                    </v-col>
                  </v-row>
                  <h6>Ubicación anterior del paquete</h6>
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
                      <v-select clearable v-model="oldRow" :items="rows" label="Fila del rack">
                        <template v-slot:no-data>
                          <div class="pa-2">No hay filas con la letra del rack seleccionado</div>
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
                          <div
                            class="pa-2"
                          >No hay columnas con la letra y fila del rack seleccionado</div>
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
                Historial de Paquetes
                <v-spacer></v-spacer>
                <v-text-field v-model="generalFilter" label="Buscar" single-line hide-details></v-text-field>
                <v-btn icon>
                  <v-icon>mdi-magnify</v-icon>
                </v-btn>
              </v-card-title>
              <v-data-table
                :loading="isLoading"
                :headers="headers"
                :items="records"
                hide-default-footer
              >
                <!-- <template v-slot:item.actions="{ item }">
                  <v-btn icon @click="navigateToDetail(item)">
                    <v-icon>mdi-eye</v-icon>
                  </v-btn>
                </template>-->
                <template
                  v-slot:item.created_at="{ item }"
                >{{moment(item.created_at).locale('es').format('ll')}}</template>
                <template
                  v-slot:item.oldLocker="{ item }"
                >{{item.old_locker ? `${item.old_locker.letter} - ${item.old_locker.row} - ${item.old_locker.column}` : 'No tiene previa posición'}}</template>
                <template
                  v-slot:item.newLocker="{ item }"
                >{{`${item.new_locker.letter} - ${item.new_locker.row} - ${item.new_locker.column}`}}</template>
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
import moment from "moment";
export default {
  props: ["rows", "letters", "columns", "lots", "statuses", "payload"],
  data: () => ({
    currentWindow: 1,
    menuFrom: false,
    menuTo: false,
    moment: moment,
    menuTo: false,
    headers: [
      { align: "center", text: "Código de barras", value: "package.bar_code" },
      { align: "center", text: "Cliente", value: "package.client.name" },
      { align: "center", text: "Número de lote", value: "package.lot.number" },
      { align: "center", text: "Responsable", value: "user.worker.first_name" },
      {
        align: "center",
        text: "Locker antiguo (Letra-Fila-Columna)",
        value: "oldLocker",
      },
      {
        align: "center",
        text: "Locker nuevo (Letra-Fila-Columna)",
        value: "newLocker",
      },
      { align: "center", text: "Fecha", value: "created_at" },
      // { align: "center", text: "Acciones", value: "actions" },
    ],
    currentPage: 1,
    pageTotal: 1,
    records: [],
    pagination: {},
    selected: null,
    isLoading: false,
    isValidForm: false,
    generalFilter: "",
    barCode: "",
    lotId: null,
    statusId: null,
    oldLetter: null,
    dateFrom: null,
    dateTo: null,
    oldRow: null,
    oldColumn: null,
    newLetter: null,
    newRow: null,
    newColumn: null,
  }),
  beforeMount() {
    this.pagination = this.payload;
    this.currentPage = this.payload.current_page;
    this.pageTotal = this.payload.last_page;
    this.records = this.payload.data;
  },
  methods: {
    navigateToDetail(selected) {
      this.selected = selected;
      this.currentWindow++;
    },
    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split("-");
      return `${day}/${month}/${year}`;
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
      if (this.lotId) {
        url = `${url}&lot_id=${this.lotId}`;
      }
      if (this.oldLetter) {
        url = `${url}&old_letter=${this.oldLetter}`;
      }
      if (this.newLetter) {
        url = `${url}&new_letter=${this.newLetter}`;
      }
      if (this.old_column) {
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
          this.records = payloadAnswer.data;
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
  },
  computed: {
    computedDateFrom() {
      return this.formatDate(this.dateFrom);
    },
    computedDateTo() {
      return this.formatDate(this.dateTo);
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
