<template>
  <div class="mvh-100 tikal-wallpaper">
    <v-alert
      id="invalid-alert"
      class="w-50 position-relative s-alert"
      :type="alertDetails.type"
      transition="scale-transition"
      :value="alertDetails.show"
      >{{ alertDetails.message }}</v-alert
    >
    <v-container>
      <v-row align="center" justify="center">
        <v-col cols="2">
          <img
            class="w-100 text-white"
            src="images/texum-logo.svg"
            alt="Kiwi standing on oval"
          />
        </v-col>
        <v-col cols="12">
          <h1 class="text-center text-white">Texsun S.A.</h1>
        </v-col>
      </v-row>

      <v-card class="mx-auto p-4" min-width="300" width="90%">
        <v-card-title>Ingresa tus credenciales</v-card-title>
        <v-form ref="form" v-model="validLoginform">
          <v-card-text>
            <v-text-field
              v-model="email"
              name="email"
              validate-on-blur
              :rules="[rules.required, rules.email]"
              label="Correo electronico"
              required
            ></v-text-field>
            <v-text-field
              v-model="password"
              name="password"
              :rules="[rules.required]"
              label="Contraseña"
              type="password"
              required
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn
              dusk="login"
              :disabled="!validLoginform"
              text
              color="accent-4"
              @click="login"
              >Ingresar</v-btn
            >
            <v-btn dusk="register" text color="accent-4" href="/register"
              >Registrarse</v-btn
            >
          </v-card-actions>
        </v-form>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data: () => {
    return {
      alertDetails: {
        show: false,
        message: "",
        type: "success",
      },
      email: "",
      password: "",
      validLoginform: true,
      rules: {
        required: (value) => !!value || "Requerido.",
        counter: (value) => value.length >= 6 || "Minimo 6 caracteres",
        email: (value) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "Invalid e-mail.";
        },
      },
    };
  },
  methods: {
    login() {
      axios
        .post("/login", {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          this.alertDetails.show = true;
          this.alertDetails.type = "success";
          this.alertDetails.message =
            "Se ha ingresado con éxito. Por favor espera mientras te redirigimos.";
          setTimeout(() => {
            this.alertDetails.show = false;
          }, 3000);
          setTimeout(() => {
            window.location.replace("/home");
          }, 1000);
        })
        .catch((error) => {
          this.alertDetails.show = true;
          this.alertDetails.type = "error";
          this.alertDetails.message = "Credenciales inválidas";
          setTimeout(() => {
            this.alertDetails.show = false;
          }, 3000);
        });
    },
  },
};
</script>
<style scoped>
.mvh-100 {
  min-height: 100vh;
}
</style>
