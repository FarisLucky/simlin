<template>
    <div class="p-2">
        <BRow class="g-2 align-items-end">
            <BCol lg="3">
                <label class="mb-1">Range Tanggal</label>
                <flat-pickr
                    v-model="filter.range_tanggal"
                    placeholder="Pilih Range Tanggal"
                    :config="{
                        mode: 'range',
                        dateFormat: 'd-m-Y',
                    }"
                    class="form-control bg-light border-light"
                    required
                ></flat-pickr>
            </BCol>
            <BCol md="4">
                <label class="mb-1">Unit</label>
                <v-select
                    v-model="filter.unit"
                    taggable
                    multiple
                    :options="unitList"
                    :reduce="(unit) => unit.kode"
                    label="nama"
                    placeholder="Pilih Unit"
                ></v-select>
            </BCol>
            <div class="col-lg">
                <BButton variant="primary" @click.prevent="getUrl">
                    <i class="bx bx-spreadsheet" style="font-size: 12px"></i>
                    Export
                </BButton>
            </div>
        </BRow>
    </div>
</template>
<script>
import { webUrl } from "@/config/http";
import { mUnitService } from "@/services/MUnitService";
import { spinnerMethods, toastMethods } from "@/state/helpers";
import queryString from "query-string";
import flatPickr from "vue-flatpickr-component";

export default {
    components: {
        flatPickr,
    },
    data() {
        return {
            filter: {
                range_tanggal: "",
                unit: "",
            },
            webUrl,
            unitList: [],
        };
    },
    created() {
        this.getUnit();
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        async getUnit() {
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
            this.unitList = resp.data;
            this.unitList.unshift({
                kode: "all",
                nama: "SEMUA",
            });

            this.hide();
        },
        getUrl() {
            if (this.filter.range_tanggal === "") {
                this.toastError({
                    title: "Gagal",
                    msg: "Range tidak boleh kosong",
                });
                return;
            }

            let a = document.createElement("a");
            const query = queryString.stringify(this.filter, {
                arrayFormat: "index",
            });
            a.href = this.webUrl + "/rekap/linen/excel?" + query;
            a.setAttribute("target", "_blank");
            a.click();
        },
    },
};
</script>
