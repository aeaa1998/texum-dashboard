<template>
  <div>
    <v-app-bar dark>
      <v-app-bar-nav-icon v-if="group !== 0" @click="drawer = true"></v-app-bar-nav-icon>

      <v-toolbar-title>Texsun S.A. Dashboard</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu
        v-model="accountShortcutOpen"
        open-on-hover
        close-on-click
        close-on-content-click
        offset-y
      >
        <template v-slot:activator="{ on }">
          <v-icon dark v-on="on">mdi-account</v-icon>
        </template>
        <v-list dark>
          <v-list-item class="pointer" v-for="(item, index) in accountItems" :key="index">
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list nav dense>
        <v-list-item-group v-model="group" active-class="deep-purple--text text--accent-4">
          <v-list-item>
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item>

          <!-- <v-list-item>
            <v-list-item-icon>
              <v-icon>mdi-account</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Account</v-list-item-title>
          </v-list-item>-->
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
    <div id="target">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  data: () => ({
    accountShortcutOpen: false,
    group: null,
    drawer: false,
    visible: true,
    routes: [0, 1],
    items: [
      { title: "Pagina Inicial" },
      { title: "Opcion 2" },
      { title: "Opcion 3" },
      { title: "Cerrar Sesion" }
    ],
    accountItems: [
      { title: "Perfil de usuario" },
      { title: "Ajustes" },
      { title: "Cerrar sesión" }
    ]
  }),
  beforeMount() {
    this.group = 0;
  }
};
</script>
<style scoped>
</style>