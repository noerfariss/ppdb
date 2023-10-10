<template>
  <AppLayoutVue>
    <h5 class="font-size-16 me-3 mb-0">Password</h5>

    <div v-if="$page.props.flash.pesanSukses" class="alert alert-success">
      {{ $page.props.flash.pesanSukses }}
    </div>

    <div v-if="$page.props.flash.pesan" class="alert alert-danger">
      {{ $page.props.flash.pesan }}
    </div>

    <div class="row mt-4">
      <div class="col-xl-6 col-sm-6">
        <div class="card">
          <div class="card-body p-3">
            <form method="post" @submit.prevent="onSubmit">
              <div class="row mb-3">
                <label class="col-sm-3 col-form-label">password</label>
                <div class="col-sm-9">
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
                <label class="col-sm-3 col-form-label"
                  >konfirmasi password</label
                >
                <div class="col-sm-9">
                  <input
                    type="password"
                    class="form-control"
                    v-model="form.password_confirmation"
                  />
                  <span
                    class="error"
                    v-if="form.invalid('password_confirmation')"
                  >
                    {{ form.errors.password_confirmation }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-account-cog-outline me-1"></i> Simpan
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayoutVue>
</template>

<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import AppLayoutVue from "./AppLayout.vue";

const form = useForm("post", "/user/password", {
  password: "",
  password_confirmation: "",
});

const onSubmit = () => {
  form.submit({
    onSuccess: () => form.reset(),
  });
};
</script>

<style>
</style>
