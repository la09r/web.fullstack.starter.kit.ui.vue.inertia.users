<template>
    <NavLogin />
  <CardLayout title="User Profile">
      <UserProfileForm route="profile"/>
  </CardLayout>
</template>

<script setup>
  import { Link } from '@inertiajs/vue3';
  import UserProfileForm from "./Form.vue";
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