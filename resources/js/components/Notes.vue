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
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchNotes(pagination.prev_page_url)">Previous</a></li>

        <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
    
        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchNotes(pagination.next_page_url)">Next</a></li>
      </ul>
    </nav>
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
        color: ''
      },
      note_id:    '',
      pagination: {},
      edit:       false
    };
  },

  created() {
    this.fetchNotes();
  },

  methods: {
    fetchNotes(page_url) {
      let vm = this;
      page_url = page_url || '/api/notes';
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.notes = res.data;
          vm.makePagination(res.meta, res.links);
        })
        .catch(err => console.log(err));
    },
    makePagination(meta, links) {
      let pagination = {
        current_page:  meta.current_page,
        last_page:     meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };

      this.pagination = pagination;
    },
    deleteNote(id) {
      if (confirm('Are You Sure?')) {
        fetch(`api/note/${id}`, {
          method: 'delete'
        })
          .then(res => res.json())
          .then(data => {
            alert('Note Removed');
            this.fetchNotes();
          })
          .catch(err => console.log(err));
      }
    },
    addNote() {
      if (this.edit === false) {
        // Add
        fetch('api/note', {
          method: 'post',
          body: JSON.stringify(this.note),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Note Added');
            this.fetchNotes();
          })
          .catch(err => console.log(err));
      } else {
        // Update
        fetch('api/note', {
          method: 'put',
          body: JSON.stringify(this.note),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Note Updated');
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