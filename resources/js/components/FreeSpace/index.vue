<template>
  <div :style="{height:height}" />
</template>

<script>
export default {
  name: 'FreeSpace',
  props: {
    height: {
      type: String,
      default: '177px',
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
