import Employees from './components/Employees';
import EmployeeView from './components/EmployeeView';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Employees
  },
  {
    path: '/view/:id',
    name: 'view',
    component: EmployeeView
  }
];

export default routes;
