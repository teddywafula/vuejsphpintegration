<template>
<div class="container-fluid">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table">
                <th>County</th>
                <th>Country</th>
                <th>Town</th>
                <th>Description</th>
                <th>Address</th>
                <th>Image</th>
                <th>Thumbnail</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Number of Bedrooms</th>
                <th>Number of Bathrooms</th>
                <th>Price</th>
                <th>Type</th>
                <th>Property Type</th>
                <th>Property Description</th>
            </thead>
            <tbody>   
                <PropertiesGridItem
                    v-for="property in properties"
                    :key="property.uuid"
                    :property="property"
                />

            </tbody>
        </table>
    </div>
</div>

</template>

<script>
    import PropertiesGridItem from './PropertiesGridItem.vue';

    export default {
        name: 'PropertiesGrid',
        props: ['properties'],
        components: {
            PropertiesGridItem
        }
    }
</script>

<style scoped>

</style>