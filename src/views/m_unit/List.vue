<template>
    <div>
        <BCard no-body class="p-2">
            <div class="mb-1">
                <div
                    class="d-flex justify-content-between align-itemns-emd py-2 border-bottom"
                >
                    <h5 class="fs-16">Data Unit</h5>
                    <button
                        v-if="isSuperAdmin"
                        class="btn btn-primary"
                        @click.prevent="onCreate"
                    >
                        <i class="mdi mdi-plus-circle-outline"></i>
                        Tambah
                    </button>
                </div>
                <div class="text-end py-2 border-bottom mb-2">
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
                <div class="mb-1">
                    <EasyDataTable
                        buttons-pagination
                        :loading="loading"
                        :headers="columns"
                        :items="rows"
                        rows-per-page="10"
                        theme-color="#556ee6"
                        show-index
                        table-class-name="no-border-table"
                        must-sort
                    >
                        <template #empty-message>
                            <h5>Belum ada unit</h5>
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
            </div>
            <UnitTambah ref="unitTambahModalRef" @fetch="fetchData" />
            <UnitDelete ref="unitDeleteModalRef" @fetch="fetchData" />
        </BCard>
    </div>
</template>
<script>
import { mUnitService } from "@/services/MUnitService";
import {
    mUnitMethods,
    mUnitState,
    spinnerMethods,
    toastMethods,
} from "@/state/helpers";
import queryString from "query-string";
import UnitDelete from "./UnitDelete";
import UnitTambah from "./UnitTambah";
import { ROLE } from "@/helpers/utils";

export default {
    components: {
        UnitDelete,
        UnitTambah,
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
            loading: false,
            itemsSelected: [],
        };
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
    computed: {
        ...mUnitState,
        isSuperAdmin() {
            return this.$store.state.auth.data.role === ROLE.SUPER_ADMIN;
        },
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        ...mUnitMethods,
        async fetchData() {
            this.show();
            this.isLoading = true;
            let query = queryString.stringify(
                Object.assign({}, this.filter, this.paginateOpt)
            );

            const [err, resp] = await mUnitService.all(query);
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
            let modalTambah = this.$refs.unitTambahModalRef;
            modalTambah.modalTitle = "Tambah Unit";
            modalTambah.modalEditable = false;
            modalTambah.resetForm();
            modalTambah.showModal();
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
            let modal = this.$refs.unitDeleteModalRef;
            modal.modalTitle = "Hapus Unit";
            modal.modalForm.kode = row.kode;
            modal.modalForm.nama = row.nama;
            modal.showModal();
        },
        onReset() {
            this.resetFilter();
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