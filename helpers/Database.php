<?php

class Database
{
    /**
     * Приватный метод, который выполняет sql запрос
     *
     * @param string $sql_query [зарпос]
     *
     * @return array|int|mixed
     */
    static private function makeRequest($sql_query)
    {
        try {
            $user = "root";
            $pass = "";
            $pdo = new PDO("mysql:dbname=;host=localhost", $user, $pass);

            $stmt = $pdo->prepare($sql_query);

            try {
                $pdo->beginTransaction();
                $stmt->execute();
                $id = $pdo->lastInsertId();
                $pdo->commit();
                return [
                    'id' => $id,
                    'data' => $stmt->fetchAll()
                ];

            } catch(PDOExecption $e) {
                $pdo->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }
        } catch( PDOExecption $e ) {
            print "Error!: " . $e->getMessage() . "</br>";
        }

    }

    /**
     * Метод возвращает книгу по ее названию (можно указывать название не полностью) вместе с ее автором
     *
     * @param string $book_name [название книги]
     *
     * @return array|int|mixed
     */
    static public function getBookByName($book_name = null)
    {
        $sql_query = "
            select *
            from misha.authors_books
            left join misha.authors on authors_books.author_id = authors.author_id
            left join misha.books on authors_books.book_id = books.book_id
            where books.book_name like '$book_name%'
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод возвращает весь список авторов
     *
     * @return array|int|mixed
     */
    static public function getAllAuthors()
    {
        $sql_query = "
            select *
            from misha.authors
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод создает новую книгу в базе данных по названию книги и иденификатору автора
     *
     * @param string $book_name [название книги]
     * @param int    $author_id [индентификатор автора книги]
     *
     * @param array $book_pic
     * @param string $description
     *
     * @return array|int|mixed
     */
    static public function createBook($book_name, $author_id, $book_pic, $description)
    {

        $file_name = time() . $book_pic['name'];
        $path = "../uploads/$file_name";
        file_put_contents($path, file_get_contents($book_pic['tmp_name']));

        $sql_query = "
            insert into misha.books (book_name, book_pic, description)
            VALUES ('$book_name', '$file_name', '$description')
        ";

        $data =  self::makeRequest($sql_query);
        $book_id = $data['id'];
        $sql_query = "
            insert into misha.authors_books (author_id, book_id)
            values ($author_id, $book_id)
        ";



        return self::makeRequest($sql_query);
    }

    /**
     * Метод удаляет книгу по ее идентификатору
     *
     * @param int $book_id [идентификатор книги]
     *
     * @return array|int|mixed
     */
    static public function deleteBook($book_id){

        $sql_query = "
            delete from misha.authors_books
            where authors_books.book_id=$book_id
        ";

        self::makeRequest($sql_query);

        $sql_query = "
            delete from misha.books
            where books.book_id=$book_id
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод удаляет автора по его идентификатору
     *
     * @param int $author_id [идентификатор автора]
     *
     * @return array|int|mixed
     */
    static public function deleteAuthor($author_id){
        $sql_query = "
            delete from misha.authors
            where authors.author_id=$author_id
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод создает автора по его ФИО
     *
     * @param string $author_name [ФИО автора]
     * @param $photo
     *
     * @return array|int|mixed
     */
    static public function createAuthor($author_name, $photo){
        $file_name = time() . $photo['name'];
        $path = "../uploads/$file_name";
        file_put_contents($path, file_get_contents($photo['tmp_name']));

        $sql_query = "
            insert into misha.authors (author_name, author_pic)
            values ('$author_name', '$file_name')
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод обновляет автора по его идентификатору
     *
     * @param int $author_id [Идентификатор автора, которого нужно изменить]
     * @param string $author_name [ФИО автора, на которое нужно изменить]
     *
     * @return array|int|mixed
     */
    static public function updateAuthor($author_id, $author_name){
        $sql_query = "
            update misha.authors
            set author_name = '$author_name'
            where author_id = $author_id
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод обновляет данные по книге
     *
     * @param int $book_id [Идентификатор книги, которую нужно изменить]
     * @param int $author_id [Идентификатор автора]
     * @param string $book_name [Назваине книги]
     *
     * @return array|int|mixed
     */
    static public function updateBook($book_id, $author_id, $book_name){
        $sql_query = "
            update misha.books
            set book_name='$book_name', author_id=$author_id
            where book_id=$book_id
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод добавляет автора к книге
     *
     * @param int $author_id
     * @param int $book_id
     *
     * @return array|int|mixed
     */
    public static function addAuthorToBook($author_id, $book_id)
    {
        $sql_query = "
            INSERT INTO misha.authors_books (author_id, book_id)
            VALUES ($author_id, $book_id)
        ";

        return self::makeRequest($sql_query);
    }


    /**
     * @param int $author_id
     * @param int $book_id
     *
     * @return array|int|mixed
     */
    public static function deleteAuthorFromBook(int $author_id, int $book_id)
    {
        $sql_query = "
            DELETE FROM misha.authors_books 
            WHERE author_id=$author_id AND book_id=$book_id
        ";

        return self::makeRequest($sql_query);
    }

    /**
     * Метод создает базу данных, 3 таблицы и заполняет их
     *
     * @return array|int|mixed
     */
    static public function createDatabase()
    {
        $sql_query = "
            DROP DATABASE IF EXISTS misha;
            
            CREATE DATABASE IF NOT EXISTS misha;
            USE misha;
            
            CREATE TABLE authors
            (
                author_id   int auto_increment primary key,
                author_pic  varchar(255),
                author_name varchar(255)
            );
            
            CREATE TABLE books
            (
                book_id        int auto_increment primary key,
                book_name      varchar(255),
                book_pic       varchar(255),
                description    text
            );
            
            CREATE TABLE authors_books
            (
                author_book_id int auto_increment primary key,
                author_id int,
                book_id int,
                FOREIGN KEY (author_id) REFERENCES authors (author_id),
                FOREIGN KEY (book_id) REFERENCES books (book_id)
            );
            
            INSERT INTO authors (author_name, author_pic) values ('Рей Брэдбери', 'Ray_Bradbury.jpg');
            INSERT INTO authors (author_name, author_pic) values ('Джордж Оруэлл', 'orwell.jpeg');
            INSERT INTO authors (author_name, author_pic) values ('Грегори Дэвид Робертс', 'greg_david.jpg');
            INSERT INTO authors (author_name, author_pic) values ('Михаил Афанасьевич Булгаков', 'bulgakov.jpg');
            INSERT INTO authors (author_name, author_pic) values ('Эрих Мария Ремарк', 'maria.jpg');
            INSERT INTO authors (author_name, author_pic) values ('Даниел Киз', 'daniel_keyes.jpg');
            INSERT INTO authors (author_name, author_pic) values ('Джером Д. Сэлинджер', 'Salinger.jpg');
            INSERT INTO authors (author_name, author_pic) values ('Оскар Уайльд', 'wild.png');
            INSERT INTO authors (author_name, author_pic) values ('Антуан де Сент-Экзюпери', 'sent.jpeg');
            
            INSERT INTO books (book_name, book_pic, description) values ('451° по Фаренгейту', '451.jpg', 'Научно-фантастический роман-антиутопия Рэя Брэдбери, изданный в 1953 году.');
            INSERT INTO books (book_name, book_pic, description) values ('1984', '1984.jpeg', 'Роман-антиутопия Джорджа Оруэлла, изданный в 1949 году. Название романа, его терминология и даже имя автора впоследствии стали нарицательными и употребляются для обозначения общественного уклада, напоминающего описанный в романе «1984» тоталитаризм.');
            INSERT INTO books (book_name, book_pic, description) values ('Шантарам', 'shantaram.jpg', 'Роман австралийского писателя Грегори Дэвида Робертса. Основой для книги послужили события собственной жизни автора.');
            INSERT INTO books (book_name, book_pic, description) values ('Мастер и Маргарита', 'master.jpg', 'Роман Михаила Афанасьевича Булгакова, работа над которым началась в конце 1920-х годов и продолжалась вплоть до смерти писателя.');
            INSERT INTO books (book_name, book_pic, description) values ('Три товарища', '3tov.jpg', 'Роман Эриха Марии Ремарка, работу над которым он начал в 1932 году.');
            INSERT INTO books (book_name, book_pic, description) values ('Цветы для Элджернона', 'cvety.jpg', 'Научно-фантастический рассказ Дэниела Киза. Первоначально издан в апрельском номере «Журнала фэнтези и научной фантастики» за 1959 год.');
            INSERT INTO books (book_name, book_pic, description) values ('Над пропастью во ржи',  'rji.jpg', 'Роман американского писателя Джерома Сэлинджера. В нём от лица 17-летнего юноши по имени Холден откровенно рассказывается о его обострённом восприятии американской действительности и неприятии общих канонов и морали современного общества.');
            INSERT INTO books (book_name, book_pic, description) values ('Портрет Дориана Грея', 'gray.jpg', 'Единственный опубликованный роман Оскара Уайльда. В жанровом отношении представляет смесь романа воспитания с моральной притчей.');
            INSERT INTO books (book_name, book_pic, description) values ('Маленький принц',  'princ.jpeg', 'Аллегорическая повесть-сказка, наиболее известное произведение Антуана де Сент-Экзюпери.');
            
            INSERT INTO authors_books (author_id, book_id) values (1,1);
            INSERT INTO authors_books (author_id, book_id) values (2,2);
            INSERT INTO authors_books (author_id, book_id) values (3,3);
            INSERT INTO authors_books (author_id, book_id) values (4,4);
            INSERT INTO authors_books (author_id, book_id) values (5,5);
            INSERT INTO authors_books (author_id, book_id) values (6,6);
            INSERT INTO authors_books (author_id, book_id) values (7,7);
            INSERT INTO authors_books (author_id, book_id) values (8,8);
            INSERT INTO authors_books (author_id, book_id) values (9,9);
        ";

        return self::makeRequest($sql_query);
    }

}
