<template>
  <div class="mh-100 main-bg">
    <v-container>
      <v-card>
        <v-card-title>
          Cortes
          <v-spacer></v-spacer>
          <v-text-field
            append-icon="mdi-magnify"
            label="Búsqueda detallada"
            v-model="search"
            search="search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="lots" :search="search">
          <template v-slot:item.is_delivered="{ item }">
            {{ item.is_delivered == 1 ? "Si" : "No" }}
          </template>
          <template v-slot:item.actions="{ item }">
            <v-btn
              v-if="item.is_delivered == 0"
              icon
              @click="acceptWorker(item, true)"
            >
              <v-icon color="green">mdi-check</v-icon>
            </v-btn>
            <div v-else>No hay acciones para este lote</div>
          </template>
          <!-- hide-default-footer agregar con el pagination -->
        </v-data-table>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import axios from "axios";
export default {
  props: ["lots"],
  data: () => ({
    headers: [
      { align: "center", text: "id", value: "id" },
      { align: "center", text: "Numero", value: "number" },
      { align: "center", text: "Cliente", value: "client.name" },
      { align: "center", text: "Entregado", value: "is_delivered" },
      { align: "center", text: "Creado", value: "create_date" },
      { align: "center", text: "Acciones", value: "actions" },
    ],
    lotes: [],
    search: "",
  }),
  beforeMount() {
    this.lotes = this.lots.data;
    console.log(this.lots);
  },
};
</script>