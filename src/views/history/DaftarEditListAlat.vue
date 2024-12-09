<template>
    <div>
        <BRow class="align-items-center">
            <BCol cols="6">
                <div class="p-1">
                    <h5 class="fs-16 fw-bold d-inline-block">
                        <i class="bx bx-detail me-1"></i>
                        Detail {{ capitalizeFirstLetter($route.params.jenis) }}
                    </h5>
                </div>
            </BCol>
            <BCol cols="6">
                <div class="mb-1 text-end">
                    <button
                        class="btn btn-soft-secondary"
                        type="button"
                        @click.prevent="fetchData"
                    >
                        <i class="mdi mdi-refresh"></i>
                    </button>
                </div>
                <div class="mb-1 text-end">
                    <small>
                        *
                        <b class="text-danger">ctrl+shift+enter</b> untuk
                        <b class="text-danger">simpan</b> setiap kolom
                    </small>
                </div>
            </BCol>
        </BRow>
        <DaftarEditListAlatNota ref="listAlatNotaRef" />
    </div>
</template>
<script>
import { spinnerMethods, toastMethods } from "@/state/helpers";
import { pinjamDetailService } from "@/services/PinjamDetailService";
import { JENIS, ROLE, STATUS_DAFTAR } from "@/helpers/utils";
import DaftarEditListAlatNota from "@/views/history/DaftarEditListAlatNota";

export default {
    components: {
        DaftarEditListAlatNota,
    },
    props: [
        "st_pengajuan",
        "st_kembali",
        "st_terima",
        "st_status",
        "st_created_by",
    ],
    data() {
        return {
            STATUS_DAFTAR,
            JENIS,
        };
    },
    computed: {
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        async fetchData() {
            this.$refs.listAlatNotaRef.fetchData();
        },
        async updateQty(params, id) {
            this.show();

            const [err] = await pinjamDetailService.updateQty(params, id);
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });

                this.hide();
                return;
            }
            this.hide();
            this.fetchData();
        },
        onShow(row) {
            let modalTambah = this.$refs.terimaRef;
            modalTambah.modalTitle = "Ubah Alat";
            modalTambah.modalForm.kode = row.kode;
            modalTambah.modalForm.nama = row.nama;
            modalTambah.modalEditable = true;
            modalTambah.showModal();
        },
        onDestroy(row) {
            let modal = this.$refs.deleteModalRef;
            modal.modalTitle = "Hapus Alat";
            modal.modalForm.id = row.id;
            modal.modalForm.nama = row.nama;
            modal.showModal();
        },
        onFilterSearchFn(event) {
            let val = event.target.value;
            if (val.length >= 3) {
                this.onFilterSearch(val);
                this.fetchData();
            }
            if (val == "") {
                this.onFilterSearch("");
                this.fetchData();
            }
        },
        onTerima() {
            this.onShowTerima({ title: "Terima Alat", items: this.rows });
        },
        onShowTerima(params) {
            let modalTambah = this.$refs.terimaRef;
            modalTambah.modalTitle = params.title;
            modalTambah.modalForm.kode = this.$route.params.kode;
            modalTambah.lists = params.items;
            modalTambah.showModal();
        },
        onRefreshNota() {
            this.fetchData();
        },
        capitalizeFirstLetter(charc) {
            return charc.charAt(0).toUpperCase() + charc.slice(1);
        },
    },
};
</script>