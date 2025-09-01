<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/">MeuApp</router-link>

      <button 
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar"
        aria-controls="mainNavbar"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li v-for="route in routes" :key="route.path" class="nav-item" >
            <router-link
              class="nav-link"
              :to="route.path"
              active-class="active"
              >{{ route.name }}</router-link>
          </li>
        </ul>

        <div class="d-flex">
          <button
            v-if="isLoggedIn"
            @click="handleLogout"
            class="btn btn-outline-light me-2"
            type="button"
          >
            Logout
          </button>

          <router-link v-else to="/login" class="btn btn-outline-light">Login</router-link>
        </div>
      </div>
    </div>
  </nav>
</template>


<script setup lang="ts">
import { httpAuth } from "@/api/http";
import { clearUserSession, getUserSession } from "@/services/session";
import { computed } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const routes = router.options.routes.filter(r => r.name);

const isLoggedIn = computed(() => !!getUserSession());

const handleLogout = async () => {
  try {
    await httpAuth.post("/auth/v1/logout");
    clearUserSession();
    router.push("/login");
  } catch (err) {
    console.error("Erro ao fazer logout:", err);
  }
};
</script>
