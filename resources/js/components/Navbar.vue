<template>
  <v-toolbar>
      <v-toolbar-title>TexSun S.A</v-toolbar-title>
      <v-spacer></v-spacer>

      <!-- drop down -->
      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn v-on="on">
            <v-icon>mdi-account-circle</v-icon>
          </v-btn>
        </template>
          <v-list>
            <v-list-item v-for="(item, index) in items" :key="index" >
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </v-list>
      </v-menu> 
  </v-toolbar>
</template>
<script>
  export default {
    data: () => ({
      items: [
        { title: 'Pagina Inicial' },
        { title: 'Opcion 2' },
        { title: 'Opcion 3' },
        { title: 'Cerrar Sesion' },
      ],
    }),
  }
</script>