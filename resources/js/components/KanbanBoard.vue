<template>
  <div>
    <!-- Simple MDL Progress Bar -->
    <div class="kanban__title">
      <h1>Jira Board</h1>
    </div>
    <div class="dd">
      <div class="w-100 p-3">
        <datepicker input-class="w-25 form-control float-left" v-model="filter.date" :format="customFormatter" @input="getColumns" placeholder="Select Date"></datepicker>
          <select v-model="filter.status" class="w-25 form-control" @change="getColumns">
            <option value="">Select Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
      </div>
      
      <ol class="kanban To-do" v-for="column in columns" :key="column.id">
        <div class="kanban__title">
          <button type="button" class="btn btn-primary btn-sm" @click="editColumn(column.id)">Edit</button>
          <button type="button" class="btn btn-danger btn-sm" @click="deleteColumn(column.id)">Delete</button>
          <h2>{{ column.name }}</h2>
          <button class="addbutt" @click="openCard(column.id)"> Add
            new
            card</button>
        </div>
        <draggable class="flex-1 overflow-hidden" v-model="column.cards" v-bind="dragOptions" @end="handleCardMoved">
          <transition-group type="transition" :name="'flip-list'">
            <li class="dd-item" v-for="card in column.cards" :key="`card-${card.id}`">
              <a href="#" class="btn btn-primary btn-sm" @click="editCard(card.id)">Edit</a>
              <a href="#" class="btn btn-danger btn-sm" @click="deleteCard(card.id)">Delete</a>
              <h3 class="title dd-handle">{{ card.name }}</h3>
              <div class="text">
                {{ card.description }}
              </div>
            </li>

            <li class="dd-item" v-if="column.cards.length == 0 || column.cards == undefined"
              :key="`undefined-card-${column.id}`">
              <div class="text">
                Please add or drop a card
              </div>
            </li>

          </transition-group>
        </draggable>
      </ol>
    </div>
    <menu class="kanban">
      <button @click="open()"> Add new
        column</button>
    </menu>
    <add-edit-column-form ref="addEditColumnForm" v-on:column-added="handleAdded"
      v-on:column-canceled="handleCanceled"></add-edit-column-form>

    <add-edit-card-form ref="addEditCardForm" v-on:card-added="handleAdded"
      v-on:card-canceled="handleCanceled"></add-edit-card-form>
  </div>
</template>

<script>
import AddEditColumnForm from "./Column/AddEditForm.vue";
import AddEditCardForm from "./Card/AddEditForm.vue";
import draggable from 'vuedraggable';
import axios from "axios";
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';

export default {
  components: { AddEditColumnForm, AddEditCardForm, draggable, Datepicker },
  data() {
    return {
      columns: {},
      filter: {
        date: '',
        status: ''
      }
    }
  },
  computed: {
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false
      };
    },
  },
  methods: {

    customFormatter(date) {
      return moment(date).format('yyyy-MM-DD');
    },

    handleCanceled(type) {
      if (type === 'column') {
        this.$modal.hide('add-edit-column-modal')
      } else {
        this.$modal.hide('add-edit-card-modal')
      }
    },

    handleAdded(type) {
      this.handleCanceled(type);
      this.getColumns();
    },

    getColumns() {
      if(this.filter.date != '') {
        this.filter.date = moment(this.filter.date).format('yyyy-MM-DD');
      }

      axios.get('/api/columns', { params: this.filter }).then(({ data }) => (this.columns = data.data));
    },

    open() {
      this.$modal.show('add-edit-column-modal')
    },

    editColumn(id) {
      axios.get('/api/columns/' + id).then(({ data }) => (this.$refs.addEditColumnForm.setColumn(data.data)));
      this.$modal.show('add-edit-column-modal')
    },

    openCard(columnId) {
      this.$refs.addEditCardForm.setColumnId(columnId)
      this.$modal.show('add-edit-card-modal')
    },

    editCard(id) {
      axios.get('/api/cards/' + id).then(({ data }) => (this.$refs.addEditCardForm.setCard(data.data)));
      this.$modal.show('add-edit-card-modal')
    },

    deleteColumn(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          axios.delete('api/columns/' + id).then(() => {
            Swal.fire(
              'Deleted!',
              'Your column has been deleted.',
              'success'
            );
            this.getColumns();
          }).catch((data) => {
            Swal.fire("Failed!", data.message, "warning");
          });
        }
      })
    },

    deleteCard(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          axios.delete('api/cards/' + id).then(() => {
            Swal.fire(
              'Deleted!',
              'Your card has been deleted.',
              'success'
            );
            this.getColumns();
          }).catch((data) => {
            Swal.fire("Failed!", data.message, "warning");
          });
        }
      })
    },

    handleCardMoved(evnt) {
      axios
        .post("/api/cards/order", { 'columns': this.columns })
    }
  },
  created() {
    this.getColumns();
  }
}


</script>