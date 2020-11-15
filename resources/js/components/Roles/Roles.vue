<template>
  <div>
    <v-snackbar
      :color="snackbar.color"
      top
      multi-line
      v-model="snackbar.show"
      :timeout="4000"
      >{{ snackbar.text }}</v-snackbar
    >
    <v-container class="mh-100">
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
            hide-details
          ></v-text-field>
          <v-btn
            @click="createModalOpen = true"
            large
            icon
            color="black"
            dark
            class="ml-3"
          >
            <v-icon>mdi-plus</v-icon>
          </v-btn>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="roles"
          :search="search"
          :items-per-page="10"
        >
          <template v-slot:item.actions="{ item }">
            <!-- <div v-if="item.id < 3"> -->

            <v-menu :close-on-content-click="false" v-model="item.menu_open">
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-eye</v-icon>
                </v-btn>
              </template>
              <v-card>
                <v-list>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title
                        >Permisos con este rol</v-list-item-title
                      >
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list>
                  <v-list-item v-for="menu in menus" :key="menu.id">
                    <v-list-item-action>
                      <v-switch
                        @change="
                          () =>
                            updateRole(
                              item.id,
                              menu.id,
                              item['menu-' + menu.slug]
                            )
                        "
                        :disabled="item.id <= 3"
                        v-model="item['menu-' + menu.slug]"
                        color="purple"
                      ></v-switch>
                    </v-list-item-action>
                    <v-list-item-title>{{ menu.name }}</v-list-item-title>
                  </v-list-item>
                </v-list>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn text @click="item.menu_open = false"> Cerrar </v-btn>
                </v-card-actions>
              </v-card>
            </v-menu>
            <v-btn
              v-if="item.id > 3 && item.users_count == 0"
              icon
              @click="() => deleteRole(item.id)"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
          <!-- hide-default-footer agregar con el pagination -->
        </v-data-table>
      </v-card>

      <NewRoleDialog
        width="80%"
        max-width="450"
        v-model="createModalOpen"
        :onSuccess="onSuccess"
      />
    </v-container>
  </div>
</template>

<script>
import axios from "axios";
import NewRoleDialog from "./NewRoleDialog";
export default {
  props: ["payload", "menus"],
  components: {
    NewRoleDialog,
  },
  data: () => ({
    roles: [],
    createModalOpen: false,
    snackbar: {
      show: false,
      text: "",
      color: "success",
    },
    headers: [
      { align: "center", text: "Nombre del rol", value: "name" },
      { align: "center", text: "Numero de usuarios", value: "users_count" },
      { align: "center", text: "Creado", value: "create_time" },
      { align: "center", text: "Acciones", value: "actions" },
    ],
    search: "",
  }),
  beforeMount() {
    this.roles = this.payload;
    console.log(this.roles);
  },
  methods: {
    updateRole(roleId, menuId, on) {
      console.log(on);
      axios
        .put(`/role/${roleId}/menu/${menuId}`, {
          on: on ? 1 : 0,
        })
        .then((response) => {
          console.log(response);
          this.roles.map((i, index) => {
            this.roles[index] = response.data;
          });
        });
    },
    deleteRole(roleId) {
      axios.delete(`/role/${roleId}`).then((response) => {
        this.roles = this.roles.filter((i, index) => roleId != i.id);
        this.snackbar.text = "Rol borrado de manera exitosa";
        this.snackbar.color = "success";
        this.snackbar.show = true;
      });
    },
    onSuccess(text, payload) {
      if (payload) {
        let holder = [...this.roles];
        holder.push(payload.data);
        this.roles = holder;
        this.roles.sort((r, l) => r.id - l.id);
      }
      this.snackbar.text = text;
      this.snackbar.color = "success";
      this.snackbar.show = true;
    },
  },
  watch: {},
};
</script>

<style>
</style>