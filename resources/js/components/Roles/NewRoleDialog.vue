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
          <v-btn dark text @click="create" :disabled="!validForm">Crear</v-btn>
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
        >{{ alert.text }}</v-alert
      >
      <v-card-text>
        <v-form ref="new_rol_form" v-model="validForm">
          <v-text-field
            clear-icon=""
            v-model="name"
            :rules="[rules.required]"
            label="Nombre del rol"
          ></v-text-field>
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
  props: ["onSuccess"],
  data: () => ({
    validForm: true,
    moment: moment,
    name: "",
    alert: {
      show: false,
      text: "",
    },
    rules: {
      required: (v) => !!v || "Es un campo obligatorio",
    },
  }),
  methods: {
    create() {
      axios
        .post("/roles", {
          name: this.name,
        })
        .then((response) => {
          this.clear();
          this.closeModal();
          console.log(response);
          this.onSuccess("Rol creado exitosamente", response);
        })
        .catch(({ response }) => {
          this.alert.text = "Error al crear el rol";
          this.alert.show = true;
          setTimeout(() => {
            this.alert.text = "";
            this.alert.show = false;
          }, 3000);
        });
    },
    clear() {
      this.$refs.new_rol_form.reset();
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

