<template>
    <NavLogin />
  <CardLayoutFluid title="Dashboard">
    {{ text }} 3 <br>
    <Link class="link-primary" :href="route('users.web.route.dashboard.welcome')">Welcome</Link>
  </CardLayoutFluid>
</template>

<script setup>
  import { Link } from '@inertiajs/vue3';
  import CardLayoutFluid from "@/Layouts/CardLayoutFluid.vue";
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