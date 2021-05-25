<template>
    <router-view></router-view>
</template>

<script>
export default {
    components: {
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap');

html {
  box-sizing: border-box;
}
*, *:before, *:after {
  box-sizing: inherit;
}

* {
  padding: 0;
  margin: 0;
}

body {
    font-family: Roboto;
    color: rgb(59, 59, 59);;
}

button, input {
    display: block;
}

.backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
}

.buttons-container {
    display: flex;
    justify-content: center;
}

.buttons-container button {
  margin: 10px 10px;
}

button {
  padding: 5px 10px;
  border: 2px solid rgb(59, 59, 59);;
  background-color: transparent;
  transition: all 0.3s ease;
}

button:hover {
  cursor: pointer;
  background-color: rgb(59, 59, 59);
  color: white;
  border-color: white;
}

a, a:visited, a:hover, a:active {
  color: inherit;
}

.red-hover:hover {
    background-color: red !important;
    color: white !important;
}

.red-hover {
    background-color: inherit;
    color: red !important;
    border: none;
}

.error {
  color:  red;
}

.form-error {
  font-size: 12px;
  font-weight: 400;
  margin-bottom: 5px;
}
</style>