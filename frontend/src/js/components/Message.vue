<template>
  <div class="ui negative message">
    <div class="header message__type">
      {{ messageType }}
    </div>
    <p>{{ messageText }}</p>
  </div>
</template>

<script>
export default {
  props: {
    messageType: {
      default: 'info',
      type: String
    },
    messageText: {
      default: '',
      type: String
    }
  }
};
</script>

<style scoped>
 .message__type {
   text-transform: uppercase;
 }
</style>
