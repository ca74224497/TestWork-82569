<template>
  <div>
    <h1>Description of the employee with ID = {{ $route.params.id }}:</h1>
    <Loader
      v-if="state === $CONSTANT('AXIOS_QUERY_STATE_LOADING')"
      loader-text="Please wait, employee data is being loaded"
    />
    <div v-else-if="state === $CONSTANT('AXIOS_QUERY_STATE_LOADED')">
      <sui-list
        divided
        relaxed
        inverted
      >
        <sui-list-item>
          <sui-list-icon
            name="user"
            size="large"
            vertical-align="middle"
          />
          <sui-list-content>
            <span is="sui-list-header">Full name:</span>
            <span is="sui-list-description">{{ `${description.first_name} ${description.last_name}` }}</span>
          </sui-list-content>
        </sui-list-item>
        <sui-list-item>
          <sui-list-icon
            name="mail"
            size="large"
            vertical-align="middle"
          />
          <sui-list-content>
            <span is="sui-list-header">E-mail:</span>
            <span is="sui-list-description">{{ description.email }}</span>
          </sui-list-content>
        </sui-list-item>
        <sui-list-item>
          <sui-list-icon
            name="briefcase"
            size="large"
            vertical-align="middle"
          />
          <sui-list-content>
            <span is="sui-list-header">Position:</span>
            <span is="sui-list-description">{{ description.position }}</span>
          </sui-list-content>
        </sui-list-item>
        <sui-list-item>
          <sui-list-icon
            :name="description.enabled ? 'thumbs up' : 'thumbs down'"
            size="large"
            vertical-align="middle"
          />
          <sui-list-content>
            <span is="sui-list-header">Status:</span>
            <span is="sui-list-description">{{ description.enabled ? 'Active' : 'Inactive' }}</span>
          </sui-list-content>
        </sui-list-item>
      </sui-list>
    </div>
    <Message
      v-else
      message-type="error"
      message-text="Failed to load employee description!"
    />
    <br>
    <sui-button
      content="Go back to employees list"
      icon="left arrow"
      inverted
      label-position="left"
      @click="$router.push({ path: '/' })"
    />
  </div>
</template>

<script>
import Loader from './Loader';
import Message from './Message';
import axios from 'axios';

export default {
  components: {
    Loader,
    Message
  },
  data () {
    return {
      state: this.$CONSTANT('AXIOS_QUERY_STATE_LOADING'),
      description: []
    }
  },
  mounted () {
    const url = location.origin + process.env.BACKEND_API_PATH + `employees/${this.$route.params.id}`;

    /* Get description of employee */
    axios
      .get(url)
      .then(response => {
        this.state = this.$CONSTANT('AXIOS_QUERY_STATE_LOADED');
        this.description = response.data;
      })
      .catch(error => {
        this.state = this.$CONSTANT('AXIOS_QUERY_STATE_FAILED');
        console.log(error.message);
      })
  }
};
</script>

<style scoped>
  h1 {
    padding-bottom: 20px;
  }

  span {
    font-size: 20px;
    padding-bottom: 10px;
  }
</style>
