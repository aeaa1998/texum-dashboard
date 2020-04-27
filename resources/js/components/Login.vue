<template>
  <div class="vh-100 tikal-wallpaper">
    <v-container>
      <v-row align="center" justify="center">
        <v-col cols="2">
          <img class="w-100" src="images/texum-logo.svg" alt="Kiwi standing on oval" />
        </v-col>
        <v-col cols="12">
          <h1 class="text-center text-white">Texum S.A.</h1>
        </v-col>
      </v-row>

      <v-card class="mx-auto p-4" min-width="300" width="90%">
        <v-card-title>Ingresa tus credenciales</v-card-title>
        <v-card-text>
          <v-text-field v-model="email" label="Correo electronico" required></v-text-field>
          <v-text-field v-model="password" label="Contraseña" type="password" required></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn text color="deep-purple accent-4" href="/">Ingresar</v-btn>
          <v-btn text color="deep-purple accent-4" href="/">Registrarse</v-btn>
        </v-card-actions>
        <a class="ml-3 uk-link-muted" href>Olvidaste tu contraseña?</a>
      </v-card>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => {
    return {
      email: "",
      password: ""
    };
  }
};
</script>
