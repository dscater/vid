<script setup>
import { onMounted, onUnmounted, ref, nextTick } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import ItemMenu from "@/Components/ItemMenu.vue";
import { useSideBar } from "@/composables/useSidebar.js";
import { useAppStore } from "@/stores/aplicacion/appStore";
import { useConfiguracionStore } from "@/stores/configuracion/configuracionStore";
const { closeSidebar, toggleSubMenuELem } = useSideBar();
const { auth } = usePage().props;
const configuracionStore = useConfiguracionStore();
const appStore = useAppStore();
const usuario = ref(null);
const permisos = ref([]);
const toggleSubMenu = (e) => {
    e.stopPropagation();
    const elem = e.currentTarget;
    if (
        elem.classList.contains("menu-is-opening") &&
        elem.classList.contains("menu-open")
    ) {
        elem.classList.remove("menu-is-opening");
        elem.classList.remove("menu-open");
        toggleSubMenuELem(elem, false);
    } else {
        elem.classList.add("menu-is-opening");
        elem.classList.add("menu-open");
        toggleSubMenuELem(elem, true);
    }
};

const route_current = ref("");
router.on("navigate", (event) => {
    route_current.value = route().current();
    closeSidebar();
});

onMounted(() => {
    configuracionStore.initConfiguracion();
    usuario.value = appStore.getUsuario;
    permisos.value = auth.permisos;
    // Selecciona el elemento del widget
    var sidebarSearchElement = $('[data-widget="sidebar-search"]');
    // Configura manualmente el texto de "no encontrado"
    sidebarSearchElement.data("notFoundText", "Sin resultados");
});

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

