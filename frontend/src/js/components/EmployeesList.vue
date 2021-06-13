<template>
  <div>
    <sui-table
      class="employees-list"
      selectable
      celled
      inverted
    >
      <caption class="employees-list__title">
        <h1
          is="sui-header"
          class="left floated"
        >
          <sui-icon name="users" />
          <sui-header-content>
            List of employees
          </sui-header-content>
        </h1>
        <sui-button
          class="right floated employees-list__add"
          content="Add new employee"
          icon="add"
          label-position="left"
          @click="$root.$emit('modal', 'create')"
        />
      </caption>
      <sui-table-header>
        <sui-table-row class="center aligned">
          <sui-table-header-cell>
            #
          </sui-table-header-cell>
          <sui-table-header-cell class="left aligned">
            Name
          </sui-table-header-cell>
          <sui-table-header-cell>Status</sui-table-header-cell>
          <sui-table-header-cell>
            Actions
          </sui-table-header-cell>
        </sui-table-row>
      </sui-table-header>
      <sui-table-body
        v-for="employee in employeesData"
        :key="employee.id"
      >
        <sui-table-row>
          <sui-table-cell class="center aligned">
            {{ employee.id }}
          </sui-table-cell>
          <sui-table-cell>{{ employee.first_name + '&nbsp;' + employee.last_name }}</sui-table-cell>
          <sui-table-cell class="center aligned">
            {{ employee.enabled ? 'Active' : 'Inactive' }}
          </sui-table-cell>
          <sui-table-cell>
            <sui-button
              class="employees-list__view"
              content="view"
              icon="eye"
              label-position="left"
              @click="$router.push({path: `/view/${employee.id}`})"
            />
            <sui-button
              class="employees-list__edit"
              content="edit"
              icon="edit"
              label-position="left"
              :data-id="employee.id"
              @click="editEmployee($event)"
            />
            <sui-button
              class="employees-list__delete"
              content="delete"
              icon="delete"
              label-position="left"
              :data-id="employee.id"
              @click="deleteEmployee($event)"
            />
          </sui-table-cell>
        </sui-table-row>
      </sui-table-body>
    </sui-table>
    <!--MODAL-->
    <EmployeeModal />
  </div>
</template>

<script>
import axios from 'axios';
import EmployeeModal from './EmpolyeeModal';

export default {
  components: {
    EmployeeModal
  },
  props: {
    employees: {
      default: () => [],
      type: Array
    }
  },
  data () {
    return {
      employeesData: this.employees
    };
  },
  methods: {
    deleteEmployee: function (e) {
      const target = e.currentTarget;
      const employeeId = target.getAttribute('data-id');
      const url = location.origin + process.env.BACKEND_API_PATH + 'employees/' + employeeId;

      target.classList.add('loading');

      /* Delete employees with ID = %employeeId% */
      axios
        .delete(url)
        .then(response => {
          if (response.data.error) {
            throw new Error(response.data.error);
          } else {
            this.employeesData.forEach((item, index, object) => {
              if (parseInt(employeeId) === parseInt(item.id)) {
                return object.splice(index, 1);
              }
            });
          }
        })
        .catch(error => {
          target.classList.remove('loading');
          alert(`Failed to delete employee with ID = ${employeeId}`);
          console.log(error.message);
        })
    },
    editEmployee: function (e) {
      const target = e.currentTarget;
      const employeeId = target.getAttribute('data-id');

      this.$root.$emit('modal', employeeId);
    }
  }
};
</script>

<style scoped>
.employees-list {
  font-size: 20px;
}

.employees-list td:last-child {
  width: 380px;
}

.employees-list__title {
  text-align: left;
  margin-bottom: 20px;
}

.employees-list__add {
  margin-top: 5px;
}
</style>
