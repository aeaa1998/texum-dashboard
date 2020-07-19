<template>
  <v-container class="mt-2">
    <v-row align="center" justify="center">
      <v-col cols="2">
        <img class="w-100" src="images/texum-logo.svg" alt="Kiwi standing on oval" />
      </v-col>
      <v-col cols="12">
        <h1 class="text-center text-white">Busqueda Detallada</h1>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col md="8" col="10">
        <v-card class="mx-auto p-3" width="100%">
          <v-card-title>Busqueda Avanzada</v-card-title>
          <v-form ref="form" v-model="validForm">
            <v-card-text>
              <v-row>
                <v-col cols="12">
                  <v-text-field v-model="filters.rack" label="Rack" required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="filters.column" label="Columna" required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="filters.level" label="Nivel" required></v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
            <v-card-actions>
              <v-btn :disabled="!validForm" text color="accent-4">Buscar</v-btn>
            </v-card-actions>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  data: () => ({
    validForm: false,
    alertDetails: {
      show: false,
      message: "",
      type: "success"
    },
    filters: {
      level: "",
      column: "",
      rack: ""
    }
  })
};
</script>

