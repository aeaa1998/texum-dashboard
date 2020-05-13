<template>
  <div>
    <navbar></navbar>
    
    <!-- Botones de las opciones -->
    <template>
      <v-container>
        <v-row class="row align-items-center" justify="center" col-lg="4">
          <v-col col-lg="4">
            <v-sheet class="pa-2" height="350px" elevation="5">
              <h1 align="center">
                Busqueda por Rack
              </h1>
              <img src="images/texum-logo.jpeg" width="100%">
            </v-sheet>
          </v-col>
          <v-col offset-lg="1">
            <v-sheet class="pa-2" height="350px" elevation="5" col-lg="4">
              <h1 align="center">
                Busqueda Avanzada
              </h1>
              <v-icon x-large="100px">mdi-magnify</v-icon>
            </v-sheet>
          </v-col>
        </v-row>
        <v-row class="row align-items-center" justify="center" col-lg="4">
          <v-col>
            <v-sheet class="col-md-4 offset-md-4" height="120px" elevation="5">
              <h1 align="center">
                Ver Historial
                <br>
                <v-icon x-large>mdi-history</v-icon>
              </h1>
            </v-sheet>
          </v-col>
        </v-row>
      </v-container>
    </template>
  </div>
</template>

