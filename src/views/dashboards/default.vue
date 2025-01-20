<script>
import Layout from "@/layouts/main";
import appConfig from "@/app.config";
import PageHeader from "@/components/page-header";
import { spinnerMethods, toastMethods } from "@/state/helpers";
import { daftarService } from "@/services/DaftarService";
import { LineChart } from "echarts/charts";
import * as echarts from "echarts/core";

import { use } from "echarts/core";
import {
    TitleComponent,
    TooltipComponent,
    GridComponent,
    ToolboxComponent,
    LegendComponent,
} from "echarts/components";
import { BarChart } from "echarts/charts";
import { CanvasRenderer } from "echarts/renderers";
import VChart from "vue-echarts";

/**
 * Dashboard Component
 */
export default {
    setup() {
        use([
            LineChart,
            TitleComponent,
            TooltipComponent,
            GridComponent,
            ToolboxComponent,
            LegendComponent,
            BarChart,
            CanvasRenderer,
        ]);
    },
    page: {
        title: "Dashboard",
        meta: [
            {
                name: "description",
                content: appConfig.description,
            },
        ],
    },
    components: {
        Layout,
        PageHeader,
        VChart,
    },
    data() {
        return {
            title: "Dashboard",
            items: [
                {
                    text: "Dashboards",
                    href: "/",
                },
                {
                    text: "Default",
                    active: true,
                },
            ],
            statData: [],
            option: {
                color: ["#FFBF00", "#FF0087", "#80FFA5", "#00DDFF", "#37A2FF"],
                title: {
                    text: "Permintaan Linen Dan Alat 10 Hari Terakhir",
                },
                tooltip: {
                    trigger: "axis",
                    axisPointer: {
                        type: "cross",
                        label: {
                            backgroundColor: "#6a7985",
                        },
                    },
                },
                grid: {
                    left: "3%",
                    right: "4%",
                    bottom: "3%",
                    containLabel: true,
                },
                legend: {
                    data: ["Linen", "Alat"],
                },
                toolbox: {
                    feature: {
                        saveAsImage: {},
                    },
                },
                xAxis: {
                    type: "category",
                    data: [],
                },
                yAxis: {
                    type: "value",
                },
                series: [],
            },
            statistik: {
                sum_pengajuan: "",
                sum_bulanan: "",
                sum_selesai: "",
            },
        };
    },
    created() {
        Promise.all([this.fetchData(), this.getStatistik()]);
    },
    methods: {
        ...spinnerMethods,
        ...toastMethods,
        async fetchData() {
            this.show();
            const [err, resp] = await daftarService.grafik();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.hide();

                return;
            }
            if (resp.data.label.length > 0) {
                this.option.xAxis.data = resp.data.label;
                this.option.series.push({
                    data: resp.data.series.alat,
                    name: "Alat",
                    type: "line",
                    smooth: true,
                    areaStyle: {
                        opacity: 0.8,
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            {
                                offset: 0,
                                color: "rgb(255, 191, 0)",
                            },
                            {
                                offset: 1,
                                color: "rgb(224, 62, 76)",
                            },
                        ]),
                    },
                    emphasis: {
                        focus: "series",
                    },
                });
                this.option.series.push({
                    data: resp.data.series.linen,
                    name: "Linen",
                    type: "line",
                    smooth: true,
                    areaStyle: {
                        opacity: 0.8,
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            {
                                offset: 0,
                                color: "rgb(255, 0, 135)",
                            },
                            {
                                offset: 1,
                                color: "rgb(135, 0, 157)",
                            },
                        ]),
                    },
                    emphasis: {
                        focus: "series",
                    },
                });
            }
            this.hide();
        },
        async getStatistik() {
            const [err, resp] = await daftarService.statistik();
            if (err) {
                this.toastError({
                    title: "Gagal",
                    msg: err.response?.data?.errors,
                });
                this.isLoading = false;

                return;
            }
            this.statistik = resp.data?.grafik_card;
        },
    },
};
</script>

<template>
    <Layout>
        <PageHeader :title="title" :items="items" />
        <div class="mb-1">
            <BRow class="g-2">
                <BCol :lg="3" :md="3" :sm="6" cols="6">
                    <BCard no-body>
                        <BCardBody>
                            <div class="d-flex justify-content-between">
                                <div class="mb-1">Belum Ditangani</div>
                                <div class="mb-1">
                                    <h4>{{ statistik?.blm_selesai ?? 0 }}</h4>
                                </div>
                            </div>
                        </BCardBody>
                    </BCard>
                </BCol>
                <BCol :lg="3" :md="3" :sm="6" cols="6">
                    <BCard no-body>
                        <BCardBody>
                            <div class="d-flex justify-content-between">
                                <div class="mb-1">Alat & Linen Tertangani</div>
                                <div class="mb-1">
                                    <h4>{{ statistik?.sum_selesai ?? 0 }}</h4>
                                </div>
                            </div>
                        </BCardBody>
                    </BCard>
                </BCol>
                <BCol :lg="3" :md="3" :sm="6" cols="6">
                    <BCard no-body>
                        <BCardBody>
                            <div class="d-flex justify-content-between">
                                <div class="mb-1">Jumlah Alat</div>
                                <div class="mb-1">
                                    <h4>{{ statistik?.count_alat ?? 0 }}</h4>
                                </div>
                            </div>
                        </BCardBody>
                    </BCard>
                </BCol>
                <BCol :lg="3" :md="3" :sm="6" cols="6">
                    <BCard no-body>
                        <BCardBody>
                            <div class="d-flex justify-content-between">
                                <div class="mb-1">Jumlah Bundle</div>
                                <div class="mb-1">
                                    <h4>{{ statistik?.count_bundle ?? 0 }}</h4>
                                </div>
                            </div>
                        </BCardBody>
                    </BCard>
                </BCol>
                <div v-if="option.series.length > 0" class="col-12">
                    <BCard no-body>
                        <BCardHeader>
                            <BCardTitle>Dashboard SIM LINEN</BCardTitle>
                        </BCardHeader>
                        <BCardBody>
                            <v-chart
                                class="chart"
                                :option="option"
                                autoresize
                                style="width: 100%; height: 340px"
                            />
                        </BCardBody>
                    </BCard>
                </div>
            </BRow>
        </div>
    </Layout>
</template>