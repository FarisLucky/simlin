<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        hide-footer
    >
        <form @submit.prevent="onSubmit">
            <BRow class="g-2">
                <BCol sm="4">
                    <div class="mb-1">
                        <label>Kode</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="modalForm.kode"
                            :disabled="modalEditable"
                        />
                        <span v-if="modalErr?.kode" class="text-danger">
                            {{ modalErr.kode[0] }}</span
                        >
                    </div>
                </BCol>
                <BCol sm="8">
                    <div class="mb-1">
                        <label>Nama</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="modalForm.nama"
                        />
                        <span v-if="modalErr?.nama" class="text-danger">
                            {{ modalErr.nama[0] }}</span
                        >
                    </div>
                </BCol>
                <BCol sm="12">
                    <div class="text-end">
                        <BButton
                            v-if="isSuperAdmin"
                            type="submit"
                            variant="primary"
                            :disabled="modalSubmit"
                        >
                            {{ modalSubmit ? "Sedang Proses" : "Simpan" }}
                        </BButton>
                    </div>
                </BCol>
            </BRow>
        </form>
    </b-modal>
</template>
<script>
import { ROLE } from "@/helpers/utils";
import { mUnitService } from "@/services/MUnitService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

const initFormData = () => ({
    kode: "",
    nama: "",
});

export default {
    data() {
        return {
            modalForm: initFormData(),
            modalErr: {},
            modalTitle: "Tambah Unit",
            modalSubmit: false,
            modalEditable: false,
            modal: false,
        };
    },
    watch: {
        "modalForm.kode"(newValue) {
            this.modalForm.kode = newValue.toUpperCase();
        },
    },
    computed: {
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        showModal() {
            this.modal = true;
        },
        hideModal() {
            this.$data.modalForm = initFormData();
            this.modalEditable = false;
            this.modal = false;
        },
        resetForm() {
            this.modalForm = Object.assign({}, initFormData());
            this.modalErr = {};
        },
        onSubmit() {
            if (this.modalEditable) {
                this.onUpdate();
            } else {
                this.onStore();
            }
        },
        async onStore() {
            this.show();
            const [err] = await mUnitService.store(this.modalForm);
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.modalErr = err.response.data.errors;
                    this.hide(false);
                    return;
                }
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.hide();
            this.$emit("fetch");
            this.resetForm();
        },
        async onUpdate() {
            this.show();
            const [err] = await mUnitService.update(
                this.modalForm,
                this.modalForm.kode
            );
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.modalErr = err.response.data.errors;
                    this.hide();
                    return;
                }
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.show();
                return;
            }
            this.hide();
            this.$emit("fetch");
            this.hideModal();
        },
    },
};
</script>
<style lang="">
</style>