<template>
  <div>
    <div class="dropdown">
      <button
        class="btn btn-secondary dropdown-toggle modal-color"
        type="button"
        id="dropdownMenuButton1"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        {{ 
          modelId === null 
            ? (models.length > 0 ? models[0].description : '') 
            : (models.find(model => model.id === modelId)?.description || '')
        }}
      </button>
        <ul class="dropdown-menu col-3 modal-background" aria-labelledby="dropdownMenuButton1">
            <li v-for="(model, index) in models" :key="index">
                <a @click="selectModel(model.id)" class="dropdown-item modal-item d-flex justify-content-between">
                    {{ model.description }}
                    <i v-if="modelId === null ? index === 0 : modelId === model.id" class="bi bi-check-circle-fill"></i>
                </a>
            </li>
        </ul>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
    data() {
        return {
            models: [],
            modelId: null,
        }
    },
    computed: {
        ...mapGetters(["getModelId"]),
    },
    methods: {
        ...mapActions(["fetchListModel"]),
        ...mapMutations(["setSelectedModel"]),
        async listModel() {
            try {
                const response = await this.fetchListModel();
                this.models = response;
            } catch (error) {
                console.log(error);
            }
        },
        selectModel(id) {
            this.modelId = id;
            this.setSelectedModel(id);
        }
    },
    mounted() {
        this.listModel();
        this.setSelectedModel(1);
    },
    watch: {
        getModelId: {
            handler(newData) {
              this.modelId = newData;
            }
        }
    }
}
</script>
<style scoped>
.modal-color {
  background-color: rgb(33, 33, 33);
  border: 0px;
  border-radius: 15px;
  font-size: 18px;
  font-weight: 700;
  padding: 10px 15px 10px 15px;
}

.modal-color:hover {
  background-color: rgb(40, 40, 40);
  border-radius: 15px;
}
.modal-background {
    background-color: rgb(40, 40, 40);
    border: 1px solid rgb(59, 59, 59);
}

.modal-item {
    color: white;
    cursor: pointer;
}

.modal-item:hover {
    background-color: rgb(59, 59, 59);
}
</style>
