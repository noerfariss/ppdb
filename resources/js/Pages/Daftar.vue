<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-sm-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Daftar</h5>

            <div v-if="$page.props.flash.pesan" class="alert alert-danger">
              {{ $page.props.flash.pesan }}
            </div>

            <form method="post" @submit.prevent="handleSubmit">
              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">whatsapp</label>
                <div class="col-sm-8">
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.nomor"
                  />

                  <span class="error" v-if="form.invalid('nomor')">
                    {{ form.errors.nomor }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Nama</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" v-model="form.nama" />

                  <span class="error" v-if="form.invalid('nama')">
                    {{ form.errors.nama }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">email</label>
                <div class="col-sm-8">
                  <input
                    type="email"
                    class="form-control"
                    v-model="form.email"
                  />

                  <span class="error" v-if="form.invalid('email')">
                    {{ form.errors.email }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">password</label>
                <div class="col-sm-8">
                  <input
                    type="password"
                    class="form-control"
                    v-model="form.password"
                  />

                  <span class="error" v-if="form.invalid('password')">
                    {{ form.errors.password }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-4 col-form-label"
                  >konfirmasi password</label
                >
                <div class="col-sm-8">
                  <input
                    type="password"
                    class="form-control"
                    v-model="form.password_confirmation"
                  />

                  <span class="error" v-if="form.invalid('password')">
                    {{ form.errors.password }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                  <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="form.processing"
                  >
                    Daftar
                  </button>

                  <Link href="/login" class="ms-3">Login</Link>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";

const form = useForm("post", "/register", {
  nomor: "",
  nama: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const handleSubmit = () => {
  form.submit({
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};
</script>

<style>
</style>
