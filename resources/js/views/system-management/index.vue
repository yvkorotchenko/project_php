<template>
  <div className="app-container">
    <div className="row justify-content-center">
      <div className="col-md-8">
        <div className="card card-default">
          <h3 className="card-header">Foo Component</h3>
          <div className="card-body">
            I'm a foo component.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log('Foo component mounted.');
  },
};
</script>
