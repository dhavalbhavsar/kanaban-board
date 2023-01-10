<template>
  <modal name="add-edit-column-modal" height="auto">
    <form @submit.prevent="handleAddNewColumn" ref="columnAdd">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" v-if="column.id === null">Add a column</h5>
          <h5 class="modal-title" v-else>Edit a column</h5>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Column Name:</label>
            <input type="text" class="form-control" placeholder="Enter a column" v-model.trim="column.name" />
            <div v-show="errorMessage">
              <span class="text-danger">
                {{ errorMessage }}
              </span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="$emit('column-canceled', 'column')" type="reset" class="btn btn-secondary">
            cancel
          </button>
          <button type="submit" class="btn btn-primary" v-if="column.id === null">
            Add
          </button>
          <button type="submit" class="btn btn-primary" v-else>
            Edit
          </button>
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
export default {
  name: 'AddEditColumnModal',
  data() {
    return {
      column: {
        id: null,
        name: ""
      },
      isEdit: false,
      errorMessage: ""
    };
  },
  methods: {

    setColumn(column) {
      this.column = column;
    },

    handleAddNewColumn() {

      // Basic validation
      if (!this.column.name) {
        this.errorMessage = "The column name field is required";
        return;
      }

      // Send new column to server
      if(this.column.id === null) {
        axios
          .post("/api/columns", this.column)
          .then(res => {
            this.column.name = '';
            this.$emit("column-added", 'column');
          })
          .catch(err => {
            this.handleErrors(err);
          });
      } else {
        axios
          .patch("/api/columns/"+this.column.id, this.column)
          .then(res => {
            this.column.name = '';
            this.$emit("column-added", 'column');
          })
          .catch(err => {
            this.handleErrors(err);
          });
      }
      
    },
    handleErrors(err) {
      if (err.response && err.response.status === 422) {
        // We have a validation error
        const errorBag = err.response.data.errors;
        if (errorBag.name) {
          this.errorMessage = errorBag.name[0];
        } else {
          this.errorMessage = err.response.message;
        }
      } else {
        console.log(err.response);
      }
    }
  }
};
</script>
