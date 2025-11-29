<script setup>
// Composables
import { usePage, Link } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref } from "vue";
import { useSideBar } from "@/composables/useSidebar.js";
import { useConfiguracionStore } from "@/stores/configuracion/configuracionStore";
const configuracionStore = useConfiguracionStore();

const { props } = usePage();
const { toggleSidebar } = useSideBar();

const salir = () => {
    Swal.fire({
        icon: "question",
        title: "Cerrar sesión",
        html: `¿Esta seguro(a) de cerrar sesión?`,
        showCancelButton: true,
        confirmButtonText: "Si, salir",
        cancelButtonText: "Cancelar",
        denyButtonText: `Cancelar`,
        customClass: {
            confirmButton: "btn-success",
        },
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            axios
                .post(route("logout"))
                .then((response) => {})
                .finally(() => {
                    window.location.href = route("portal.index");
                });
        }
    });
};

onMounted(() => {});

onUnmounted(() => {});
</script>
<template>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark bg-principal">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a
                    class="nav-link toggleButton"
                    href="#"
                    role="button"
                    @click="toggleSidebar"
                    ><i class="fas fa-bars"></i
                ></a>
            </li>
            <!-- <li class="nav-item d-none d-sm-inline-block">
                <Link :href="route('pagos.create')" class="nav-link">Nuevo Pago</Link>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <Link :href="route('trabajos.create')" class="nav-link">Nuevo Trabajo</Link>
            </li> -->
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-widget="fullscreen"
                    href="#"
                    role="button"
                    @click.prevent="salir"
                >
                    <i class="fas fa-power-off"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
</template>
