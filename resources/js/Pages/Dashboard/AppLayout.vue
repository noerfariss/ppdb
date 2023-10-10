<template>
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <HeaderVue />

            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
    import HeaderVue from "../../Components/Header.vue";
</script>

<style>
</style>
