<template>
  <div class="mh-100 main-bg">
    <v-container>
      <v-card>
        <v-card-title>
          Roles de los trabajadores
          <v-spacer></v-spacer>
          <v-text-field
            append-icon="mdi-magnify"
            label="Búsqueda detallada"
            v-model="search"
            search="search"
            single-line
            hide-details></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="roles"
          :search="search"
          >
          <!-- hide-default-footer agregar con el pagination -->
        </v-data-table>
      </v-card>
    </v-container>
    

  </div>
</template>

<script>
import axios from "axios"
export default {
  props: ["roles"],
  data: () => ({
    headers: [
    {align: 'center', text: 'id', value: 'id'},
    {align: 'center', text: 'role id', value: 'role_id'},
    {align: 'center', text: 'Email', value: 'email'},
    {align: 'center', text: 'Creado', value: 'created_at'},
    ],
    rols: [],
    search: '',
  }),

  beforeMount() {
    this.rols = this.roles.data
    console.log(this.roles)
  }
  
  
}
</script>

<style>

</style>