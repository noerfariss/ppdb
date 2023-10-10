<template>
  <div class="d-flex flex-wrap">
    <h5 class="font-size-16 me-3">Proses</h5>
  </div>
  <hr class="mt-2" />
  <div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Date modified</th>
          <th scope="col">Size</th>
          <th scope="col" colspan="2">Members</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <a href="javascript: void(0);" class="text-dark fw-medium"
              ><i
                class="mdi mdi-file-document font-size-16 align-middle text-primary me-2"
              ></i>
              index.html</a
            >
          </td>
          <td>12-10-2020, 09:45</td>
          <td>09 KB</td>
          <td></td>
          <td>
            <div class="dropdown">
              <a
                class="font-size-16 text-muted"
                role="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
              >
                <i class="mdi mdi-dots-horizontal"></i>
              </a>

              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="#">Open</a>
                <a class="dropdown-item" href="#">Edit</a>
                <a class="dropdown-item" href="#">Rename</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Remove</a>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>
