<template>
    <div class="vh-100 tikal-wallpaper">
        <v-container>

            <v-row align="center" justify="center">
                <v-col cols="2">
                    <img class="w-100" src="images/texum-logo.svg" alt="Kiwi standing on oval"/>
                </v-col>
                <v-col cols="3">
                    <h1 class="text-center text-white"> Texum S.A </h1>
                </v-col>
            </v-row>


            <v-card class="mx-auto p-4" min-width="300" width="90%">
                <v-card-title> Crea tu usuario </v-card-title>
                <v-card-text>
                    <v-row style="padding: 0px">
                        <v-col cols="6">
                            <v-text-field v-model="first_name" label="Primer Nombre" required></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field v-model="last_name" label="Primer Apellido" required></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field v-model="email" label="Correo electronico" required></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field v-model="password" label="Contraseña" type="password" required></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field v-model="password_confirmation" label="Confirmar contraseña" type="password" required></v-text-field>
                        </v-col>
                        <v-col style="display: flex; justify-content: flex-end;" cols="6">
                            
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn text color="deep-purple accent-4" href="/" > Crear Cuenta </v-btn>
                </v-card-actions>
                
            </v-card>

        </v-container>
    </div>
</template>
<script>
export default {
    data: () => {
    return {
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        password_confirmation: ""
    };
  }
}
</script>
