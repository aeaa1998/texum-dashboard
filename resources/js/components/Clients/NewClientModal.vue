<template>
  <v-dialog persistent v-bind="$attrs">
    <v-card>
      <v-toolbar flat dark color="primary">
        <v-btn icon dark @click="closeModal">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Agregar Cliente</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn dark text @click="createClient" :disabled="!validForm"
            >Crear</v-btn
          >
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
        <v-form ref="new_client_form" v-model="validForm">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="name"
                :rules="[rules.required]"
                label="Nombre del cliente"
              ></v-text-field>
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
  props: ["onSuccess"],
  data: () => ({
    validForm: true,
    moment: moment,
    name: null,
    alert: {
      show: false,
      text: "",
    },
    rules: {
      required: (v) => !!v || "Es un campo obligatorio",
    },
  }),

  methods: {
    createClient() {
      axios
        .post("/clients", {
          name: this.name,
        })
        .then((response) => {
          this.clear();
          this.closeModal();
          this.onSuccess("Cliente creado exitosamente", response);
        })
        .catch(({ response }) => {
          this.alert.text = "Error al crear el cliente";
          this.alert.show = true;
          setTimeout(() => {
            this.alert.text = "";
            this.alert.show = false;
          }, 3000);
        });
    },
    clear() {
      this.$refs.new_client_form.reset();
    },
    closeModal() {
      this.$emit("input", false);
    },
  },
};
</script>
