<template>
    <div id="nav-bar">
      <router-link to="/" id="properties-link">
        <h1>Properties</h1>
      </router-link>
    </div>
  </template>
  
  <script>
  export default {
      name: 'NavBar',
  }
  </script>
  
  <style scoped>
    #nav-bar {
      border-bottom: 1px solid #ddd;
      height: 75px;
      width: 100%;
    }
  
    #properties-link {
      text-align: center;
      display: block;
      color: black;
      font-size: 22px;
      left: 32px;
      position: absolute;
      top: 16px;
      text-decoration: none;
    }
  
    #properties-link h1 {
        margin: 0;
    }
  </style>
  