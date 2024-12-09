<script>
export default {
    components: {},
};
</script>

<template>
    <div
        :style="{
            backgroundImage:
                'url(' + require('@/assets/images/bg-cssd.png') + ')',
            height: '100vh',
            display: 'flex',
        }"
    >
        <div class="account-pages" style="margin: auto; width: 100%">
            <b-container>
                <slot />
            </b-container>
        </div>
    </div>
</template>
