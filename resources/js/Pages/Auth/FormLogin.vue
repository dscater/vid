<script setup>
import MiModal from "@/Components/MiModal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
import FormLogin from "@/Pages/Auth/FormLogin.vue";
import VerificacionCodigo from "./VerificacionCodigo.vue";
const props = defineProps({
    muestra_formulario: {
        type: Boolean,
        default: false,
    },
});

const { oUsuario, limpiarUsuario } = useUsuarios();
const muestra_form = ref(props.muestra_formulario);
const enviando = ref(false);
let form = useForm({
    usuario: "",
    password: "",
});
const inputUsuario = ref(null);
watch(
    () => props.muestra_formulario,
    async (newValue) => {
        muestra_form.value = newValue;
        if (muestra_form.value) {
            await nextTick(); // espera a que el DOM se actualice
            inputUsuario.value.focus(); // ahora sí funciona
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
        } else {
            document
                .getElementsByTagName("body")[0]
                .classList.remove("modal-open");
        }
    }
);

const { flash, url_assets } = usePage().props;
const tituloDialog = computed(() => {
    return `<i class="fa fa-edit"></i> Usuario`;
});

const textBtn = computed(() => {
    if (enviando.value) {
        return `<i class="fa fa-spin fa-spinner"></i> Enviando...`;
    }
    return `<i class="fa fa-sign-in"></i> Ingresar`;
});

const muestra_password = ref(false);
const errors = ref([]);
const muestraFormCodigo = ref(false);
const oUserVerificado = ref(null);

const enviarFormulario = () => {
    enviando.value = true;
    axios
        .post(route("login"), form)
        .then((response) => {
            form.usuario = "";
            form.password = "";

            // ENVIAR AL INICIO
            Swal.fire({
                icon: "success",
                title: "Correcto",
                html: `<strong>Sesión iniciada correctamente</strong>`,
                confirmButtonText: `Aceptar`,
                customClass: {
                    confirmButton: "btn-alert-success",
                },
            });
            setTimeout(() => {
                window.location.href = route("inicio");
            });
        })
        .catch((error) => {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors || {};
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    html: `<strong>${error.message}</strong>`,
                    confirmButtonText: `Aceptar`,
                    customClass: {
                        confirmButton: "btn-error",
                    },
                });
            }
        })
        .finally(() => {
            enviando.value = false;
        });
};

const emits = defineEmits(["cerrar-formulario", "envio-formulario"]);

watch(muestra_form, (newVal) => {
    if (!newVal) {
        emits("cerrar-formulario");
    }
});

const cerrarFormulario = () => {
    errors.value = [];
    muestra_form.value = false;
    document.getElementsByTagName("body")[0].classList.remove("modal-open");
};

onMounted(() => {});
</script>

<template>
    <MiModal
        :open_modal="muestra_form"
        @close="cerrarFormulario"
        :size="'modal-xl'"
        :header-class="'bg-principal'"
        :footer-class="'justify-content-between'"
    >
        <template #header>
            <div class="w-100 text-center">
                <img
                    :src="url_assets + 'imgs/emblemaUnipol.png'"
                    class="logo"
                    alt=""
                />
            </div>

            <button
                type="button"
                class="close"
                @click.prevent="cerrarFormulario()"
            >
                <span aria-hidden="true">×</span>
            </button>
        </template>

        <template #body>
            <form @submit.prevent="enviarFormulario()">
                <h5 class="w-100 text-center h4">Iniciar Sesión</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="input-group form-floating">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-principal">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input
                                type="text"
                                name="usuario"
                                id="usuario"
                                class="form-control"
                                placeholder="Usuario"
                                v-model="form.usuario"
                                autofocus
                                ref="inputUsuario"
                                @keypress.enter="enviarFormulario"
                            />
                            <label
                                for="usuario"
                                class="d-flex align-items-center text-gray-600 fs-13px ml-5"
                                style="z-index: 100"
                                >Usuario</label
                            >
                        </div>
                        <ul
                            v-if="errors?.usuario"
                            class="text-danger list-unstyled mb-0"
                        >
                            <li class="parsley-required">
                                {{ errors?.usuario[0] }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="input-group form-floating mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-principal">
                                    <i class="fa fa-key"></i>
                                </span>
                            </div>
                            <input
                                :type="muestra_password ? 'text' : 'password'"
                                name="password"
                                id="password"
                                class="form-control"
                                v-model="form.password"
                                autocomplete="false"
                                placeholder="Contraseña"
                                @keypress.enter="enviarFormulario"
                            />
                            <label
                                for="password"
                                class="d-flex align-items-center text-gray-600 fs-13px ml-5"
                                style="z-index: 100"
                                >Contraseña</label
                            >
                            <div class="input-group-append">
                                <button
                                    class="btn btn-default"
                                    type="button"
                                    @click="
                                        muestra_password = !muestra_password
                                    "
                                >
                                    <i
                                        class="fa"
                                        :class="[
                                            muestra_password
                                                ? 'fa-eye'
                                                : 'fa-eye-slash',
                                        ]"
                                    ></i>
                                </button>
                            </div>
                        </div>
                        <ul
                            v-if="errors?.password"
                            class="text-danger list-unstyled"
                        >
                            <li class="parsley-required">
                                {{ errors?.password[0] }}
                            </li>
                        </ul>
                    </div>
                    <div class="w-100" v-if="form.errors?.usuario">
                        <span
                            class="invalid-feedback alert alert-danger"
                            style="display: block"
                            role="alert"
                        >
                            <strong>{{ errors.usuario }}</strong>
                        </span>
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-danger"
                @click.prevent="cerrarFormulario()"
            >
                <i class="fa fa-times"></i> Cancelar
            </button>
            <button
                type="button"
                class="btn btn-success"
                @click.prevent="enviarFormulario"
                :disabled="enviando"
                v-html="textBtn"
            ></button>
        </template>
    </MiModal>

    <VerificacionCodigo
        :muestra_formulario="muestraFormCodigo"
        :usuario="oUserVerificado"
        @cerrar-formulario="muestraFormCodigo = false"
    ></VerificacionCodigo>
</template>

<style>
.logo {
    margin: auto;
    width: 100px;
}
</style>
