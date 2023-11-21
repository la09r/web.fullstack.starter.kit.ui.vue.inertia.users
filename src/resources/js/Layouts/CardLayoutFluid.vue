<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
              <div>{{ title }}</div>
              <Button v-if="showButtonAdd" @click="$emit('callbackButtonAdd', {})" icon="pi pi-plus" rounded raised ></Button>
            </div>
          </div>
          <div class="card-body">
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { Link } from '@inertiajs/vue3';
  import Button from "primevue/button"
</script>
<script>
  export default {
    props: {
      title: String,
      showButtonAdd: Boolean,
    },
    data() {
      return {}
    }
  };
</script>