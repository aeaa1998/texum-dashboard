<template>
  <div class="vh-100 tikal-wallpaper">
    <v-alert class="w-50 position-relative s-alert" :type="alertDetails.type" transition="scale-transition" :value="alertDetails.show">{{alertDetails.message}}</v-alert>
    <v-container>
      <v-row align="center" justify="center">
        <v-col cols="2">
          <img class="w-100" src="images/texum-logo.svg" alt="Kiwi standing on oval" />
        </v-col>
        <v-col cols="3">
          <h1 class="text-center text-white">Texsun S.A</h1>
        </v-col>
      </v-row>

      <v-card class="mx-auto p-3" min-width="300" width="90%">
        <v-card-title>Crea tu usuario</v-card-title>
        <v-form ref="form" v-model="validForm">
          <v-card-text>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  :rules="[registerRules.required]"
                  dense
                  v-model="firstName"
                  label="Primer Nombre"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  :rules="[registerRules.required]"
                  dense
                  v-model="lastName"
                  label="Primer Apellido"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  :rules="[registerRules.required, registerRules.email]"
                  dense
                  v-model="email"
                  label="Correo electronico"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  :rules="[registerRules.required, registerRules.counter]"
                  dense
                  v-model="password"
                  label="Contraseña"
                  type="password"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="passwordConfirmation"
                  :rules="[registerRules.required, passwordMatchRule, registerRules.counter]"
                  label="Confirmar contraseña"
                  type="password"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-btn :disabled="!validForm" @click="registerUser" text color="accent-4">Crear Cuenta</v-btn>
            <a class="ml-3 uk-link-muted" href="/">Ya tienes usuario? Inicia sesión</a>
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
      type: "success"
      },
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      passwordConfirmation: "",
      validForm: true,
      registerRules: {
        required: value => !!value || "Requerido.",
        counter: value => value.length >= 6 || "Minimo 6 caracteres",
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "Invalid e-mail.";
        }
      }
    };
  },
  methods: {
    registerUser() {
      axios
        .post("register", {
            "first_name": this.firstName,
            "last_name": this.lastName,
            "email": this.email,
            "password": this.password,
            "password_confrimation": this.passwordConfirmation
        })
        .then(response => {
          this.alertDetails.show = true
          this.alertDetails.type = "success"
          this.alertDetails.message = "Usuario creado con exito. Por favor espera mientras te redirigimos."
          setTimeout(()=> {
            window.location.replace("/")
          }, 1000)
          setTimeout(()=> {
            this.alertDetails.show = false
          }, 3000)

        })
        .catch(e => {
          this.alertDetails.show = true
          this.alertDetails.type = "error"
          this.alertDetails.message = "El usuario ya ha sido tomado"
          setTimeout(()=> {
            this.alertDetails.show = false
          }, 3000)
        });
    }
  },
  computed: {
    passwordMatchRule() {
      return () =>
        this.password == this.passwordConfirmation ||
        "Las contraseñas no coinciden";
    }
  }
};
</script>
