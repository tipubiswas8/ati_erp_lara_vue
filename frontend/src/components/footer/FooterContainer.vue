<template>
    <FooterOne v-if="selectedFooter === 1" />
    <FooterTwo v-if="selectedFooter === 2" />
    <FooterThree v-if="selectedFooter === 3" />
    <FooterFour v-if="selectedFooter === 4" />
</template>

<script lang="ts" setup>
import FooterOne from './FooterOne.vue';
import FooterTwo from './FooterTwo.vue';
import FooterThree from './FooterThree.vue';
import FooterFour from './FooterFour.vue';
import { selectedFooter } from '@/stores/footer-store';

</script>