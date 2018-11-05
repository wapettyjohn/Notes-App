<template>
  <div class="wrapper">
    <div class="left-col">
      <div class="notes">
        <div
          class="note"
          v-for="note in notes" 
          v-bind:key="note.id"
          v-bind:class="{
              'alert-light': (note.color === 'light'),
              'alert-dark': (note.color === 'dark'),
              'alert-warning': (note.color === 'warning'),
              'alert-info': (note.color === 'info'),
              'alert-success': (note.color === 'success'),
              'alert-primary': (note.color === 'primary'),
              'alert-secondary': (note.color === 'secondary'),
              'alert-danger': (note.color === 'danger'),
            }">
          <span 
            class="note-date"
            role="button" 
            @click="editNote(note)">
            {{ note.updated_at }}
          </span>

          <button @click="deleteNote(note.id)" class="btn btn-primary delete-note">Delete</button>
        </div>
      </div> <!-- .notes -->
    </div>

    <div class="right-col">
      <form @submit.prevent="addNote" class="form">
        <div class="form-group note-form-group row">
          <textarea 
            class="form-control note-form-textarea" 
            placeholder="Add your note here..." 
            v-model="note.note"
            v-bind:class="{
              'alert-light': (note.color === 'light'),
              'alert-dark': (note.color === 'dark'),
              'alert-warning': (note.color === 'warning'),
              'alert-info': (note.color === 'info'),
              'alert-success': (note.color === 'success'),
              'alert-primary': (note.color === 'primary'),
              'alert-secondary': (note.color === 'secondary'),
              'alert-danger': (note.color === 'danger'),
            }">
          </textarea>
        </div>

        <div class="form-group note-form-controls row">
          <select id="inputColor" class="input-color form-control" v-model="note.color">
            <option value="secondary" selected="true">Gray</option>
            <option value="dark">Black</option>
            <option value="light">White</option>
            <option value="primary">Red</option>
            <option value="danger">Orange</option>
            <option value="warning">Yellow</option>
            <option value="success">Green</option>
            <option value="info">Blue</option>
          </select>
        
          <button type="submit" class="btn btn-outline-secondary">Save</button>
          <button @click="clearForm()" class="btn btn-primary">Cancel</button>
        </div>
      </form>
     </div> 
  </div>
</template>

<script>
export default {
  data() {
    return {
      notes: [],
      note: {
        id: '',
        note: '',
        color: 'secondary',
        user_id: window.Laravel.userId,
        updated_at: ''
      },
      note_id: '',
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
      this.note.color   = 'secondary';
    }
  }
};
</script>