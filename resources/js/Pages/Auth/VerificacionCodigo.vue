<script setup>
import MiModal from "@/Components/MiModal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
const props = defineProps({
    muestra_formulario: {
        type: Boolean,
        default: false,
    },
    usuario: {
        type: Object,
        default: null,
    },
});

const muestra_form = ref(props.muestra_formulario);
const enviando = ref(false);
let form = useForm({
    codigo: "",
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

const textBtn = computed(() => {
    if (enviando.value) {
        return `<i class="fa fa-spin fa-spinner"></i> Enviando...`;
    }
    return `<i class="fa fa-sign-in"></i> Verificar`;
});

const errors = ref([]);
const enviarFormulario = () => {
    enviando.value = true;
    axios
        .post(
            route("codigoVerificacion.verificarCodigo", props.usuario.id ?? 0),
            form
        )
        .then((response) => {
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
            if (error.response?.status === 422 && error.response.data.errors) {
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
                <h5 class="w-100 text-center font-weight-bold">
                    Ingresar código de verificación
                </h5>
                <div class="row">
                    <div class="input-group form-floating">
                        <span class="input-group-text bg-principal">
                            <i class="fa fa-hashtag"></i>
                        </span>
                        <input
                            type="text"
                            name="codigo"
                            id="codigo"
                            class="form-control fs-13px h-45px"
                            placeholder="Código de verificación"
                            v-model="form.codigo"
                            autofocus
                            ref="inputUsuario"
                        />
                        <label
                            for="codigo"
                            class="d-flex align-items-center text-gray-600 fs-13px ml-5"
                            style="z-index: 100"
                            >Código de verificación</label
                        >
                    </div>
                    <ul
                        v-if="errors?.error"
                        class="text-danger list-unstyled mb-0"
                    >
                        <li class="parsley-required">
                            {{ errors?.error[0] }}
                        </li>
                    </ul>
                    <ul
                        v-if="errors?.codigo"
                        class="text-danger list-unstyled mb-0"
                    >
                        <li class="parsley-required">
                            {{ errors?.codigo[0] }}
                        </li>
                    </ul>
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
</template>

<style>
.logo {
    margin: auto;
    width: 100px;
}
</style>
