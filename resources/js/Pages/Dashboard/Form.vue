<template>
  <AppLayoutVue>
    <h5 class="font-size-16 me-3 mb-0">
      <Link :href="route('user.dashboard')">Kembali</Link>
      Form
    </h5>

    <div v-if="$page.props.flash.pesanSukses" class="alert alert-success">
      {{ $page.props.flash.pesanSukses }}
    </div>

    <div class="row mt-4">
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
        <div class="card">
          <div class="card-body p-3">
            <form method="post" @submit.prevent="onSubmit">
              <div class="row mb-3" v-for="item in forms" :key="item.id">
                <label class="col-sm-12 col-form-label"
                  >{{ item.label }}

                  <span v-if="item.pivot.wajib" class="text-danger float-end"
                    ><small>*wajib</small></span
                  >
                </label>
                <div class="col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.jawaban"
                  />

                  <span class="error" v-if="$page.props.flash.pesan">
                    {{ $page.props.flash.pesan[item.id] }}
                  </span>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-primary">
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
import AppLayoutVue from "./AppLayout.vue";
import { useForm } from "laravel-precognition-vue-inertia";
import { computed, onMounted, ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  data: Object,
  errors: Object,
});

const forms = ref([]);
const labels = ref([]);
const cekError = ref([]);

const cekLabels = computed(() => {
  return labels.value.length > 0 ? labels.value : [];
});

const onSubmit = () => {
  const items = [];
  forms.value.forEach((item) => {
    items.push({
      form_id: item.id,
      wajib: item.pivot.wajib,
      jawaban: item.jawaban ? item.jawaban : "",
    });
  });

  labels.value = items;

  const form = useForm("post", "/user/grup/simpan", {
    labels: labels.value,
    tahun: props.data.slug,
    grup: props.data.form[0].grup.slug,
  });

  form.submit({
    preserveScroll: true,
  });
};

onMounted(() => {
  forms.value = props.data.form;
});
</script>

<style>
</style>
