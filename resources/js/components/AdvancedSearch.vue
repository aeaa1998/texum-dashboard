<template>
  <div class="vh-100 texum-logo">
    <v-alert
      class="w-50 position-relative s-alert"
      :type="alertDetails.type"
      transition="scale-transition"
      :value="alertDetails.show"
    >{{alertDetails.message}}</v-alert>
    <v-container>
      <v-row align="center" justify="center">
        <v-col cols="2">
          <img class="w-100" src="images/texum-logo.svg" alt="Kiwi standing on oval" />
        </v-col>
        <v-col cols="12">
          <h1 class="text-center text-white">Busqueda Detallada</h1>
        </v-col>
      </v-row>
      
      <v-card class="mx-auto p-3" min-width="100" width="50%">
        <v-card-title>Busqueda Avanzada</v-card-title>
        <v-form ref="form" v-model="validForm">
          <v-card-text>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="Rack"
                  label="Rack"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="Columna"
                  label="Columna"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="Nivel"
                  label="Nivel"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-btn :disabled="!validForm" @click="buscar" text color="accent-4">Buscar</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-container>
  </div>
</template>
