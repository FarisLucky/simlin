export const menuItems = [
    {
        id: 1,
        label: "menuitems.menu.text",
        isTitle: true,
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 2,
        label: "menuitems.dashboards.text",
        icon: "bx-home-circle",
        link: "/",
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 9,
        label: "menuitems.trans.text",
        isTitle: true,
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 10,
        label: "menuitems.trans.harian",
        icon: "bx bx-book",
        link: "/daftar/harian",
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 3,
        isLayout: true,
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 4,
        label: "menuitems.master.header",
        isTitle: true,
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 5,
        label: "menuitems.master.text",
        icon: "bx bx-collection",
        roles: ["SUPER_ADMIN","UNIT"],
        subItems: [
            {
                id: 6,
                label: "menuitems.master.unit",
                link: "/m-unit",
                parentId: 5,
                roles: ["SUPER_ADMIN","UNIT"]
            },
            {
                id: 7,
                label: "menuitems.master.linen",
                link: "/m-linen/list",
                parentId: 5,
                roles: ["SUPER_ADMIN","UNIT"]
            },
            {
                id: 8,
                label: "menuitems.master.alat",
                link: "/m-alat/list",
                parentId: 5,
                roles: ["SUPER_ADMIN","UNIT"]
            },
            {
                id: 8,
                label: "menuitems.master.bundle",
                link: "/m-alat-bundle/list",
                parentId: 5,
                roles: ["SUPER_ADMIN","UNIT"]
            },
        ]
    },
    {
        id: 9,
        label: "menuitems.history.label",
        isTitle: true,
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 10,
        label: "menuitems.history.text",
        icon: "bx bx-history",
        link: "/history/daftar",
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 9,
        label: "menuitems.rekap.label",
        isTitle: true,
        roles: ["SUPER_ADMIN","UNIT"]
    },
    {
        id: 10,
        label: "menuitems.rekap.text",
        icon: "bx bxs-report",
        link: "/rekap",
        roles: ["SUPER_ADMIN","UNIT"]
    },
];