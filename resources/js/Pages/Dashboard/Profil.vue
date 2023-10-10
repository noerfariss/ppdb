<template>
  <AppLayoutVue>
    <h5 class="font-size-16 me-3 mb-0">Profil</h5>

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
                <label class="col-sm-3 col-form-label">Whatsapp</label>
                <div class="col-sm-9">
                  <input
                    type="text"
                    class="form-control"
                    name="nomor"
                    disabled
                    v-model="form.nomor"
                  />
                  <span class="error" v-if="form.invalid('nomor')">
                    {{ form.errors.nomor }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                  <input
                    type="text"
                    class="form-control"
                    name="email"
                    disabled
                    v-model="form.email"
                  />
                  <span class="error" v-if="form.invalid('email')">
                    {{ form.errors.email }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-3 col-form-label">nama</label>
                <div class="col-sm-9">
                  <input
                    type="text"
                    class="form-control"
                    name="nama"
                    v-model="form.nama"
                  />
                  <span class="error" v-if="form.invalid('nama')">
                    {{ form.errors.nama }}
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
import { onMounted } from "vue";
import AppLayoutVue from "./AppLayout.vue";
import { useForm } from "laravel-precognition-vue-inertia";

const props = defineProps({
  data: Object,
});

const form = useForm("post", "/user/profil", {
  nomor: props.data.nomor,
  email: props.data.email,
  nama: props.data.nama,
});

const onSubmit = () => {
  form.submit();
};
</script>

<style>
</style>