onUnmounted(() => {});
</script>
<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-success elevation-4">
        <!-- Brand Logo -->
        <a :href="route('inicio')" class="brand-link">
            <img
                :src="configuracionStore.oConfiguracion.url_logo"
                alt="Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8"
            />
            <span
                class="brand-text font-weight-light title_Chau_Philomene_One"
                >{{ configuracionStore.oConfiguracion.nombre_sistema }}</span
            >
        </a>

        <!-- Sidebar -->
        <div class="sidebar p-0">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="usuario?.url_foto"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <Link :href="route('profile.edit')" class="d-block">{{
                        usuario?.full_name
                    }}</Link>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <ItemMenu
                        :label="'Inicio'"
                        :ruta="'inicio'"
                        :icon="'fa fa-home'"
                    ></ItemMenu>
                    <li
                        class="nav-header font-weight-bold bg-principal"
                        v-if="
                            permisos == '*' ||
                            permisos.includes('usuarios.index') ||
                            permisos.includes('postulantes.index') ||
                            permisos.includes('postulantes.preinscripcion') ||
                            permisos.includes('requisitos.buscar') ||
                            permisos.includes('evaluacion_medicas.index') ||
                            permisos.includes('evaluacion_fisicas.index') ||
                            permisos.includes(
                                'evaluacion_instruccions.index'
                            ) ||
                            permisos.includes(
                                'evaluacion_conocimientos.index'
                            ) ||
                            permisos.includes('lengua_castellanas.index') ||
                            permisos.includes('matematica_fisicas.index') ||
                            permisos.includes('ciencias_sociales.index') ||
                            permisos.includes('historial_policials.index') ||
                            permisos.includes('instruccion_policials.index') ||
                            permisos.includes(
                                'acondicionamiento_fisicos.index'
                            ) ||
                            permisos.includes('revocar_accesos.habilitar') ||
                            permisos.includes('revocar_accesos.deshabilitar') ||
                            permisos.includes('roles.index')
                        "
                    >
                        ADMINISTRACIÓN
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos == '*' ||
                            permisos.includes('evaluacion_medicas.index') ||
                            permisos.includes('evaluacion_fisicas.index') ||
                            permisos.includes(
                                'evaluacion_instruccions.index'
                            ) ||
                            permisos.includes('evaluacion_conocimientos.index')
                        "
                    >
                        <a
                            href="#"
                            class="nav-link sub-menu"
                            :class="[
                                route_current == 'evaluacion_medicas.index' ||
                                route_current == 'evaluacion_fisicas.index' ||
                                route_current ==
                                    'evaluacion_instruccions.index' ||
                                route_current ==
                                    'evaluacion_conocimientos.index'
                                    ? 'active menu-is-opening menu-open'
                                    : '',
                            ]"
                            @click.stop="toggleSubMenu($event)"
                        >
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>
                                Evaluaciones
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'evaluacion_medicas.index'
                                    )
                                "
                                :label="'Evaluación Médica'"
                                :ruta="'evaluacion_medicas.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'evaluacion_fisicas.index'
                                    )
                                "
                                :label="'Evaluación del Área de Aptitud Física'"
                                :ruta="'evaluacion_fisicas.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'evaluacion_instruccions.index'
                                    )
                                "
                                :label="'Evaluación del Área de Instrucción Policial'"
                                :ruta="'evaluacion_instruccions.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'evaluacion_conocimientos.index'
                                    )
                                "
                                :label="'Evaluación del Área de Conocimientos'"
                                :ruta="'evaluacion_conocimientos.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                        </ul>
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos == '*' ||
                            permisos.includes('lengua_castellanas.index') ||
                            permisos.includes('matematica_fisicas.index') ||
                            permisos.includes('ciencias_sociales.index') ||
                            permisos.includes('historial_policials.index') ||
                            permisos.includes('instruccion_policials.index') ||
                            permisos.includes('acondicionamiento_fisicos.index')
                        "
                    >
                        <a
                            href="#"
                            class="nav-link sub-menu"
                            :class="[
                                route_current == 'lengua_castellanas.index' ||
                                route_current == 'matematica_fisicas.index' ||
                                route_current == 'ciencia_socials.index' ||
                                route_current == 'historial_policials.index' ||
                                route_current ==
                                    'instruccion_policials.index' ||
                                route_current ==
                                    'acondicionamiento_fisicos.index'
                                    ? 'active menu-is-opening menu-open'
                                    : '',
                            ]"
                            @click.stop="toggleSubMenu($event)"
                        >
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>
                                Prefacultativos
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'lengua_castellanas.index'
                                    )
                                "
                                :label="'Lengua Castellana'"
                                :ruta="'lengua_castellanas.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'matematica_fisicas.index'
                                    )
                                "
                                :label="'Matemáticas-Física'"
                                :ruta="'matematica_fisicas.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('ciencia_socials.index')
                                "
                                :label="'Ciencias Sociales'"
                                :ruta="'ciencia_socials.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'historial_policials.index'
                                    )
                                "
                                :label="'Historia Policial'"
                                :ruta="'historial_policials.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'instruccion_policials.index'
                                    )
                                "
                                :label="'Instrucción Policial'"
                                :ruta="'instruccion_policials.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'acondicionamiento_fisicos.index'
                                    )
                                "
                                :label="'Acondicionamiento Físico'"
                                :ruta="'acondicionamiento_fisicos.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                        </ul>
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos == '*' ||
                            permisos.includes('postulantes.index') ||
                            permisos.includes('postulantes.inscritos') ||
                            permisos.includes('postulantes.preinscripcion') ||
                            permisos.includes('requisitos.buscar') ||
                            permisos.includes('revocar_accesos.habilitar') ||
                            permisos.includes('revocar_accesos.deshabilitar')
                        "
                    >
                        <a
                            href="#"
                            class="nav-link sub-menu"
                            :class="[
                                route_current == 'postulantes.index' ||
                                route_current == 'postulantes.inscritos' ||
                                route_current == 'postulantes.preinscripcion' ||
                                route_current == 'requisitos.buscar' ||
                                route_current == 'revocar_accesos.index'
                                    ? 'active menu-is-opening menu-open'
                                    : '',
                            ]"
                            @click.stop="toggleSubMenu($event)"
                        >
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Postulantes
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'postulantes.preinscripcion'
                                    )
                                "
                                :label="'Preinscripción'"
                                :ruta="'postulantes.preinscripcion'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('postulantes.index')
                                "
                                :label="'Lista de Postulantes'"
                                :ruta="'postulantes.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    auth.user.role_id == 1 ||
                                    auth.user.role_id == 2
                                "
                                :label="'Inscritos'"
                                :ruta="'postulantes.inscritos'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('requisitos.buscar')
                                "
                                :label="'Verificación de requisitos'"
                                :ruta="'requisitos.buscar'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'revocar_accesos.habilitar'
                                    ) ||
                                    permisos.includes(
                                        'revocar_accesos.deshabilitar'
                                    )
                                "
                                :label="'Revocar Acceso'"
                                :ruta="'revocar_accesos.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                        </ul>
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos == '*' ||
                            permisos.includes('usuarios.index') ||
                            permisos.includes('roles.index')
                        "
                    >
                        <a
                            href="#"
                            class="nav-link sub-menu"
                            :class="[
                                route_current == 'usuarios.index' ||
                                route_current == 'roles.index'
                                    ? 'active menu-is-opening menu-open'
                                    : '',
                            ]"
                            @click.stop="toggleSubMenu($event)"
                        >
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Usuarios
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('usuarios.index')
                                "
                                :label="'Usuarios'"
                                :ruta="'usuarios.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('roles.index')
                                "
                                :label="'Roles'"
                                :ruta="'roles.index'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                        </ul>
                    </li>
                    <li
                        class="nav-header font-weight-bold bg-principal"
                        v-if="
                            permisos == '*' ||
                            permisos.includes('reportes.usuarios') ||
                            permisos.includes('reportes.postulantes') ||
                            permisos.includes('reportes.inscritos') ||
                            permisos.includes('reportes.ginscripcions') ||
                            permisos.includes('reportes.historial_accions')
                        "
                    >
                        REPORTES
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos == '*' ||
                            permisos.includes('reportes.usuarios') ||
                            permisos.includes('reportes.postulantes') ||
                            permisos.includes('reportes.inscritos') ||
                            permisos.includes('reportes.ginscripcions') ||
                            permisos.includes('reportes.historial_accions')
                        "
                    >
                        <a
                            href="#"
                            class="nav-link sub-menu"
                            :class="[
                                route_current == 'reportes.usuarios' ||
                                route_current == 'reportes.postulantes' ||
                                route_current == 'reportes.inscritos' ||
                                route_current == 'reportes.ginscripcions' ||
                                route_current == 'reportes.historial_accions'
                                    ? 'active menu-is-opening menu-open'
                                    : '',
                            ]"
                            @click.stop="toggleSubMenu($event)"
                        >
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Reportes
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('reportes.usuarios')
                                "
                                :label="'Usuarios'"
                                :ruta="'reportes.usuarios'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('reportes.postulantes')
                                "
                                :label="'Lista de Preinscritos'"
                                :ruta="'reportes.postulantes'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('reportes.inscritos')
                                "
                                :label="'Lista de Inscritos'"
                                :ruta="'reportes.inscritos'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes('reportes.ginscripcions')
                                "
                                :label="'Gráfico Inscripciones'"
                                :ruta="'reportes.ginscripcions'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                            <ItemMenu
                                v-if="
                                    permisos == '*' ||
                                    permisos.includes(
                                        'reportes.historial_accions'
                                    )
                                "
                                :label="'Historial de Acciones'"
                                :ruta="'reportes.historial_accions'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                        </ul>
                    </li>
                    <li class="nav-header font-weight-bold bg-principal">
                        OTROS
                    </li>
                    <ItemMenu
                        v-if="
                            permisos == '*' ||
                            permisos.includes('descarga_documentos.index')
                        "
                        :label="'Documentos de descarga'"
                        :ruta="'descarga_documentos.index'"
                        :icon="'fa fa-file-download'"
                    ></ItemMenu>
                    <ItemMenu
                        v-if="
                            permisos == '*' ||
                            permisos.includes('comunicados.index')
                        "
                        :label="'Comunicados'"
                        :ruta="'comunicados.index'"
                        :icon="'fa fa-bullhorn'"
                    ></ItemMenu>
                    <ItemMenu
                        v-if="
                            permisos == '*' ||
                            permisos.includes('configuracions.index')
                        "
                        :label="'Configuración Sistema'"
                        :ruta="'configuracions.index'"
                        :icon="'fa fa-cog'"
                    ></ItemMenu>
                    <ItemMenu
                        v-if="permisos == '*' || permisos.includes('backup_db')"
                        :label="'Backup'"
                        :ruta="'backup_db'"
                        :icon="'fa fa-database'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Perfil'"
                        :ruta="'profile.edit'"
                        :icon="'fa fa-id-card'"
                    ></ItemMenu>
                    <li class="nav-item">
                        <a
                            href="#"
                            class="nav-link"
                            @click.prevent="salir()"
                            ref="link"
                        >
                            <i class="nav-icon fa fa-power-off"></i>
                            <p>Salir</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>
<style scoped></style>
