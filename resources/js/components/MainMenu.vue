<template>
  <v-container class="mt-2">
    <v-row justify-center>
      <v-col
        cols="12"
        md="6"
        v-for="menuOption in menuOptions"
        :key="menuOption.title"
        class="pointer"
      >
        <a :href="menuOption.href">
          <v-card shaped raised height="350">
            <v-img height="100%" :src="menuOption.img" class="position-relative pt-2 pb-2">
              <div class="h-35 grey lighten-1 fixed-bottom-absolute">
                <h1 class="white--text text-center mt-1">{{menuOption.title}}</h1>
                <h5 class="white--text text-center mt-5 pl-5 pr-5">{{menuOption.label}}</h5>
              </div>
            </v-img>
          </v-card>
        </a>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  data: () => ({
    menuOptions: [
      {
        title: "Busqueda Avanzada",
        img: "images/texum-logo.jpeg",
        label: "Busqueda de la paqueteria con críterios más especificos.",
        href: "/packages/general"
      },
      {
        title: "Historial",
        img: "images/records-image.jpg",
        label:
          "Lleva un registro de tus paquetes de donde vienen y a donde han sido reubicados.",
        href: "#"
      },
      {
        title: "Mapa de bodega",
        img: "images/map.jpg",
        label:
          "Ubicación de tu paqueteria dentro de la bodega. Visualiza tus paquetes en donde estan ubicados de manera gráfica.",
        href: "#"
      },
      {
        title: "Mover paquetes",
        img: "images/move-package.jpg",
        label: "Reubica los paquetes que han sido ingresados en bodega.",
        href: "#"
      }
    ]
  })
};
</script>

