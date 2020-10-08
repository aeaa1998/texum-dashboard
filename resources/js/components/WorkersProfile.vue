<template>
    <div class="mh-100 main-bg">
        <v-container class="align-items-center">
            <v-card class="mx-auto">
                <v-container class="black">
                    <v-row>
                        <v-col cols="4" justify="center">
                            <v-avatar
                                color="#DBDBDB"
                                size="100">
                                <v-icon size="50px">mdi-account</v-icon>
                            </v-avatar>
                        </v-col>
                        <v-col cols="8">
                            <v-card-title class="h1 align-middle">Perfil de Trabajador</v-card-title>
                        </v-col>
                    </v-row>
                </v-container>
                <v-tabs>
                    <v-tab>
                        Información
                    </v-tab>
                    <v-tab-item>
                        <v-card>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="user.worker.first_name"
                                            label="Nombre"
                                            readonly>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="user.worker.last_name"
                                            label="Apellido"
                                            readonly>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="user.email"
                                            label="Correo"
                                            readonly>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="user.verified_at"
                                            label="Fecha de Verificación"
                                            readonly>
                                        </v-text-field>
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
  props: ["user"],
  data: () => {
      return {
          userFirstName: "",
          userLastName: "",
          userEmail: "",
          userVerificationDate: "",
      }
  }
}
</script>
<style scoped>
    .h1 {
    font-size: 50px;
    text-align: space-around;
    color: #DBDBDB;
    }
    .black {
    background-color: #212529;
    }
</style>