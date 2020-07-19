<template>
  <v-container class="mt-2 fluid">
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
                  <v-col cols="12" md="6">
                    <v-text-field v-model="barCode" label="Codigo de barras"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-select
                      clearable
                      v-model="statusId"
                      :items="statuses"
                      :item-text="(item) => (item.name)"
                      :item-value="(item) => (item.id)"
                      label="Status del paquete"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" md="6">
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
                    <v-select clearable v-model="letter" :items="letters" label="Letra del rack"></v-select>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-select clearable v-model="row" :items="rows" label="Fila del rack">
                      <template v-slot:no-data>
                        <div class="pa-2">No hay filas con la letra del rack seleccionado</div>
                      </template>
                    </v-select>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-select clearable v-model="column" :items="columns" label="Column del rack">
                      <template v-slot:no-data>
                        <div class="pa-2">No hay columnas con la letra y fila del rack seleccionado</div>
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
            <template v-slot:body="{ items }">
              <tbody>
                <tr v-for="item in items" :key="item.name">
                  <td class="text-center">{{ item.bar_code }}</td>
                  <td class="text-center">{{item.client.name}}</td>
                  <td class="text-center">{{item.lot.number}}</td>
                  <td class="text-center">{{item.status.name}}</td>
                  <td
                    class="text-center"
                  >{{`${item.currentLocker.letter} - ${item.currentLocker.row} - ${item.currentLocker.column}`}}</td>
                  <td class="text-center">Actions</td>
                </tr>
              </tbody>
            </template>
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
  </v-container>
</template>
<script>
import axios from "axios";
export default {
  props: ["rows", "letters", "columns", "lots", "statuses", "payload"],
  data: () => ({
    headers: [
      { align: "center", text: "Código de barras", value: "bar_code" },
      { align: "center", text: "Cliente", value: "client" },
      { align: "center", text: "Número de lote", value: "lot_number" },
      { align: "center", text: "Status", value: "status" },
      { align: "center", text: "Locker (Letra-Fila-Columna)" },
      { align: "center", text: "Acciones" }
    ],
    currentPage: 1,
    pageTotal: 1,
    packages: [],
    pagination: {},
    isLoading: false,
    isValidForm: false,
    generalFilter: "",
    barCode: "",
    lotId: null,
    statusId: null,
    letter: null,
    row: null,
    column: null
  }),
  beforeMount() {
    this.pagination = this.payload;
    this.currentPage = this.payload.current_page;
    this.pageTotal = this.payload.last_page;
    this.packages = this.payload.data;
  },
  methods: {
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

      axios
        .get(url)
        .then(response => {
          let payloadAnswer = response.data.payload;
          this.currentPage = payloadAnswer.current_page;
          this.pageTotal = payloadAnswer.last_page;
          this.packages = payloadAnswer.data;
          this.pagination = payloadAnswer;
        })
        .catch(error => {
          console.log(error);
        });
    },
    cleanPage() {
      let url = this.pagination.path + "?dataOnly=true&page=1";
      axios.get(url).then(response => {
        this.pagination = response.data.books;
      });
    }
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
    }
  }
};
</script>