<template>
    <div>
        <div
            class="d-flex justify-content-between align-items-center py-2 border-bottom"
        >
            <div class="mb-1 d-inline-block">
                <h5 class="fs-16">Data Linen</h5>
            </div>
            <div class="mb-1 d-inline-block">
                <button
                    v-if="isSuperAdmin"
                    class="btn btn-primary"
                    @click.prevent="onCreate"
                >
                    <i class="mdi mdi-plus-circle-outline"></i>
                    Tambah
                </button>
            </div>
        </div>
        <div class="text-end py-2 border-bottom">
            <div class="d-inline-block me-1">
                <div class="search-box">
                    <div class="position-relative">
                        <input
                            type="text"
                            class="form-control rounded bg-light border-light"
                            placeholder="Search..."
                            :value="filter.search"
                            @keyup.enter="onFilterSearchFn"
                        />
                    </div>
                </div>
            </div>
            <button
                type="submit"
                class="btn btn-soft-primary me-1"
                @click.prevent="fetchData"
            >
                <i class="mdi mdi-file-search-outline"></i>
                Cari
            </button>
            <button
                class="btn btn-soft-secondary me-1"
                @click.prevent="onReset"
            >
                <i class="mdi mdi-refresh-circle"></i>
                Filter
            </button>
        </div>
        <div class="mb-1 py-2">
            <EasyDataTable
                buttons-pagination
                rows-per-page="10"
                :loading="loading"
                :headers="columns"
                :items="rows"
                theme-color="#556ee6"
                show-index
                table-class-name="no-border-table"
            >
                <template #empty-message>
                    <h5>Belum ada linen</h5>
                </template>
                <template #item-berat="item">
                    <b>{{ item.berat }}</b> gram
                </template>
                <template #item-aksi="item">
                    <BButton
                        variant="soft-primary"
                        size="sm"
                        @click.prevent="onShow(item)"
                        class="me-1"
                    >
                        <i class="bx bx-pencil"></i>
                    </BButton>
                    <BButton
                        v-if="isSuperAdmin"
                        type="button"
                        variant="soft-danger"
                        size="sm"
                        @click.prevent="onDestroy(item)"
                    >
                        <i class="bx bx-trash"></i>
                    </BButton>
                </template>
            </EasyDataTable>
        </div>
        <LinenTambah ref="linenTambahModalRef" @fetch="fetchData" />
        <LinenListDelete ref="unitDeleteModalRef" @fetch="fetchData" />
    </div>
</template>
<script>
import {
    mLinenMethods,
    mLinenState,
    spinnerMethods,
    toastMethods,
} from "@/state/helpers";
import queryString from "query-string";
import LinenListDelete from "./LinenListDelete";
import LinenTambah from "./LinenTambah";
import { mLinenService } from "@/services/MLinenService";
import { ROLE } from "@/helpers/utils";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "nama",
    sortType: "ASC",
});

export default {
    components: {
        LinenListDelete,
        LinenTambah,
    },
    data() {
        return {
            columns: [
                {
                    text: "Kode",
                    value: "kode",
                    sortable: true,
                },
                {
                    text: "Nama",
                    value: "nama",
                    sortable: true,
                },
                {
                    text: "Dibuat",
                    value: "created_at",
                    sortable: false,
                },
                {
                    text: "Last Update",
                    value: "updated_at",
                    sortable: false,
                },
                {
                    text: "Aksi",
                    value: "aksi",
                    sortable: false,
                },
            ],
            rows: [],
            rowsLength: 0,
            loading: false,
            paginateOpt: initTableOpt(),
            itemsSelected: [],
        };
    },
    computed: {
        ...mLinenState,
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    created() {
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
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        ...mLinenMethods,
        async fetchData() {
            this.show();
            this.isLoading = true;
            let query = queryString.stringify(
                Object.assign({}, this.filter, this.paginateOpt)
            );

            const [err, resp] = await mLinenService.all(query);
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
            let result = resp.data;
            this.rows = result;
            this.loading = false;
        },
        onCreate() {
            let modalTambah = this.$refs.linenTambahModalRef;
            modalTambah.modalTitle = "Tambah Linen";
            modalTambah.modalEditable = false;
            modalTambah.resetForm();
            console.log("test");
            modalTambah.showModal();
        },
        onShow(row) {
            let modalTambah = this.$refs.linenTambahModalRef;
            modalTambah.modalTitle = "Ubah Linen";
            modalTambah.modalForm.kode = row.kode;
            modalTambah.modalForm.nama = row.nama;
            modalTambah.modalEditable = true;
            modalTambah.showModal();
        },
        onDestroy(row) {
            let modal = this.$refs.unitDeleteModalRef;
            modal.modalTitle = "Hapus Linen";
            modal.modalForm.kode = row.kode;
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
    },
};
</script>
<style>
.no-border-table {
    --easy-table-border: none;
    --easy-table-row-border: 1px solid rgba(75, 5, 173, 0.1);
}
</style>