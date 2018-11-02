<template>
  <div>
    <h2>Notes</h2>
    
    <form @submit.prevent="addNote" class="mb-3">
      <div class="form-group">
        <textarea class="form-control" placeholder="Note" v-model="note.note"></textarea>
      </div>

      <div class="form-group">
        <input type="text" class="form-control" placeholder="Color" v-model="note.color">
      </div>

      <button type="submit" class="btn btn-light btn-block">Save</button>
    </form>
    
    <button @click="clearForm()" class="btn btn-danger btn-block">Cancel</button>
    
    <div class="card card-body mb-2" v-for="note in notes" v-bind:key="note.id">
      <p>{{ note.note }}</p>
      
      <hr>
      
      <button @click="editNote(note)" class="btn btn-warning mb-2">Edit</button>
      
      <button @click="deleteNote(note.id)" class="btn btn-danger">Delete</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      notes: [],
      note: {
        id:    '',
        note:  '',
        color: '',
        user_id: window.Laravel.userId
      },
      note_id: '',
      //pagination: {},
      edit: false
    };
  },

  created() {
    this.fetchNotes();
  },

  methods: {
    fetchNotes(page_url) {
      page_url = page_url || '/api/notes';
      axios.get(page_url)
        .then(res => res.data)
        .then(res => {
          this.notes = res.data;
        })
        .catch(err => console.log(err));
    },
    deleteNote(id) {
      if (confirm('Are You Sure?')) {
        axios.delete(`api/note/${id}`)
          //.then(res => res.json())
          .then(res => res.data)
          .then(data => {
            this.fetchNotes();
          })
          .catch(err => console.log(err));
      }
    },
    addNote() {
      if (this.edit === false) {
        axios({
          method: 'post',
          url: 'api/note',
          data: JSON.stringify(this.note),
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(res => res.data)
          .then(data => {
            this.clearForm();
            this.fetchNotes();
          })
          .catch(err => console.log(err));
      } else {
        axios({
          method: 'put',
          url: 'api/note',
          data: JSON.stringify(this.note),
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(res => res.data)
          .then(data => {
            this.clearForm();
            this.fetchNotes();
          })
          .catch(err => console.log(err));
      }
    },
    editNote(note) {
      this.edit         = true;
      this.note.id      = note.id;
      this.note.note_id = note.id;
      this.note.note    = note.note;
      this.note.color   = note.color;
    },
    clearForm() {
      this.edit         = false;
      this.note.id      = null;
      this.note.note_id = null;
      this.note.note    = '';
      this.note.color   = '';
    }
  }
};
</script>