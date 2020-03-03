new Vue({
    el: '#list',
    data() {
        return {
            items: null,
            book_pic: null,
            author_pic: null,
            description: null,
            authors: null,
            ShowAddBook: false,
            ShowBlockAuthorAdd: false,
            author_id: null
        }
    },
    mounted() {
        this.searchByBook();
        this.AuthorsAddBook();
        setTimeout(function(){
            document.getElementsByName("book_list")[0].click();
        }, 2300);

    },
    methods: {
        selectItem: function (item) {
            document.getElementById("Textbox1").value = item["author_name"];
            this.book_pic = "/uploads/" + item["book_pic"];
            this.author_pic = "/uploads/" + item["author_pic"];
            this.description = item["description"];
            this.author_id = item["author_id"];
        },
        Search: function(searchField){
            this.searchByBook(searchField.target.value);
        },
        deleteItem: function (item) {
            const url = '/backend/delete-book.php';
            const params = new FormData();
            params.append('book_id', item["book_id"]);
            axios({
                method: 'POST',
                url: url,
                data: params,
            }).then(function (res) {
                console.log(res.data);
                location.reload();
            });
        },
        searchByBook: function (SearchField = "") {
            const url = '/backend/search-by-book.php?book_name=' + SearchField;
            axios
                .get(url)
                .then(response => {
                    this.items = response.data.message;
                })
        },
        AuthorsAddBook: function () {
            const url = '/backend/get-authors.php';
            axios
                .get(url)
                .then(response => this.authors = response.data.message)
        },
        ShowBlockBook : function(){
            this.ShowAddBook = !this.ShowAddBook;
            document.getElementById("book_name_add").value = null;
            document.getElementById("book_description_add").value = null;
        },
        AddBook: function () {
            let selectAuthor = document.getElementById("Select_book").value;
            let book_name_add = document.getElementById("book_name_add").value;
            let book_pic_add = document.querySelector('#profile_pic').files[0];
            let book_description_add = document.getElementById("book_description_add").value;
            const url = '/backend/create-book.php';
            const params = new FormData();
            params.append('book_name', book_name_add);
            params.append('author_id', selectAuthor);
            params.append('book_pic', book_pic_add);
            params.append('description', book_description_add);
            axios({
                method: 'POST',
                url: url,
                data: params,
            }).then(function (res) {
                console.log(res.data);
                location.reload();
            });
        },
        ShowBlockAuthor: function () {
            this.ShowBlockAuthorAdd = !this.ShowBlockAuthorAdd;
        },
        Addauthor: function () {
            let author_name_add = document.querySelector('#author_name_add').value;
            let author_pic_add = document.getElementById("author_pic_add").files[0];
            const url = '/backend/create-author.php';
            const params = new FormData();
            params.append('author_name', author_name_add);
            params.append('author_pic', author_pic_add);
            axios({
                method: 'POST',
                url: url,
                data: params,
            }).then(function (res) {
                console.log(res.data);
                location.reload();
            });
        },
        Edit_author: function () {
            document.getElementById('Textbox1').disabled = !document.getElementById('Textbox1').disabled;
            if(document.getElementById('Textbox1').disabled){
                let author_id = this.author_id;
                let author_name = document.getElementById("Textbox1").value;
                const url = '/backend/update-author.php';
                const params = new FormData();
                params.append('author_id', author_id);
                params.append('author_name', author_name);
                axios({
                    method: 'POST',
                    url: url,
                    data: params,
                }).then(function (res) {
                    console.log(res.data);
                    location.reload();
                });
            }
        }

    }
});