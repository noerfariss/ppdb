<template>
  <AppLayoutVue>
    <div class="row">
      <div class="col-sm-8">
        <h5 class="font-size-16 me-3 mb-0">Berkas</h5>
        <small>Lengkapi data di bawah ini</small>
      </div>

      <div class="col-sm-4" v-if="multiTahunajar">
        <div class="row mb-3">
          <div class="col-sm-12">
            <select
              name=""
              class="form-control"
              v-model="tahunajarselect"
              @change="onChange"
            >
              <option value="">-- Tahun Ajar --</option>
              <option
                :value="tahun.id"
                v-for="tahun in props.tahunajar"
                :key="tahun.id"
              >
                {{ tahun.tahun }} - {{ tahun.keterangan }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <input type="hidden" v-else v-model="cekValueSingleTahunajarSelect" />
    </div>

    <div class="row mt-2" v-if="multiTahunajar && cekTutupBox">
      <div class="col-sm-2"></div>
      <div class="col-sm-8 text-center alert alert-danger">
        Pilih tahun ajar terlebih dahulu
      </div>
    </div>

    <div class="row mt-2" v-else>
      <div
        class="col-xl-4 col-sm-6"
        v-for="(item, index) in dataGrups.grup"
        :key="item.slug"
      >
        <Link
          :href="
            route('user.grup', {
              tahun: tahunajarselect,
              grup: item.slug,
            })
          "
          class="berkas"
        >
          <div class="card border">
            <div class="card-body p-3">
              <div class="">
                <div class="d-flex align-items-center">
                  <div class="avatar align-self-center me-3">
                    <div
                      class="avatar-title rounded bg-soft-primary text-primary font-size-24"
                    >
                      <i class="mdi mdi-google-drive"></i>
                    </div>
                  </div>

                  <div class="flex-1">
                    <h5 class="font-size-15 mb-1 bold">
                      {{ index.toUpperCase() }}
                    </h5>
                    <Link
                      :href="
                        route('user.grup', {
                          tahun: tahunajarselect,
                          grup: item.slug,
                        })
                      "
                      class="font-size-13 text-muted"
                      >Selengkapnya</Link
                    >
                  </div>
                </div>
                <div class="mt-3 pt-1">
                  <div class="progress animated-progess custom-progress">
                    <div
                      class="progress-bar bg-gradient bg-primary"
                      role="progressbar"
                      :style="{ width: item.persen + '%' }"
                      aria-valuemin="0"
                      aria-valuenow="50"
                      aria-valuemax="100"
                    >
                      {{ item.persen }}%
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Link>
      </div>
      <!-- end col -->
    </div>

    <div
      class="d-flex justify-content-center"
      v-if="!cekTutupBox && cekKelengkapanBerkas"
    >
      <button class="btn btn-warning text-dark">Proses PPDB</button>
    </div>

    <ListStatusVue v-if="!cekTutupBox" />
  </AppLayoutVue>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";
import AppLayoutVue from "./AppLayout.vue";
import ListStatusVue from "../../Components/ListStatus.vue";
import { useForm } from "laravel-precognition-vue-inertia";
import axios from "axios";

const props = defineProps({
  judul: String,
  tahunajar: Array,
  grups: Array,
});

const tahunajarselect = ref("");
const tutupBox = ref(true);
const dataGrups = ref([]);

const multiTahunajar = computed(() => {
  return props.tahunajar.length > 1 ? true : false;
});

const cekValueSingleTahunajarSelect = computed(() => {
  return props.tahunajar.length === 1
    ? (tahunajarselect.value = props.tahunajar[0].id)
    : tahunajarselect.value;
});

const cekTutupBox = computed(() => {
  return tahunajarselect.value === "" ? true : false;
});

const cekKelengkapanBerkas = computed(() => {
  let gruppp = "";
  if (tahunajarselect.value !== "") {
    gruppp = dataGrups.value = props.grups[0].grup;
  } else {
    gruppp = dataGrups.value.grup;
  }

  let statusBerkas = false;
  for (const key in gruppp) {
    if (gruppp[key].persen < 100) {
      statusBerkas = false;
      break;
    } else {
      statusBerkas = true;
    }
  }

  return statusBerkas;
});

const onChange = () => {
  tahunajarselect.value === ""
    ? (tutupBox.value = true)
    : (tutupBox.value = false);

  const filterGroup = props.grups.filter((item) => {
    return item.id === tahunajarselect.value ? item : null;
  });

  dataGrups.value = filterGroup[0];
};

onMounted(() => {
  if (tahunajarselect.value !== "") {
    dataGrups.value = props.grups[0];
  }
});
</script>

<style>
</style>
