<template>
    <b-modal
        v-model="modal"
        id="modal-standard"
        :title="modalTitle"
        title-class="font-18"
        size="lg"
        hide-footer
    >
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Validasi</th>
                        <th>Kembalikan</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
                <tbody v-if="lists?.length">
                    <tr v-for="(list, idx) in lists" :key="idx">
                        <td>{{ ++idx }}</td>
                        <td width="35%">{{ list?.nama }}</td>
                        <td>
                            <b>{{ list?.jml }}</b> Item
                        </td>
                        <td>
                            <b>{{ list.jml_terima }}</b> Item
                        </td>
                        <td>
                            <b>{{ list.jml_kembali }}</b> Item
                        </td>
                        <td>
                            <b>{{ list.jml_akhir }}</b> Item
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <h5 class="fs-14"></h5>
                        </td>
                        <td>
                            <div class="text-end">
                                <button
                                    v-if="status === STATUS_DAFTAR.PENGAJUAN"
                                    class="btn btn-soft-primary w-100"
                                    @click.prevent="
                                        onSubmit({
                                            kode: modalForm.kode,
                                            progress: STATUS_DAFTAR.TERIMA,
                                        })
                                    "
                                >
                                    <i
                                        class="mdi mdi-book-arrow-right-outline"
                                    ></i>
                                    Terima
                                </button>
                                <button
                                    v-else-if="status === STATUS_DAFTAR.TERIMA"
                                    class="btn btn-soft-primary w-100"
                                    @click.prevent="
                                        onSubmit({
                                            kode: modalForm.kode,
                                            progress:
                                                STATUS_DAFTAR.DIKEMBALIKAN,
                                        })
                                    "
                                >
                                    <i class="mdi mdi-keyboard-return"></i>
                                    Kembalikan ke Unit
                                </button>
                                <button
                                    v-else-if="
                                        status === STATUS_DAFTAR.DIKEMBALIKAN &&
                                        $store.state.auth.data.id ===
                                            pcreated_by
                                    "
                                    class="btn btn-soft-primary w-100"
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
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </b-modal>
</template>
<script>
import { STATUS_DAFTAR } from "@/helpers/utils";
import { daftarService } from "@/services/DaftarService";
import { transLinenDetailService } from "@/services/TransLinenDetailService";
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
            this.lists = [];
        },
        async onDelete() {
            this.show();

            const [err] = await transLinenDetailService.delete(
                this.modalForm.kode
            );
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