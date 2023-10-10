<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-sm-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Verifikasi</h5>

            <div v-if="$page.props.flash.pesan" class="alert alert-danger">
              {{ $page.props.flash.pesan }}
            </div>

            <form method="post" @submit.prevent="handleSubmit">
              <div class="row mb-3">
                <label class="col-sm-3 col-form-label">kode</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" v-model="form.kode" />
                  <input type="hidden" v-model="form.nomor" />

                  <span class="error" v-if="form.invalid('kode')">
                    {{ form.errors.kode }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9">
                  <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="form.processing"
                  >
                    Verifikasi
                  </button>
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
import { onMounted } from "vue";

const props = defineProps({
  nomor: "",
});

const form = useForm("post", "/verifikasi", {
  nomor: props.nomor,
  kode: "",
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
