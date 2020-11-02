<template>
  <div class="mh-100 main-bg">
    <v-alert
    class="w-75 position-relative s-alert"
    :type="alertDetails.type"
    transition="scale-transition"
    :value="alertDetails.show"
    >
      {{alertDetails.message}}
    </v-alert>
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
              <v-card-title class="h1 align-middle">Perfil de Usuario</v-card-title>
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
          <v-tab>
            Cambiar contraseña
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
                        :rules="[inputRules.required, inputRules.min(1)]"
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
                      <v-btn :disabled="!valid" @click="editProfile" align="right">Editar</v-btn>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-form>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card>
              <v-form>
                <v-card-text>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                      v-model="oldPassword"
                        label="Contraseña Actual"
                        type="password"
                        :rules="[inputRules.required, inputRules.min(6)]">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="newPassword"
                        label="Nueva Contraseña"
                        type="password"
                        :rules="[inputRules.required, inputRules.min(6)]">
                      </v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="passwordConfirm"
                        label="Confirmar Contraseña"
                        type="password"
                        :rules="[inputRules.required, passwordMatchRule, inputRules.min(6)]">
                      </v-text-field>
                    </v-col>
                    <v-col class="text-right">
                      <v-btn :disabled="!valid" @click="editPassword" align="right">Editar</v-btn>
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
  editedProfile: {},
  data: () => {
    return {
      alertDetails: {
      show: false,
      message: "",
      type: "success"
      },
      oldPassword: "",
      newPassword: "",
      passwordConfirm: "",
      valid: true,
      passwordValid: true,
      inputRules: {
        required: v => !!v || 'Campo Requerido.',
        min: length => v => v.length >= length || `El campo debe tener como minimo ${length}`,
        email: value => {
            const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return pattern.test(value) || "E-mail Invalido.";
          }
      },
    };
  },
  beforeMount() {
    this.editedProfile = {...this.profile}
  },
  methods: {
    editProfile() {
      axios
        .put("/profiles", {
          first_name: this.profile.worker.first_name,
          last_name: this.profile.worker.last_name,
          email: this.profile.email
        })
        .then(response => {
          //console.log(response)
          this.alertDetails.show = true;
          this.alertDetails.type = "success";
          this.alertDetails.message = "Se han realizado los cambios con éxito";
          setTimeout(() => {
          this.alertDetails.show = false;
          }, 3000);
        })
        .catch(e => {
          console.log(e)
          this.alertDetails.show = true;
          this.alertDetails.type = "error";
          this.alertDetails.message = "Otro usuario tiene las mismas credenciales";
          setTimeout(() => {
            this.alertDetails.show = false;
          }, 3000);
        });
    },
    editPassword() {
      axios
      .put("/profile/password", {
        password: this.newPassword
      })
      .then(response => {
        console.log(response)
        this.alertDetails.show = true;
        this.alertDetails.type = "success";
        this.alertDetails.message = "Se ha realizado el cambio con éxito";
        setTimeout(() => {
        this.alertDetails.show = false;
        }, 3000);
      })
      .catch(e => {
        this.alertDetails.show = true;
        this.alertDetails.type = "error";
        this.alertDetails.message = "La contraseña no se logro cambiar";
        setTimeout(() => {
          this.alertDetails.show = false;
        }, 3000);
        console.log(e)
      });
    }   
  },
  computed: {
    passwordMatchRule() {
      return () =>
        this.newPassword == this.passwordConfirm ||
        "Las contraseñas no coinciden";
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