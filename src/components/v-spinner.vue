<style scoped>
.container-spinner {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    height: 100%;
    /* background: rgba(224, 224, 224, 0.226); */
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
<template>
    <div v-if="$store.state.spinner.visible" class="container-spinner">
        <div
            class="bg-white px-2 py-1 d-inline-block text-center"
            style="vertical-align: middle"
        >
            <BSpinner variant="primary" small />
            <strong class="ms-1">Loading...</strong>
        </div>
    </div>
</template>
<script>
export default {};
</script>