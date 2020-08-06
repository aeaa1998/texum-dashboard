<template>
  <div class="mh-100 main-bg">
    <v-container class="align-items-center">
      <v-card class="mx-auto">
        <v-container class="black">
          <v-row>
            <v-col cols="1">
              <v-avatar
              color="#DBDBDB"
              size="150">
                <v-icon size="100px">mdi-account</v-icon>
              </v-avatar>
            </v-col>
            <v-col cols="11">
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title class="title"><h1 class="h1 align-middle">Perfil de Usuario</h1></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
          </v-row>
        </v-container>

        <v-tabs>
          <v-tab>
            Información
          </v-tab>
          <v-tab>
            Editar
          </v-tab>
          <v-tab-item>
            <v-card>
              <v-card-text>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      :firstNames="firstName"
                      label="Nombre"
                      readonly>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      :lastNames="lastName"
                      label="Apellido"
                      readonly>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      :emails="email"
                      label="Correo"
                      readonly>
                    </v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card>
              <v-card-text>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      label="Nombre">
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      label="Apellido">
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      label="Correo">
                    </v-text-field>
                  </v-col>
                  <v-col class="text-right">
                    <v-btn align="right">Editar</v-btn>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-tab-item>
        </v-tabs>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import axios from "axios"
export default {
  props: ["profile"],
  data: () => {
    return {
      firstName: "",
      lastName: "",
      email: "",
    };
    
  }
}
</script>
<style scoped>
.h1 {
  font-size: 80px;
  text-align: center;
  color: #DBDBDB;
}
.black {
  background-color: #212529;
}
</style>