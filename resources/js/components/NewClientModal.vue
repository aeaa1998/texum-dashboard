<template>
  <v-dialog persistent v-bind="$attrs">
    <v-card>
      <v-toolbar flat dark color="primary">
        <v-btn icon dark @click="closeModal">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Crear Nuevo Cliente</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn dark text @click="createClient()" :disabled="!validForm">Crear</v-btn>
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
        <v-form ref="new_client_form" v-model="validForm">
          <v-row>
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
  name: "new-client-modal",
  props: ["clients"],
  data: () => ({
    validForm: true,
    moment: moment,
    selectedClient: null,
    lotId: null,
    clientName: "",
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
        console.log("Creando...")
    //   axios
    //     .post("/client", {
    //       name: this.clients.name
    //     })
    //     .then(() => {
    //       this.clear();
    //       this.closeModal();
    //       this.payload.onSuccess("Cliente creado exitosamente");
    //     })
    //     .catch(({ response }) => {
    //       this.alert.text = response.data;
    //       this.alert.show = true;
    //       setTimeout(() => {
    //         this.alert.text = "No se pudo realizar la accion";
    //         this.alert.show = false;
    //       }, 3000);
    //     });
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
