<template>
  <sui-modal v-model="addEmployee">
    <sui-modal-header>{{ modalData === 'create' ? 'Create new employee' : 'Update employee' }}</sui-modal-header>
    <sui-modal-content image>
      <sui-image
        wrapped
        size="medium"
        src="/frontend/assets/images/user.png"
      />
      <sui-modal-description>
        <sui-header>Please fill in all fields:</sui-header>
        <form class="ui form">
          <div
            ref="first_name"
            class="field"
          >
            <label>First Name</label>
            <input
              v-model="first_name"
              type="text"
              name="first_name"
              placeholder="First Name"
              autocomplete="off"
              @focus="clearField($event)"
            >
          </div>
          <div
            ref="last_name"
            class="field"
          >
            <label>Last Name</label>
            <input
              v-model="last_name"
              type="text"
              name="last_name"
              placeholder="Last Name"
              autocomplete="off"
              @focus="clearField($event)"
            >
          </div>
          <div
            ref="email"
            class="field"
          >
            <label>Email</label>
            <input
              v-model="email"
              type="text"
              name="email"
              placeholder="Email"
              autocomplete="off"
              @focus="clearField($event)"
            >
          </div>
          <div
            ref="position"
            class="field"
          >
            <label>Position</label>
            <input
              v-model="position"
              type="text"
              name="position"
              placeholder="Position"
              autocomplete="off"
              @focus="clearField($event)"
            >
          </div>
        </form>
      </sui-modal-description>
    </sui-modal-content>
    <sui-modal-actions>
      <sui-button
        negative
        @click="addEmployee = false"
      >
        Cancel
      </sui-button>
      <sui-button
        positive
        @click="employeeAction($event)"
      >
        {{ modalData === 'create' ? 'Create employee' : 'Update employee' }}
      </sui-button>
    </sui-modal-actions>
  </sui-modal>
</template>

<script>
import axios from 'axios';

export default {
  data () {
    return {
      modalData: null,
      addEmployee: false,
      first_name: null,
      last_name: null,
      email: null,
      position: null,
      errors: {}
    };
  },
  mounted: function () {
    this.$root.$on('modal', (modalData) => {
      this.modalData = modalData;
      this.addEmployee = !this.addEmployee;

      if (modalData === 'create' /* Reset fields values */) {
        if (this.first_name) {
          this.first_name = null;
        }

        if (this.last_name) {
          this.last_name = null;
        }

        if (this.email) {
          this.email = null;
        }

        if (this.position) {
          this.position = null;
        }
      } else {
        // Set fields value.
        const url = location.origin + process.env.BACKEND_API_PATH + `employees/${modalData}`;

        axios
          .get(url)
          .then(response => {
            this.first_name = response.data.first_name;
            this.last_name = response.data.last_name;
            this.email = response.data.email;
            this.position = response.data.position;
          })
          .catch(error => {
            console.log(error.message);
          })
      }
    })
  },
  methods: {
    clearField: function (e) {
      const target = e.target;
      const field = target.getAttribute('name');

      target.parentElement.classList.remove('error');
      if (field in this.errors) {
        delete this.errors[field];
      }
    },
    employeeAction: function (e) {
      const target = e.target;

      if (!this.first_name) {
        this.$refs.first_name.classList.add('error');
        this.errors.first_name = 'You must fill in the "First Name" field correctly.';
      }

      if (!this.last_name) {
        this.$refs.last_name.classList.add('error');
        this.errors.last_name = 'You must fill in the "Last Name" field correctly.';
      }

      if (!this.email || !~this.email.indexOf('@')) {
        this.$refs.email.classList.add('error');
        this.errors.email = 'You must fill in the "Email" field correctly.';
      }

      if (!this.position) {
        this.$refs.position.classList.add('error');
        this.errors.position = 'You must fill in the "Position" field correctly.';
      }

      if (!Object.keys(this.errors).length) {
        target.classList.add('loading');

        const data = {
          first_name: this.first_name,
          last_name: this.last_name,
          email: this.email,
          position: this.position
        }

        if (this.modalData === 'create') {
          const url = location.origin + process.env.BACKEND_API_PATH + 'employees';

          /* Add new employee */
          axios
            .post(url, data)
            .then(response => {
              if (response.data.error) {
                throw new Error(response.data.error);
              } else {
                this.$parent.employeesData.push(response.data);
                target.classList.remove('loading');
                this.addEmployee = false; // Close modal.
              }
            })
            .catch(error => {
              target.classList.remove('loading');
              alert(error.message);
            })
        } else {
          /* Edit */
          const url = location.origin + process.env.BACKEND_API_PATH + `employees/${this.modalData}`;

          axios.patch(url, data)
            .then(response => {
              if (response.data.error) {
                throw new Error(response.data.error);
              } else {
                target.classList.remove('loading');
                this.addEmployee = false; // Close modal.
                this.$router.go();
              }
            })
            .catch(error => {
              target.classList.remove('loading');
              alert(error.message);
            })
        }
      }
    }
  }
};
</script>

<style scoped>
</style>
