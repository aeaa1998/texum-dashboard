<template>
  <v-dialog persistent v-bind="$attrs">
    <v-card>
      <v-toolbar flat dark color="primary">
        <v-btn icon dark @click="closeModal">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Crear paquete</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn dark text @click="createPackage" :disabled="!validForm">Crear</v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-alert
        text
        prominent
        type="error"
        class="w-100"
        v-show="alert.show"
        transition="scale-transition"
        icon="mdi-cloud-alert"
      >{{alert.text}}</v-alert>
      <v-card-text>
        <v-form ref="new_package_form" v-model="validForm">
          <v-row>
            <v-col md="6" cols="12">
              <v-text-field v-model="barCode" :rules="[rules.required]" label="Codigo de barras"></v-text-field>
            </v-col>
            <v-col md="6" cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                v-model="selectedClient"
                :items="clients"
                label="Cliente"
                item-text="name"
                item-value="id"
                placeholder="Ingresa el nombre de tu cliente"
              ></v-autocomplete>
            </v-col>
            <v-col cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                :disabled="selectedClient == null"
                v-model="lotId"
                :items="filteredLots"
                label="Lote"
                :item-text="(item) => `Número ${item.number} - ${moment(item.cut_date).locale('es').format('ll')}`"
                item-value="id"
                placeholder="Ingresa el numero de lote"
              ></v-autocomplete>
            </v-col>
            <v-col md="4" cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                v-model="letter"
                :items="payload.letters"
                label="Letra del locker"
                placeholder="Ingresa la letra del locker"
              ></v-autocomplete>
            </v-col>
            <v-col md="4" cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                v-model="column"
                :items="payload.columns"
                label="Columna del locker"
                placeholder="Ingresa la columna del locker"
              ></v-autocomplete>
            </v-col>
            <v-col md="4" cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                v-model="row"
                :items="payload.rows"
                label="Fila del locker"
                placeholder="Ingresa la fila del locker"
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-btn @click="clear" text>Limpiar campos</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import axios from "axios";
import moment from "moment";
export default {
  name: "new-package-modal",
  props: ["payload"],
  data: () => ({
    validForm: true,
    moment: moment,
    selectedClient: null,
    lotId: null,
    barCode: "",
    letter: "",
    column: "",
    row: "",
    alert: {
      show: false,
      text: "",
    },
    rules: {
      required: (v) => !!v || "Es un campo obligatorio",
    },
  }),
  computed: {
    clients() {
      return this.payload.lots.map((lot) => lot.client);
    },
    filteredLots() {
      if (this.selectedClient == null) {
        return [];
      }
      return this.payload.lots.filter(
        (lot) => lot.client.id == this.selectedClient
      );
    },
  },
  methods: {
    createPackage() {
      axios
        .post("/packages", {
          bar_code: this.barCode,
          column: this.column,
          letter: this.letter,
          row: this.row,
          lot_id: this.lotId,
        })
        .then(() => {
          this.clear();
          this.closeModal();
          this.payload.onSuccess("Paquete creado exitosamente");
        })
        .catch(({ response }) => {
          this.alert.text = response.data;
          this.alert.show = true;
          setTimeout(() => {
            this.alert.text = "";
            this.alert.show = false;
          }, 3000);
        });
    },
    clear() {
      this.$refs.new_package_form.reset();
    },
    closeModal() {
      this.$emit("input", false);
    },
  },
};
</script>
