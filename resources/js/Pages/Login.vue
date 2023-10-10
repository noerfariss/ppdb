<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-sm-5 push-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Login</h5>

            <div v-if="$page.props.flash.pesanSukses" class="alert alert-success">
              {{ $page.props.flash.pesanSukses }}
            </div>

            <div v-if="$page.props.flash.pesan" class="alert alert-danger">
              {{ $page.props.flash.pesan }}
            </div>

            <form method="post" @submit.prevent="onSubmit">
              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">whatsapp</label>
                <div class="col-sm-8">
                  <input
                    type="text"
                    class="form-control"
                    name="nomor"
                    v-model="form.nomor"
                  />

                  <span class="error" v-if="form.invalid('nomor')">
                    {{ form.errors.nomor }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-4 col-form-label">password</label>
                <div class="col-sm-8">
                  <input
                    type="password"
                    class="form-control"
                    name="password"
                    v-model="form.password"
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
                    Login
                  </button>

                  <Link href="/register" class="ms-3">Daftar</Link>
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

const form = useForm("post", "/login", {
  nomor: "",
  password: "",
});

const onSubmit = () =>
  form.submit({
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
</script>

<style>
</style>
