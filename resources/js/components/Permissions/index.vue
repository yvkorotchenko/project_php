<template>
  <span v-if="visible">
    <slot />
  </span>
</template>

<script>
import permissions from '@/utils/permission';

export default {
  name: 'PermissionChecker',
  props: {
    permissions: {
      required: true,
      type: Array,
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      visible: true,
    };
  },
  created() {
    this.visible = permissions(this.permissions);
  },
};
</script>
