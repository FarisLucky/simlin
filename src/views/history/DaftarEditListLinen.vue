<template>
    <div>
        <div class="mb-1">
            <EasyDataTable
                :loading="loading"
                :headers="columns"
                :items="rows"
                theme-color="#556ee6"
                show-index
                table-class-name="no-border-table customize-table"
                must-sort
            >
                <template #empty-message>
                    <h5>Belum ada detail</h5>
                </template>
                <template #item-berat="item">
                    <span>{{ item.berat }} KG</span>
                </template>
                <template #item-jml="item">
                    <span>{{ item.jml }} ITEM</span>
                </template>
                <template #item-jml_terima="item">
                    <span>{{ item.jml_terima }} ITEM</span>
                </template>
                <template #item-jml_kembali="item">
                    <span>{{ item.jml_kembali }} ITEM</span>
                </template>
                <template #item-jml_akhir="item">
                    <span>{{ item.jml_akhir }} ITEM</span>
                </template>
            </EasyDataTable>
        </div>
    </div>
</template>
<script>
import {
    daftarMethods,
    daftarState,
    spinnerMethods,
    toastMethods,
} from "@/state/helpers";
import { transLinenDetailService } from "@/services/TransLinenDetailService";
import { ROLE, STATUS_DAFTAR } from "@/helpers/utils";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "created_at",
    sortType: "DESC",
});

export default {
    props: ["st_pengajuan", "st_kembali", "st_terima", "st_status"],
    data() {
        return {
            columns: [
                {
                    text: "Kode",
                    value: "kode_linen_unit",
                    sortable: true,
                },
                {
                    text: "Nama",
                    value: "nama",
                    sortable: true,
                    fixed: true,
                },
                {
                    text: "Berat",
                    value: "berat",
                    sortable: false,
                },
                {
                    text: "Jumlah",
                    value: "jml",
                    sortable: false,
                    width: 100,
                },
                {
                    text: "Validasi",
                    value: "jml_terima",
                    sortable: false,
                    width: 130,
                },
                {
                    text: "Kembalikan",
                    value: "jml_kembali",
                    sortable: false,
                    width: 100,
                },
                {
                    text: "Selesai",
                    value: "jml_akhir",
                    sortable: false,
                    width: 130,
                },
            ],
            rows: [],
            rowsLength: 0,
            paginateOpt: initTableOpt(),
            loading: false,
            itemsSelected: [],
            STATUS_DAFTAR,
        };
    },
    mounted() {
        this.fetchData();
    },
    watch: {
        reload() {
            this.fetchData();
        },
        paginateOpt: {
            handler() {
                this.fetchData();
            },
            deep: true,
        },
    },
    computed: {
        ...daftarState,
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        ...daftarMethods,
        async fetchData() {
            this.show();
            this.isLoading = true;

            const [err, resp] = await transLinenDetailService.showByDaftar(
                this.$route.params.kode
            );
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.isLoading = false;

                this.hide();
                return;
            }
            this.hide();
            this.rows = resp.data;
            this.loading = false;
        },
        async updateQty(params, id) {
            this.show();

            const [err] = await transLinenDetailService.updateQty(params, id);
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
            let modalTambah = this.$refs.unitTambahModalRef;
            modalTambah.modalTitle = "Ubah Unit";
            modalTambah.modalForm.kode = row.kode;
            modalTambah.modalForm.nama = row.nama;
            modalTambah.modalEditable = true;
            modalTambah.showModal();
        },
        onDestroy(row) {
            let modal = this.$refs.transLinenDetailDeleteModalRef;
            modal.modalTitle = "Hapus Linen";
            modal.modalForm.id = row.id;
            modal.modalForm.nama = row.nama;
            modal.showModal();
        },
        onReset() {
            this.resetFilter();
            Object.assign(this.paginateOpt, initTableOpt());
            this.fetchData();
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
        onUpdateSortFn(val) {
            console.log(val);
        },
    },
};
</script>
<style>
.no-border-table {
    --easy-table-border: none;
    --easy-table-row-border: 1px solid #ebe5ff;
}
.no-border-table tr td {
    padding: 0.4rem 0.2rem !important;
}
</style>