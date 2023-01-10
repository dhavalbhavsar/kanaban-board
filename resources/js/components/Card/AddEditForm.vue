<template>
    <modal name="add-edit-card-modal" height="auto">
      <form @submit.prevent="handleAddNewCard" ref="cardAdd">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-if="card.id === null">Add a card</h5>
            <h5 class="modal-title" v-else>Edit a card</h5>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="card-name" class="col-form-label">Card Name:</label>
              <input type="text" id="card-name" class="form-control" placeholder="Enter a card name" v-model.trim="card.name" />
              <div v-show="errorMessage">
                <span class="text-danger">
                  {{ errorMessage }}
                </span>
              </div>
            </div>
            <div class="form-group">
                <label for="card-description" class="col-form-label">Description:</label>
                <textarea id="card-description" class="form-control" placeholder="Enter a card description" v-model.trim="card.description"></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button @click="$emit('card-canceled', 'card')" type="reset" class="btn btn-secondary">
              cancel
            </button>
            <button type="submit" class="btn btn-primary" v-if="card.id === null">
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
    name: 'AddEditCardModal',
    data() {
      return {
        card: {
          id: null,
          column_id: null,
          name: "",
          description: ""
        },
        errorMessage: ""
      };
    },
    methods: {
  
      setCard(card) {
        this.card = card;
      },

      setColumnId(columnId) {
        this.card.column_id = columnId;
      },    
  
      handleAddNewCard() {
  
        // Basic validation
        if (!this.card.name) {
          this.errorMessage = "The card name field is required";
          return;
        }
  
        // Send new card to server
        if(this.card.id === null) {
          axios
            .post("/api/cards", this.card)
            .then(res => {
                this.card = {
                    id: null,
                    column_id: null,
                    name: "",
                    description: ""
                };
              this.$emit("card-added", 'card');
            })
            .catch(err => {
              this.handleErrors(err);
            });
        } else {
          axios
            .patch("/api/cards/"+this.card.id, this.card)
            .then(res => {
                this.card = {
                    id: null,
                    column_id: null,
                    name: "",
                    description: ""
                };
              this.$emit("card-added", 'card');
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
  