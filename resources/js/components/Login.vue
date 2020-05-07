<template>
  <div class="vh-100 tikal-wallpaper">
    <v-container>
      
      <v-row align="center" justify="center">
        <v-col cols="2">
          <img class="w-100 text-white" src="images/texum-logo.svg" alt="Kiwi standing on oval" />
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
              :rules="[rules.required, rules.email]"
              label="Correo electronico"
              required
            ></v-text-field>
            <v-text-field
              v-model="password"
              :rules="[rules.required]"
              label="Contraseña"
              type="password"
              required
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn :disabled="!validLoginform" text color="accent-4" href="/">Ingresar</v-btn>
            <v-btn text color="accent-4" href="/register">Registrarse</v-btn>
          </v-card-actions>
        </v-form>
        <!-- <a class="ml-3 uk-link-muted" href="/register">Olvidaste tu contraseña?</a> -->
      </v-card>
    </v-container>
  </div>
</template>
<script>
export default {

  data: () => {
    return {
      email: "",

      password: "",
      validLoginform: true,
      rules: {
        required: value => !!value || "Requerido.",
        counter: value => value.length >= 6 || "Minimo 6 caracteres",
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "Invalid e-mail.";
        }
      }
    };
  },

};
</script>
