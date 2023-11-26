<template>
    <NavLogin />
  <CardLayout title="Home">
    User Profile
  </CardLayout>
</template>

<script setup>
  import { Link } from '@inertiajs/vue3';
  import CardLayout from "@/Layouts/CardLayout.vue";
  import NavLogin from "@/components/Dashboard/Nav.vue";
</script>
<script>
  export default {
    props: {
      text: String
    },
    data() {
      return {}
    }
  };
</script>