<template>
  <v-dialog persistent v-bind="$attrs">
    <v-card>
      <v-toolbar flat dark color="primary">
        <v-btn icon dark @click="closeModal">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Crear solicitud</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn dark text @click="create" :disabled="!validForm || !differentLockers">Crear</v-btn>
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
        <v-form ref="new_package_request_form" v-model="validForm">
          <h4>Busca el paquete para realizar la solicitud</h4>
          <v-row>
            <v-col md="4" cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                v-model="oldLetter"
                :items="payload.letters"
                label="Letra del locker"
                placeholder="Ingresa la letra del locker"
              ></v-autocomplete>
            </v-col>
            <v-col md="4" cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                v-model="oldColumn"
                :items="payload.columns"
                label="Columna del locker"
                placeholder="Ingresa la columna del locker"
              ></v-autocomplete>
            </v-col>
            <v-col md="4" cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                v-model="oldRow"
                :items="payload.rows"
                label="Fila del locker"
                placeholder="Ingresa la fila del locker"
              ></v-autocomplete>
            </v-col>
            <v-col cols="12">
              <v-autocomplete
                clearable
                :rules="[rules.required]"
                :disabled="[oldLetter, oldRow, oldColumn].some(val => (val == '' || val == null)) || packages.length == 0"
                v-model="selectedPackage"
                :items="packages"
                label="Paquete a mover (Codigo - Número de lote)"
                :item-text="item => `${item.bar_code} - ${item.lot.number}`"
                item-value="id"
                placeholder="Ingrese el código de barras del paquete"
              ></v-autocomplete>
            </v-col>
            <v-col cols="12">
              <h4>Nueva ubicación del paquete</h4>
            </v-col>
            <v-col md="4" cols="12">
              <v-autocomplete
                clearable
                :disabled="selectedPackage == null"
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
                :disabled="selectedPackage == null"
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
                :disabled="selectedPackage == null"
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
  props: ["payload"],
  data: () => ({
    validForm: true,
    moment: moment,
    packages: [],
    selectedPackage: null,
    letter: "",
    column: "",
    row: "",
    oldLetter: "",
    oldColumn: "",
    oldRow: "",
    alert: {
      show: false,
      text: "",
    },
    rules: {
      required: (v) => !!v || "Es un campo obligatorio",
    },
  }),
  computed: {
    differentLockers() {
      let canFetch = [
        this.oldLetter,
        this.oldRow,
        this.oldColumn,
        this.letter,
        this.row,
        this.column,
      ].some((val) => val == "" || val == null);
      if (canFetch) {
        return false;
      }
      return (
        this.oldLetter != this.letter ||
        this.oldColumn != this.column ||
        this.oldRow != this.row
      );
    },
  },
  methods: {
    fetchPackages() {
      let canFetch = ![this.oldLetter, this.oldRow, this.oldColumn].some(
        (val) => val == "" || val == null
      );
      if (canFetch) {
        let baseUrl = `/packages/by/locker?letter=${this.oldLetter}&row=${this.oldRow}&column=${this.oldColumn}`;
        axios.get(baseUrl).then((response) => {
          this.packages = response.data;
        });
      } else {
        if (this.packages.length != 0) {
          this.packages = [];
        }
      }
    },
    create() {
      console.log({
        old_locker_row: this.oldRow,
        old_locker_column: this.oldColumn,
        old_locker_letter: this.oldLetter,
        new_locker_row: this.row,
        new_locker_column: this.column,
        new_locker_letter: this.letter,
        package_id: this.selectedPackage,
      });
      axios
        .post("/requests", {
          old_locker_row: this.oldRow,
          old_locker_column: this.oldColumn,
          old_locker_letter: this.oldLetter,
          new_locker_row: this.row,
          new_locker_column: this.column,
          new_locker_letter: this.letter,
          package_id: this.selectedPackage,
        })
        .then(() => {
          this.clear();
          this.closeModal();
          this.payload.onSuccess("Solicitud creada exitosamente");
        })
        .catch(({ response }) => {
          this.alert.text = response.data.message;
          this.alert.show = true;
          setTimeout(() => {
            this.alert.text = "";
            this.alert.show = false;
          }, 3000);
        });
    },
    clear() {
      this.$refs.new_package_request_form.reset();
    },
    closeModal() {
      this.$emit("input", false);
    },
  },
  watch: {
    oldLetter() {
      this.fetchPackages();
    },
    oldColumn() {
      this.fetchPackages();
    },
    oldRow() {
      this.fetchPackages();
    },
  },
};
</script>

