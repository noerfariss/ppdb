<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-primary mb-4">
    <div class="container-fluid">
      <Link class="navbar-brand" href="/user">PPDB</Link>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <Link class="nav-link active" href="/user">Home</Link>
          </li>
          <li class="nav-item">
            <Link class="nav-link active" href="/user">Informasi</Link>
          </li>
        </ul>

        <form class="d-flex">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <Link href="/user/profil" class="nav-link active">Profil</Link>
            </li>
            <li class="nav-item">
              <Link class="nav-link active" href="/user/password"
                >Password</Link
              >
            </li>
          </ul>

          <a href="#" @click="logout" class="btn btn-sm btn-outline-warning"
            >Keluar</a
          >
        </form>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const logout = () => {
  Swal.fire({
    title: "Anda yakin ingin keluar?",
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonText: "Keluar",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      router.get("/user/logout");
    }
  });
};
</script>

<style></style>
