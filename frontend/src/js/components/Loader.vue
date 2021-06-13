<template>
  <sui-segment :class="loaderClass">
    <sui-dimmer active>
      <sui-loader size="massive">
        {{ loaderText }}
      </sui-loader>
    </sui-dimmer>
  </sui-segment>
</template>

<script>
export default {
  props: {
    loaderText: {
      default: 'Loading',
      type: String
    },
    loaderClass: {
      default: 'loader-default-height',
      type: String
    }
  }
};
</script>
