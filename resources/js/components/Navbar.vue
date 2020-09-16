<template>
  <div class="overflow-hidden">
    <v-app-bar
      dark
      style="z-index: 2;"
      absolute
      :collapse="!collapseOnScroll"
      :collapse-on-scroll="collapseOnScroll"
      scroll-target="#target"
    >
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
          <v-list-item
            class="pointer"
            v-for="(item, index) in accountItems"
            :key="index"
            :href="item.action"
          >
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list nav dense>
        <v-list-item-group v-model="group" active-class="deep-purple--text text--accent-4 ">
          <a v-for="item in drawerItems" :key="item.title" :href="item.href" class="no-underline">
            <v-list-item>
              <v-list-item-icon>
                <v-icon>{{item.icon}}</v-icon>
              </v-list-item-icon>

              <v-list-item-title>{{item.title}}</v-list-item-title>
            </v-list-item>
          </a>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <div id="target" class="vh-100 overflow-y-auto content-padding main-bg">
      <slot></slot>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data: () => ({
    collapseOnScroll: true,
    accountShortcutOpen: false,
    group: null,
    drawer: false,
    visible: true,
    routes: [0, 1],
    drawerItems: [
      {
        icon: "mdi-home",
        title: "Home",
        href: "/home"
      },
      {
        icon: "mdi-package-variant-closed",
        title: "Paquetería",
        href: "/packages/general"
      },
      {
        icon: "mdi-file-multiple",
        title: "Historial",
        href: "/records/general"
      },
      {
        icon: "mdi-account-group",
        title: "Tabla trabajadores",
        href: "/workers-table"
      },
      {
        icon: "mdi-account-cog",
        title: "Roles Trabajadores",
        href: "/roles"
      }
    ],
    accountItems: [
      { title: "Perfil de usuario", action: "/profile" },
      { title: "Ajustes", action: "#" },
      { title: "Cerrar sesión", action: "/logout" }
    ]
  }),
  beforeMount() {
    this.group = window.location.pathname !== "/home" ? 1 : 0;
    // console.log(window.location.pathname);
    // this.group = 0;
  }
};
</script>
<style scoped>
.no-underline {
  text-decoration: none;
}
.main-bg {
  background: url("/../images/main-bg.jpg") no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.content-padding {
  padding-top: 90px;
}
</style>