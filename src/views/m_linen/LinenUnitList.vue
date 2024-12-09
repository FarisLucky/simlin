<template>
    <div>
        <div
            class="d-flex justify-content-between align-items-center py-2 border-bottom"
        >
            <div class="mb-1 d-inline-block">
                <h5 class="fs-16">Data Linen Unit</h5>
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
        <div class="py-2 border-bottom">
            <BRow class="g-2 justify-content-end">
                <BCol cols="6" md="2" class="me-1">
                    <div class="search-box">
                        <div class="position-relative">
                            <v-select
                                :modelValue="filter.unit"
                                :options="unitList"
                                :reduce="(unit) => unit.kode"
                                @update:modelValue="onFilterUnitFn"
                                placeholder="Pilih Unit"
                                label="nama"
                            ></v-select>
                        </div>
                    </div>
                </BCol>
                <BCol cols="6" md="2" class="me-1">
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
                </BCol>
                <BCol>
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
                </BCol>
            </BRow>
        </div>
        <div class="mb-1 py-2">
            <EasyDataTable
                :loading="loading"
                :headers="columns"
                :items="rows"
                buttons-pagination
                rows-per-page="10"
                theme-color="#556ee6"
                show-index
                table-class-name="no-border-table"
            >
                <template #empty-message>
                    <h5>Belum ada linen unit</h5>
                </template>
                <template #item-jml="item">
                    <ul class="m-0 px-2 py-1">
                        <li class="mb-1">Bersih: {{ item.bersih }}</li>
                        <li class="mb-1">Kotor: {{ item.kotor }}</li>
                        <li class="mb-1">Terpakai: {{ item.terpakai }}</li>
                    </ul>
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
        <LinenUnitListTambah
            ref="linenUnitListTambahModalRef"
            @fetch="fetchData"
        />
        <LinenUnitListDelete ref="unitDeleteModalRef" @fetch="fetchData" />
    </div>
</template>
<script>
import {
    mLinenUnitMethods,
    mLinenUnitState,
    spinnerMethods,
    toastMethods,
} from "@/state/helpers";
import queryString from "query-string";
import LinenUnitListTambah from "./LinenUnitListTambah";
import LinenUnitListDelete from "./LinenUnitListDelete";
import { mLinenUnitService } from "@/services/MLinenUnitService";
import { mUnitService } from "@/services/MUnitService";
import { ROLE } from "@/helpers/utils";

const initTableOpt = () => ({
    page: 1,
    rowsPerPage: 10,
    sortBy: "nama",
    sortType: "ASC",
});

export default {
    components: {
        LinenUnitListDelete,
        LinenUnitListTambah,
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
                    text: "Linen",
                    value: "nama",
                    sortable: true,
                },
                {
                    text: "Unit",
                    value: "m_unit.nama",
                },
                {
                    text: "Jumlah",
                    value: "jml",
                    sortable: true,
                },
                {
                    text: "Status",
                    value: "status_desc",
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
            unitList: [],
        };
    },
    computed: {
        ...mLinenUnitState,
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    created() {
        this.fetchData();
        this.getListUnit();
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
        ...mLinenUnitMethods,
        async fetchData() {
            this.show();
            this.isLoading = true;
            let query = queryString.stringify(
                Object.assign({}, this.filter, this.paginateOpt)
            );

            const [err, resp] = await mLinenUnitService.all(query);
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
        async getListUnit() {
            this.show();
            const [err, resp] = await mUnitService.data();
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
            this.unitList = resp.data;
            this.loading = false;
        },
        async onCreate() {
            let modalTambah = this.$refs.linenUnitListTambahModalRef;
            modalTambah.modalTitle = "Tambah Linen Unit";
            modalTambah.modalEditable = false;
            modalTambah.resetForm();
            await modalTambah.getListUnit();
            await modalTambah.getListLinen();
            modalTambah.showModal();
        },
        async onShow(row) {
            let modalTambah = this.$refs.linenUnitListTambahModalRef;
            modalTambah.modalTitle = "Ubah Linen Unit";
            modalTambah.modalForm.kode = row.kode;
            modalTambah.modalForm.kode_linen = row.kode_linen;
            modalTambah.modalForm.kode_unit = row.kode_unit;
            modalTambah.modalForm.jml = row.jml;
            modalTambah.modalForm.steril = row.steril;
            modalTambah.modalForm.kotor = row.kotor;
            modalTambah.modalForm.cuci = row.cuci;
            modalTambah.modalForm.status = row.status;
            modalTambah.modalEditable = true;
            await modalTambah.getListUnit();
            await modalTambah.getListLinen();
            modalTambah.showModal();
        },
        onDestroy(row) {
            let modal = this.$refs.unitDeleteModalRef;
            modal.modalTitle = "Hapus Linen Unit";
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
        onFilterUnitFn(event) {
            let val = event.target.value;
            this.onFilterUnit(val);
            this.fetchData();
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