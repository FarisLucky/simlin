<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        size="lg"
        hide-footer
    >
        <form @submit.prevent="onSubmit">
            <BRow class="g-2 align-items-end">
                <BCol v-if="modalEditable" sm="2">
                    <label>Kode</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="modalForm.kode"
                        :disabled="modalEditable"
                    />
                    <span v-if="modalErr?.kode" class="text-danger">
                        {{ modalErr.kode[0] }}
                    </span>
                </BCol>
                <BCol sm="6" md="4">
                    <label>Linen</label>
                    <v-select
                        v-model="modalForm.kode_linen"
                        :options="listLinens"
                        label="nama"
                        :reduce="(linen) => linen.kode"
                        placeholder="Pilih Linen"
                    >
                    </v-select>
                    <span v-if="modalErr?.kode_linen" class="text-danger">
                        {{ modalErr.kode_linen[0] }}
                    </span>
                </BCol>
                <BCol sm="6" md="4">
                    <label>Unit</label>
                    <v-select
                        v-model="modalForm.kode_unit"
                        :options="listUnits"
                        label="nama"
                        :reduce="(unit) => unit.kode"
                        placeholder="Pilih Unit"
                    >
                    </v-select>
                    <span v-if="modalErr?.kode_unit" class="text-danger">
                        {{ modalErr.kode_unit[0] }}
                    </span>
                </BCol>
                <BCol sm="4" md="2">
                    <label>Terpakai</label>
                    <input
                        v-model="modalForm.terpakai"
                        type="number"
                        class="form-control"
                    />
                    <span v-if="modalErr?.terpakai" class="text-danger">
                        {{ modalErr?.terpakai[0] }}
                    </span>
                </BCol>
                <BCol sm="4" md="2">
                    <label>Bersih</label>
                    <input
                        v-model="modalForm.bersih"
                        type="number"
                        class="form-control"
                    />
                    <span v-if="modalErr?.bersih" class="text-danger">
                        {{ modalErr.bersih[0] }}
                    </span>
                </BCol>
                <BCol sm="4" md="2">
                    <label>Kotor</label>
                    <input
                        v-model="modalForm.kotor"
                        type="number"
                        class="form-control"
                    />
                    <span v-if="modalErr?.kotor" class="text-danger">
                        {{ modalErr.kotor[0] }}
                    </span>
                </BCol>
                <BCol sm="3">
                    <label>Status</label>
                    <v-select
                        v-model="modalForm.status"
                        :options="[
                            {
                                id: 1,
                                label: 'AKTIF',
                            },
                            {
                                id: 0,
                                label: 'NONAKTIF',
                            },
                        ]"
                        :reduce="(st) => st.id"
                        placeholder="Pilih Status"
                    >
                    </v-select>
                    <span v-if="modalErr?.status" class="text-danger">
                        {{ modalErr.status[0] }}
                    </span>
                </BCol>
                <BCol>
                    <BButton
                        v-if="isSuperAdmin"
                        type="submit"
                        variant="primary"
                        :disabled="modalSubmit"
                    >
                        {{ modalSubmit ? "Sedang Proses" : "Simpan" }}
                    </BButton>
                </BCol>
            </BRow>
        </form>
    </b-modal>
</template>
<script>
import { ROLE } from "@/helpers/utils";
import { mLinenService } from "@/services/MLinenService";
import { mLinenUnitService } from "@/services/MLinenUnitService";
import { mUnitService } from "@/services/MUnitService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

const initFormData = () => ({
    kode: "",
    nama: "",
    kode_linen: "",
    kode_unit: "",
    bersih: 0,
    kotor: 0,
    terpakai: 0,
    status: "",
});

export default {
    data() {
        return {
            modalForm: initFormData(),
            modalErr: {},
            modalTitle: "Tambah Linen",
            modalSubmit: false,
            modalEditable: false,
            modal: false,
            listUnits: [],
            listLinens: [],
        };
    },
    watch: {
        "modalForm.nama"(newValue) {
            this.modalForm.nama = newValue.toUpperCase();
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
        async getListUnit() {
            this.show();

            const [err, resp] = await mUnitService.data();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.listUnits = resp.data;
            this.hide();
        },
        async getListLinen() {
            this.show();

            const [err, resp] = await mLinenService.data();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();
                return;
            }
            this.listLinens = resp.data;
            this.hide();
        },
        async onStore() {
            this.show();
            const [err] = await mLinenUnitService.store(this.modalForm);
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
            const [err] = await mLinenUnitService.update(
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
                this.hide();
                return;
            }
            this.hide();
            this.$emit("fetch");
            this.hideModal();
        },
        // inputKotor(event) {
        //     let val = event.target.value;
        //     this.modalForm.kotor = val;
        // },
        // inputBersih(event) {
        //     let val = event.target.value;
        //     this.modalForm.bersih = val;
        // },
        // inputCuci(event) {
        //     let val = event.target.value;
        //     this.modalForm.cuci = val;
        // },
    },
};
</script>
<style lang="">
</style>