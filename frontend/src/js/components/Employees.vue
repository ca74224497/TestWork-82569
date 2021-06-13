<template>
  <Loader
    v-if="state === $CONSTANT('AXIOS_QUERY_STATE_LOADING')"
    loader-text="Please wait, the employee list is loading"
  />
  <EmployeesList
    v-else-if="state === $CONSTANT('AXIOS_QUERY_STATE_LOADED')"
    :employees="employees"
  />
  <Message
    v-else
    message-type="error"
    message-text="Failed to load the list of employees!"
  />
</template>

<script>
import axios from 'axios';
import EmployeesList from './EmployeesList';
import Loader from './Loader';
import Message from './Message';

export default {
  components: {
    Message,
    Loader,
    EmployeesList
  },
  data () {
    return {
      employees: [],
      state: this.$CONSTANT('AXIOS_QUERY_STATE_LOADING')
    }
  },
  mounted () {
    const url = location.origin + process.env.BACKEND_API_PATH + 'employees';

    /* Get list of employees */
    axios
      .get(url)
      .then(response => {
        this.state = this.$CONSTANT('AXIOS_QUERY_STATE_LOADED');
        this.employees = response.data;
      })
      .catch(error => {
        this.state = this.$CONSTANT('AXIOS_QUERY_STATE_FAILED');
        console.log(error.message);
      })
  }
};
</script>
