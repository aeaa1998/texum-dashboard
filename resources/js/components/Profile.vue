<template>
  <div class="mh-100 main-bg">
    <v-alert
    transition="scale-transition"
    :type="alertDetails.type"
    :value="alertDetails.show"
    >

    </v-alert>
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
                      v-model="profile.worker.first_name"
                      label="Nombre"
                      readonly>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="profile.worker.last_name"
                      label="Apellido"
                      readonly>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="profile.email"
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
              <v-form
                v-model="valid">
                <v-card-text>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        v-model="profile.worker.first_name"
                        :rules="[inputRules.required, inputRules.min(2)]"
                        label="Nombre">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="profile.worker.last_name"
                        :rules="[inputRules.required]"
                        label="Apellido">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="profile.email"
                        :rules="[inputRules.required, inputRules.email]"
                        label="Correo">

                      </v-text-field>
                    </v-col>
                    <v-col class="text-right">
                      <v-btn :disabled="!valid" @click="updateUser" align="right">Editar</v-btn>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-form>
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
  beforeMount() {
    this.editProfile = {...this.profile}
  },
  editProfile: {
  
  },
  data: () => ({
    alertDetails: {
      show: false,
      message: "",
      type: "success"
    },
    valid: true,
    name: '',
    inputRules: {
      required: v => !!v || 'Campo Requerido.',
      min: length => v => v.length >= length || `El campo debe tener como minimo ${length}`,
      email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "E-mail Invalido.";
        }
    },
    methods: {
      editProfile() {
        axios
          .post("/profile", {
            ...this.editProfile
          })
          .then(response => {
            alertDetails.show = true
            
            
          }

          )
      }
    }
      

  })
  
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