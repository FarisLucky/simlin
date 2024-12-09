<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        size="sm"
        hide-footer
    >
        <div class="table-responsive">
            <div class="text-end">
                <button
                    v-if="status === STATUS_DAFTAR.PENGAJUAN"
                    class="btn btn-soft-primary"
                    @click.prevent="
                        onSubmit({
                            kode: modalForm.kode,
                            progress: STATUS_DAFTAR.TERIMA,
                        })
                    "
                >
                    <i class="mdi mdi-book-arrow-right-outline"></i>
                    Validasi
                </button>
                <button
                    v-else-if="status === STATUS_DAFTAR.TERIMA"
                    class="btn btn-soft-primary"
                    @click.prevent="
                        onSubmit({
                            kode: modalForm.kode,
                            progress: STATUS_DAFTAR.DIKEMBALIKAN,
                        })
                    "
                >
                    <i class="mdi mdi-keyboard-return"></i>
                    Kembalikan ke Unit
                </button>
                <button
                    v-else-if="
                        status === STATUS_DAFTAR.DIKEMBALIKAN &&
                        $store.state.auth.data.id === pcreated_by
                    "
                    class="btn btn-soft-primary"
                    @click.prevent="
                        onSubmit({
                            kode: modalForm.kode,
                            progress: STATUS_DAFTAR.SELESAI,
                        })
                    "
                >
                    <i class="mdi mdi-content-save-all"></i>
                    Terima
                </button>
            </div>
        </div>
    </b-modal>
</template>
<script>
import { STATUS_DAFTAR } from "@/helpers/utils";
import { daftarService } from "@/services/DaftarService";
import { pinjamDetailService } from "@/services/PinjamDetailService";
import { spinnerMethods, toastMethods } from "@/state/helpers";

export default {
    props: ["status", "pcreated_by", "jenisTrans"],
    data() {
        return {
            modalTitle: "",
            modalUnit: "",
            modalForm: {},
            modal: false,
            lists: [],
            err: [],
            STATUS_DAFTAR,
        };
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        showModal() {
            this.modal = true;
        },
        hideModal() {
            this.modal = false;
        },
        async onDelete() {
            this.show();

            const [err] = await pinjamDetailService.delete(this.modalForm.kode);
            if (err) {
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
        async onSubmit(params) {
            this.show();
            const [err] = await daftarService.updatePengajuan(
                params.kode,
                params
            );
            if (err) {
                if (err.response && err.response.status === 422) {
                    // Capture validation errors from the server
                    this.err = err.response.data.errors;
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
            this.hideModal();
            this.$router.push({ name: "DaftarHarian" });
        },
    },
};
</script>