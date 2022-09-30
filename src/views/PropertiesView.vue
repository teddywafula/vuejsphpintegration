<template>
    <PropertiesGrid :properties = "properties" />
</template>

<script>
import axios from 'axios'

import PropertiesGrid from "@/components/PropertiesGrid.vue";

export default {
    name: "PropertiesView",
    components: {
        PropertiesGrid,
    },
    data() {
        return {
            properties: [],
        };
    },
    async created() {
        const results = await axios.get('/properties.php');
        const properties = results.data;
        this.properties = properties;

    }
};
</script>

<style scoped>
    
</style>