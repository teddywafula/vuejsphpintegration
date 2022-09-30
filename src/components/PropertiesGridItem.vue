<template>
    <tr>
        <td>{{ property.county}}</td>
        <td>{{ property.country}}</td>
        <td>{{ property.town }}</td>
        <td>{{ property.description }}</td>
        <td>{{ property.address }}</td>
        <td><a :href= property.image_full target="_blank">View</a></td>
        <td><img :src=property.image_thumbnail :alt=property.county /></td>
        <td>{{ property.latitude }}</td>
        <td>{{ property.longitude }}</td>
        <td>{{ property.num_bedrooms }}</td>
        <td>{{ property.num_bathrooms }}</td>
        <td>{{ property.price }}</td>
        <td>{{ property.type }}</td>
        <td>{{ property.property_type }}</td>
        <td>{{ property.property_description }}</td>
    </tr>
   </template>
   
   <script>
     
       export default {
           name: 'PropertiesGridItem',
           props: ['property'],
        
       }
   
   </script>
   
   <style scoped>
   
   </style>